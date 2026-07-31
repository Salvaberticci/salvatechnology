<?php
/**
 * Base de conocimiento dinámica del chatbot.
 * Genera el catálogo de cursos, lecciones y actividades directamente
 * desde la base de datos para que SALVA AI siempre tenga información
 * actualizada de la plataforma.
 */

function chatbot_generar_catalogo($pdo): string
{
    $md = "\n\n## CATÁLOGO DE CURSOS (información oficial de la plataforma, de la base de datos)\n"
        . "Cuando te pregunten por los cursos, sus clases, módulos o contenidos, usa EXCLUSIVAMENTE esta sección. Esta es la fuente de verdad.\n\n";

    $cursos = $pdo->query(
        "SELECT id, titulo, descripcion, categoria, duracion, precio
         FROM cursos WHERE activo = 1 ORDER BY creado_en DESC"
    )->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cursos)) {
        return $md . "Actualmente no hay cursos publicados.\n";
    }

    foreach ($cursos as $curso) {
        $md .= "### Curso: " . $curso['titulo'] . "\n";
        $md .= "- Categoría: " . ($curso['categoria'] ?: 'General')
            . " | Duración: " . ($curso['duracion'] ?: 'No especificada')
            . " | " . ($curso['precio'] > 0 ? 'Curso premium (requiere suscripción)' : 'Curso gratuito') . "\n";
        $md .= "- Descripción: " . trim(preg_replace('/\s+/', ' ', (string) $curso['descripcion'])) . "\n";

        $stmt = $pdo->prepare(
            "SELECT l.id, l.titulo, l.descripcion,
                    (SELECT COUNT(*) FROM actividades a WHERE a.leccion_id = l.id) AS n_actividades
             FROM lecciones l WHERE l.curso_id = ? ORDER BY l.orden, l.id"
        );
        $stmt->execute([$curso['id']]);
        $lecciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $md .= "- Total de lecciones: " . count($lecciones) . "\n";
        if (!empty($lecciones)) {
            $md .= "- Lecciones:\n";
            foreach ($lecciones as $lec) {
                $desc = trim(preg_replace('/\s+/', ' ', (string) $lec['descripcion']));
                $desc = mb_substr($desc, 0, 200);
                $md .= "  - Clase: " . $lec['titulo'];
                if ($desc !== '') {
                    $md .= " → " . $desc;
                }
                if ((int) $lec['n_actividades'] > 0) {
                    $md .= " [" . $lec['n_actividades'] . " actividad(es)]";
                }
                $md .= "\n";
            }
        }
        $md .= "\n";
    }

    return $md;
}
