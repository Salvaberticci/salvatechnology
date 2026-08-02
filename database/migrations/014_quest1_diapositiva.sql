-- ============================================================
-- Migración 014: Quest #01 de la Clase 1 del Curso 1 -> versión
-- oficial de las diapositivas (uploads/diapositivas/clase-1.html)
-- "Desmontaje de un Software de Tu Vida Diaria"
-- Persistencia (5 datos) / Capas (2+2) / Plataformas (web/desktop/móvil)
-- Fecha: 2026-08-02
-- ============================================================

UPDATE actividades
SET titulo = '🎯 Quest #01: "Desmontaje de un Software de Tu Vida Diaria"',
    descripcion = 'Desmontaje de un Software de Tu Vida Diaria.

Elige una aplicación que utilices todos los días (Instagram, Spotify, MercadoLibre, Uber o WhatsApp) y documenta en tu cuaderno/documento:

[1] LA PERSISTENCIA: ¿Qué 5 datos específicos crees que guarda esa app sí o sí en su Base de Datos permanente?

[2] LAS CAPAS: Identifica 2 funciones que ocurran en el Frontend y 2 validaciones que pertenezcan al Backend.

[3] LAS PLATAFORMAS: Si la usas en Web, Desktop o Móvil, ¿qué diferencias operativas o de sensores notas entre ellas?

Trae tus respuestas preparadas para analizarlas y auditarlas en nuestra próxima Clase en Vivo.'
WHERE id = 771;