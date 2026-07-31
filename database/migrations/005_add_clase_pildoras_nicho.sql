-- ============================================================
-- Migración 005: Nueva clase en "Píldoras de Conocimiento"
-- "Cómo ganar MÁS trabajando MENOS: El poder del Nicho en Software"
-- Video YouTube: 4lbOEplfmss
-- Fecha: 2026-07-31
-- ============================================================

INSERT INTO lecciones (curso_id, titulo, descripcion, video_url, orden)
SELECT 2,
       'Cómo ganar MÁS trabajando MENOS: El poder del Nicho en Software',
       '¿Sientes que compites contra miles de desarrolladores por las mismas migajas? En este video te explico por qué elegir un nicho y un cliente ideal es la estrategia definitiva para subir tus tarifas y dominar el mercado freelance.

[LO QUE APRENDERÁS HOY]

- Por qué el "Todólogo" siempre será el profesional peor pagado.
- Cómo identificar un nicho rentable en el mundo del desarrollo de software.
- El perfil del Cliente Ideal: quién tiene el problema y, sobre todo, el presupuesto.
- Cómo la especialización te permite crear procesos más rápidos y cobrar por valor, no por hora.

Si te dedicas al código y quieres dejar de ser un "commodity" para convertirte en un socio estratégico para tus clientes, suscríbete y dale a la campanita.',
       'https://www.youtube.com/embed/4lbOEplfmss',
       (SELECT COALESCE(MAX(orden), 0) + 1 FROM lecciones WHERE curso_id = 2)
WHERE NOT EXISTS (
    SELECT 1 FROM lecciones
    WHERE curso_id = 2 AND video_url = 'https://www.youtube.com/embed/4lbOEplfmss'
);
