-- ============================================================
-- Migración 006: Nueva clase en "Píldoras de Conocimiento"
-- "Cómo Crear una OFERTA IRRESISTIBLE para tus Servicios de Software"
-- Video YouTube: d_yxLtuL3qA
-- Fecha: 2026-07-31
-- ============================================================

INSERT INTO lecciones (curso_id, titulo, descripcion, video_url, orden)
SELECT 2,
       'Cómo Crear una OFERTA IRRESISTIBLE para tus Servicios de Software',
       '¿Estás cansado de competir por precio en plataformas de freelancers o de que los clientes te vean como un simple "gasto" y no como una inversión?

En este video, te revelo la estrategia exacta para dejar de vender "horas de código" y empezar a vender soluciones de alto valor. Aprenderás a estructurar una oferta que haga que sea lógicamente imposible que tu cliente ideal te diga que no, enfocándote en el Retorno de Inversión (ROI) y en la resolución de problemas de negocio reales.

[EN ESTE VIDEO APRENDERÁS]

- ¿Por qué fallan las ofertas?
- Los 3 pilares de una oferta ganadora.
- La ecuación de valor.
- Psicología del high ticket.

[RECURSOS MENCIONADOS]

- Carpeta de Recursos (Pitch High Ticket + Guion de Ventas): carpeta compartida en Drive.
- Calculadora de ROI para Software: demo.salvanovasolutions.online

¿Quieres que te ayude a escalar tu negocio de software? Si eres desarrollador y buscas mentoría personalizada para elegir un nicho rentable, mejorar tu oferta y conseguir mejores clientes, agenda un diagnóstico: https://salvatechnology.online',
       'https://www.youtube.com/embed/d_yxLtuL3qA',
       (SELECT COALESCE(MAX(orden), 0) + 1 FROM lecciones WHERE curso_id = 2)
WHERE NOT EXISTS (
    SELECT 1 FROM lecciones
    WHERE curso_id = 2 AND video_url = 'https://www.youtube.com/embed/d_yxLtuL3qA'
);
