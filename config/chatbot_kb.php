<?php
/**
 * Base de conocimiento del chatbot con recuperación RAG ligera.
 * No se envía todo el conocimiento en cada mensaje: se seleccionan
 * solo las secciones relevantes a la pregunta del usuario (búsqueda
 * por palabras clave con tildes y stopwords en español).
 */

define('CHATBOT_INSTRUCTIONS_FILE', __DIR__ . '/../chatbot-instructions.md');

define('CHATBOT_STOPWORDS', [
    'de', 'la', 'el', 'los', 'las', 'que', 'en', 'y', 'a', 'o', 'u', 'un', 'una',
    'unos', 'unas', 'para', 'por', 'con', 'del', 'al', 'es', 'son', 'se', 'su',
    'sus', 'lo', 'no', 'si', 'pero', 'como', 'cual', 'cuando', 'donde', 'cuales',
    'tambien', 'mas', 'menos', 'hay', 'tiene', 'tienen', 'puedo', 'puedes',
    'quiero', 'quieres', 'me', 'te', 'le', 'mi', 'tu', 'este', 'esta', 'esto',
    'ese', 'esa', 'eso', 'algo', 'todo', 'toda', 'todos', 'todas', 'cuanto',
    'cuantos', 'cuantas', 'informacion', 'info', 'sobre', 'acerca', 'dime',
    'cuentame', 'explicame', 'contame', 'cualquier', 'donde', 'estan', 'estas',
]);

function chatbot_quitar_tildes(string $t): string
{
    $mapa = ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n',
             'Á' => 'a', 'É' => 'e', 'Í' => 'i', 'Ó' => 'o', 'Ú' => 'u', 'Ü' => 'u', 'Ñ' => 'n'];
    return strtr($t, $mapa);
}

function chatbot_tokenizar(string $texto): array
{
    $t = mb_strtolower(chatbot_quitar_tildes($texto));
    $t = preg_replace('/[^a-z0-9\s]/', ' ', $t);
    $palabras = preg_split('/\s+/', trim($t));
    $tokens = [];
    foreach ($palabras as $p) {
        if ($p === '') continue;
        if (!preg_match('/^\d+$/', $p) && mb_strlen($p) < 3) continue;
        if (in_array($p, CHATBOT_STOPWORDS, true)) continue;
        $tokens[] = $p;
    }
    return array_values(array_unique($tokens));
}

function chatbot_leer_instrucciones(): array
{
    $archivo = CHATBOT_INSTRUCTIONS_FILE;
    $lineas = preg_split('/\R/', file_exists($archivo) ? file_get_contents($archivo) : '');
    $chunks = [];
    $titulo = null;
    $cuerpo = [];
    foreach ($lineas as $linea) {
        if (preg_match('/^#{2,4}\s+(.+)$/', $linea, $m)) {
            if ($titulo !== null) {
                $chunks[] = ['titulo' => $titulo, 'contenido' => implode("\n", $cuerpo)];
            }
            $titulo = trim($m[1]);
            $cuerpo = [];
        } else {
            $cuerpo[] = $linea;
        }
    }
    if ($titulo !== null) {
        $chunks[] = ['titulo' => $titulo, 'contenido' => implode("\n", $cuerpo)];
    }
    return $chunks;
}

function chatbot_chunks_bd(PDO $pdo): array
{
    $chunks = [];
    $cursos = $pdo->query(
        "SELECT id, titulo, descripcion, categoria, duracion, precio
         FROM cursos WHERE activo = 1 ORDER BY creado_en DESC"
    )->fetchAll(PDO::FETCH_ASSOC);

    foreach ($cursos as $curso) {
        $stmt = $pdo->prepare(
            "SELECT l.titulo, l.descripcion,
                    (SELECT COUNT(*) FROM actividades a WHERE a.leccion_id = l.id) AS n_act
             FROM lecciones l WHERE l.curso_id = ? ORDER BY l.orden, l.id"
        );
        $stmt->execute([$curso['id']]);
        $lecciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $por_clase = [];
        foreach ($lecciones as $l) {
            if (preg_match('/^Clase\s+(\d+)/i', $l['titulo'], $m)) {
                $por_clase[$m[1]][] = $l;
            } else {
                $por_clase['general'][] = $l;
            }
        }

        $desc = trim(preg_replace('/\s+/', ' ', (string) $curso['descripcion']));
        $premium = $curso['precio'] > 0 ? 'requiere suscripción' : 'gratuito';
        $overview = "- Categoría: " . ($curso['categoria'] ?: 'General')
            . "\n- Duración: " . ($curso['duracion'] ?: 'No especificada')
            . "\n- Acceso: " . $premium
            . "\n- Total de lecciones: " . count($lecciones)
            . "\n- Descripción: " . mb_substr($desc, 0, 300);
        $chunks[] = ['titulo' => 'Curso: ' . $curso['titulo'] . ' (visión general)', 'contenido' => $overview];

        if (!empty($por_clase['general'])) {
            $cuerpo = [];
            foreach ($por_clase['general'] as $it) {
                $d = trim(preg_replace('/\s+/', ' ', (string) $it['descripcion']));
                $linea = '- ' . $it['titulo'];
                if ($d !== '') $linea .= ' → ' . mb_substr($d, 0, 150);
                if ($it['n_act'] > 0) $linea .= ' [' . $it['n_act'] . ' act]';
                $cuerpo[] = $linea;
            }
            $chunks[] = ['titulo' => 'Curso: ' . $curso['titulo'] . ' — lecciones generales', 'contenido' => implode("\n", $cuerpo)];
        }

        foreach ($por_clase as $clase => $items) {
            if ($clase === 'general') continue;
            $cuerpo = [];
            foreach ($items as $it) {
                $d = trim(preg_replace('/\s+/', ' ', (string) $it['descripcion']));
                $linea = '- ' . $it['titulo'];
                if ($d !== '') $linea .= ' → ' . mb_substr($d, 0, 150);
                if ($it['n_act'] > 0) $linea .= ' [' . $it['n_act'] . ' act]';
                $cuerpo[] = $linea;
            }
            $chunks[] = ['titulo' => 'Curso: ' . $curso['titulo'] . ' — ' . $items[0]['titulo'], 'contenido' => implode("\n", $cuerpo)];
        }
    }

    return $chunks;
}

