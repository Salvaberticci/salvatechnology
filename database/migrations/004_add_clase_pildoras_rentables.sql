-- ============================================================
-- Migración 004: Nueva clase en "Píldoras de Conocimiento"
-- "Guía para crear sistemas web rentables: De la idea al cliente ideal"
-- Video YouTube: XKa75UEmtfw
-- Fecha: 2026-07-31
-- ============================================================

INSERT INTO lecciones (curso_id, titulo, descripcion, video_url, orden)
SELECT 2,
       'Guía para crear sistemas web rentables: De la idea al cliente ideal',
       '¿Cansado de aprender lenguajes de programación y no saber cómo monetizarlos? En este video te revelo mi metodología exacta para crear aplicaciones web rentables identificando problemas reales en negocios locales (como los ISPs) y transformándolos en un negocio bajo un modelo de pago inicial + mantenimiento mensual.

No necesitas ser un "influencer" ni tener miles de seguidores para vivir del código. Te enseño cómo lo hago yo con un stack sencillo (PHP, JavaScript) y cómo la Inteligencia Artificial me ayuda a entregar resultados en tiempo récord.

[LO QUE APRENDERÁS HOY]

- El Cliente Ideal: Cómo detectar negocios que aún sufren.
- Stack Tecnológico: Por qué sigo usando HTML, CSS, JS y PHP para soluciones de alto valor.
- Potenciado por IA: Cómo herramientas como Antigravity aceleran el desarrollo.
- Modelo de Negocio: Cómo cobrar por el desarrollo y asegurar un ingreso recurrente por mantenimiento.
- Marca Personal: Estrategias de captación y el poder del boca a boca.
- Las Dos Rutas: El camino largo (solo) vs. el camino rápido (mentoría).

Si te gustó este video, no olvides suscribirte y dejar tu duda en los comentarios. ¡Estaré respondiendo personalmente!',
       'https://www.youtube.com/embed/XKa75UEmtfw',
       (SELECT COALESCE(MAX(orden), 0) + 1 FROM lecciones WHERE curso_id = 2)
WHERE NOT EXISTS (
    SELECT 1 FROM lecciones
    WHERE curso_id = 2 AND video_url = 'https://www.youtube.com/embed/XKa75UEmtfw'
);
