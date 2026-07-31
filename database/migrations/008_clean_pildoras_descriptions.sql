-- ============================================================
-- Migración 008: Limpiar descripciones de las 3 clases de
-- "Píldoras de Conocimiento" — eliminar menciones de
-- suscripción a YouTube ("suscríbete", "campanita", comentarios)
-- Fecha: 2026-07-31
-- ============================================================

UPDATE lecciones SET descripcion =
'¿Cansado de aprender lenguajes de programación y no saber cómo monetizarlos? En este video te revelo mi metodología exacta para crear aplicaciones web rentables identificando problemas reales en negocios locales (como los ISPs) y transformándolos en un negocio bajo un modelo de pago inicial + mantenimiento mensual.

No necesitas ser un "influencer" ni tener miles de seguidores para vivir del código. Te enseño cómo lo hago yo con un stack sencillo (PHP, JavaScript) y cómo la Inteligencia Artificial me ayuda a entregar resultados en tiempo récord.

[LO QUE APRENDERÁS HOY]

- El Cliente Ideal: Cómo detectar negocios que aún sufren.
- Stack Tecnológico: Por qué sigo usando HTML, CSS, JS y PHP para soluciones de alto valor.
- Potenciado por IA: Cómo herramientas como Antigravity aceleran el desarrollo.
- Modelo de Negocio: Cómo cobrar por el desarrollo y asegurar un ingreso recurrente por mantenimiento.
- Marca Personal: Estrategias de captación y el poder del boca a boca.
- Las Dos Rutas: El camino largo (solo) vs. el camino rápido (mentoría).'
WHERE id = 891;

UPDATE lecciones SET descripcion =
'¿Sientes que compites contra miles de desarrolladores por las mismas migajas? En este video te explico por qué elegir un nicho y un cliente ideal es la estrategia definitiva para subir tus tarifas y dominar el mercado freelance.

[LO QUE APRENDERÁS HOY]

- Por qué el "Todólogo" siempre será el profesional peor pagado.
- Cómo identificar un nicho rentable en el mundo del desarrollo de software.
- El perfil del Cliente Ideal: quién tiene el problema y, sobre todo, el presupuesto.
- Cómo la especialización te permite crear procesos más rápidos y cobrar por valor, no por hora.

Si te dedicas al código y quieres dejar de ser un "commodity" para convertirte en un socio estratégico para tus clientes, esta clase es para ti.'
WHERE id = 892;
