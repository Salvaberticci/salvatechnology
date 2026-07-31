# CHANGELOG

Historial de cambios y modificaciones realizadas en **SalvaTechnology Academy**.

## 2026-07-31

### SALVA AI Chatbot
- Panel lateral derecho colapsable en todas las páginas del dashboard.
- Resizable arrastrando el borde izquierdo; el ancho se guarda en `localStorage`.
- Collapse corregido: de `translateX(260px)` a `translateX(100%)` para ocultarse por completo a cualquier ancho.
- Historial de conversación por sesión e indicador de escritura.
- Integración con Groq API (`api/chatbot.php`) + respuestas fallback por palabras clave.
- **`chatbot-instructions.md`**: archivo central con instrucciones del sistema (tono amigable tipo profesor, restricción de contexto solo tecnología/programación/plataforma, info del creador, planes de pago, funcionalidades y consejos).
- `config/chatbot_config.php` ahora lee el `system_prompt` directamente desde `chatbot-instructions.md`.

### Configuración y Despliegue
- **`config/app.php`** (gitignore): constante `BASE_URL` centralizada; reemplazados 60+ `/salvatechnology/` hardcodeados.
- **`config/db.php`** (gitignore) + plantillas `db.example.php` y `app.example.php` para nuevos entornos.
- Landing pages y archivos de `auth/` corregidos con `require_once config/app.php` y `<base href>`.
- Despliegue en Namecheap (subdominio `academy.salvatechnology.online`) vía SSH + `git pull`.
- `uploads/ebooks/` y `uploads/diapositivas/` ahora trackeados en git (solo `uploads/comprobantes/` en gitignore).

### Experiencia Visual (Matrix Rain)
- Animación de lluvia de caracteres katakana (cyber matrix) en el dashboard.
- **Extraída a componentes reutilizables**: `partials/matrix-rain.php` (canvas + estilo) y `js/matrix-rain.js` (lógica).
- Agregada a `cursos.php` y `planes.php`.

### Loader y Audio
- Loader siempre visible en cada recarga (se eliminó la persistencia en `sessionStorage`).
- `AudioManager`: `AudioContext` ahora se crea de forma perezosa al primer clic del usuario.

### E-Books Interactivos
- **`uploads/interactive/`**: nueva carpeta para e-books tipo videojuego.
- **`clase-1.html`**: e-book interactivo de 8 capítulos con:
  - Estilo cyberpunk heredado de las diapositivas.
  - Sistema de XP, niveles y 4 logros desbloqueables.
  - 5 quizzes tipo desafío con retroalimentación instantánea (se resalta la respuesta correcta y permite reintentar).
  - Tarjetas flip con explicaciones, barras de progreso, diálogos del guía "SALVA", analogías del mundo real y ejemplos de código.
  - Progreso guardado en `localStorage`.
- `curso_ver.php`: botón "🎮 E-Book Interactivo" + modal de pantalla completa.
- Botón "CERRAR E-BOOK" funciona vía `postMessage`.

### Video
- Clase 1 (lección 781): asignada URL de video de **Bunny Stream** (`player.mediadelivery.net`).
- `curso_ver.php`: detección de `mediadelivery.net` para renderizar como iframe.

## 2026-07-30

### Chatbot
- Creación del panel de chatbot con UI mockup.
- Integración Groq API (los keys devuelven 403 desde Venezuela por bloqueo geográfico; respuestas fallback activas hasta el despliegue en servidor US/EU).

### Correcciones
- Slide typo: "Salvatore Vertichi" → "Salvatore Berticci" en `uploads/diapositivas/clase-1.html`.
- Favicon `logo.png` agregado a las 20 páginas (landing + dashboard).

### Despliegue
- Configuración SSH key (`~/.ssh/salvatechnology`), remote tracking (`origin/master`).
- Repositorio `github.com/Salvaberticci/salvatechnology` con todos los commits en `master`.