function chatbot_todos_chunks(PDO $pdo): array
{
    static $cache = null;
    if ($cache === null) {
        $cache = array_merge(chatbot_leer_instrucciones(), chatbot_chunks_bd($pdo));
    }
    return $cache;
}

function chatbot_stems(string $t): array
{
    $variantes = [$t];
    if (substr($t, -2) === 'es' && strlen($t) > 4) {
        $variantes[] = substr($t, 0, -2);
        $variantes[] = substr($t, 0, -1);
    } elseif (substr($t, -1) === 's' && strlen($t) > 3) {
        $variantes[] = substr($t, 0, -1);
    }
    return array_values(array_unique($variantes));
}

function chatbot_tokens_expandidos(array $tokens): array
{
    $set = [];
    foreach ($tokens as $t) {
        foreach (chatbot_stems($t) as $s) {
            $set[$s] = true;
        }
    }
    return array_keys($set);
}

function chatbot_buscar_chunks(PDO $pdo, string $query, int $limite = 5): array
{
    $tokens = chatbot_tokenizar($query);
    if (empty($tokens)) return [];

    $tokensExp = chatbot_tokens_expandidos($tokens);
    $quiereCursos = in_array('curso', $tokensExp, true);

    $resultados = [];
    foreach (chatbot_todos_chunks($pdo) as $c) {
        $tituloSet = array_flip(chatbot_tokens_expandidos(chatbot_tokenizar($c['titulo'])));
        $cuerpoSet = array_flip(chatbot_tokens_expandidos(chatbot_tokenizar($c['contenido'])));
        $score = 0;
        foreach ($tokensExp as $t) {
            if (isset($tituloSet[$t])) $score += 3;
            if (isset($cuerpoSet[$t])) $score += 1;
        }
        if ($quiereCursos && mb_strpos($c['titulo'], '(visión general)') !== false) {
            $score += 1000;
        }
        if ($score > 0) {
            $resultados[] = ['score' => $score, 'titulo' => $c['titulo'], 'contenido' => $c['contenido']];
        }
    }

    usort($resultados, function ($a, $b) {
        if ($b['score'] !== $a['score']) return $b['score'] <=> $a['score'];
        return mb_strlen($a['contenido']) <=> mb_strlen($b['contenido']);
    });

    return array_slice($resultados, 0, $limite);
}

function chatbot_core(): string
{
    $siempre = ['Personalidad y Tono', 'Restricciones de Contexto', 'Formato de Respuestas'];
    $partes = [];
    foreach (chatbot_leer_instrucciones() as $c) {
        if (in_array($c['titulo'], $siempre, true)) {
            $partes[] = "# " . $c['titulo'] . "\n" . trim($c['contenido']);
        }
    }
    $partes[] = "# Uso del Contexto\n\nPara responder sobre los cursos, clases, la plataforma, planes de suscripción, pagos o sobre Salvatore, usa ÚNICAMENTE el bloque \"## CONTEXTO RELEVANTE PARA ESTA PREGUNTA\" incluido más abajo en este mensaje. Si la respuesta no está en ese contexto, responde con conocimiento técnico general de programación o indica amablemente que esa información no está publicada todavía.\n\n# Concisión Obligatoria\n\n- Responde SIEMPRE de forma breve y directa: máximo 150-200 palabras por mensaje.\n- Usa listas cortas (3-5 puntos) y párrafos de 1-2 líneas.\n- Ve al grano en la primera frase; no repitas la pregunta ni des intro largas.\n- Si la respuesta sería muy larga (ej. enumerar muchas clases), resume los puntos clave y ofrece ampliar: \"¿Quieres que te liste las clases X en detalle?\".\n- NUNCA dejes una respuesta a medias: si no alcanzas a terminar, recorta el detalle y termina con la idea principal completa.";
    return implode("\n\n", $partes);
}

function chatbot_contexto_rag(PDO $pdo, string $query): string
{
    $seleccion = chatbot_buscar_chunks($pdo, $query, 5);
    $md = "\n\n## CONTEXTO RELEVANTE PARA ESTA PREGUNTA\n";
    if (empty($seleccion)) {
        return $md . "\n(No hay información específica en la base de conocimiento para esta pregunta. Usa tu conocimiento técnico general.)\n";
    }
    foreach ($seleccion as $c) {
        $md .= "\n### " . $c['titulo'] . "\n" . trim($c['contenido']) . "\n";
    }
    return $md;
}
