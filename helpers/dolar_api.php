<?php
/**
 * Cotización del Dólar Oficial (BCV) vía https://ve.dolarapi.com/v1/dolares/oficial
 * Con caché en archivo para no golpear la API en cada request.
 */

function dolarOficialBcv() {
    $cacheFile = sys_get_temp_dir() . '/salvatechnology_dolar_cache.php';
    $ttl = 300; // 5 minutos

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $ttl) {
        $cached = @include $cacheFile;
        if (is_array($cached) && isset($cached['promedio']) && $cached['promedio'] > 0) {
            return $cached;
        }
    }

    $resultado = ['promedio' => 0, 'compra' => null, 'venta' => null, 'fuente' => 'BCV', 'fecha' => '', 'error' => ''];

    $ch = curl_init('https://ve.dolarapi.com/v1/dolares/oficial');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 6,
        CURLOPT_CONNECTTIMEOUT => 4,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_USERAGENT => 'SalvaTechnology/1.0',
    ]);
    $res = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($res !== false && $httpCode === 200) {
        $data = json_decode($res, true);
        if (is_array($data) && !empty($data['promedio'])) {
            $result['promedio'] = (float)$data['promedio'];
            $result['compra'] = isset($data['compra']) ? (float)$data['compra'] : null;
            $result['venta'] = isset($data['venta']) ? (float)$data['venta'] : null;
            $result['fecha'] = $data['fechaActualizacion'] ?? '';
            $result['fuente'] = $data['fuente'] ?? $result['fuente'];
        } else {
            $result['error'] = 'Respuesta inesperada de la API';
        }
    } else {
        $result['error'] = $curlError !== '' ? $curlError : 'HTTP ' . $httpCode;
    }

    if ($result['promedio'] > 0) {
        file_put_contents($cacheFile, '<?php return ' . var_export($result, true) . ';', LOCK_EX);
    }

    return $result;
}

/** Convierte un monto en USD a bolívares según el promedio BCV. */
function usdABs($usd) {
    $cotizacion = dolarOficialBcv();
    $dolar = (int)($cotizacion['promedio'] ?? 0);
    if ($dolar <= 0) return null;
    return round((float)$usd * $dolar, 2);
}

/** Formatea un monto de bolívares: Bs. 1.234,56 */
function formatoBs($monto) {
    return 'Bs. ' . number_format($monto, 2, ',', '.');
}