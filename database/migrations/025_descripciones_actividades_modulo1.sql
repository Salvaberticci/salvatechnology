-- ============================================================
-- Migración 025: Descripciones del Módulo 1 con formato
-- "Qué se verá en esta clase + Objetivo final" y EJEMPLOS
-- resueltos agregados a cada actividad para guiar al alumno.
-- Idempotente: UPDATEs por título (robusto ante distintos ids).
-- Fecha: 2026-08-07
-- ============================================================

-- ============================================================
-- 1) DESCRIPCIONES DE LAS LECCIONES DEL MÓDULO 1
-- ============================================================

UPDATE lecciones
SET descripcion = '¿Qué se verá en esta clase? El mapa completo del oficio de Ingeniero de Software: la jerarquía Dato → Información → Sistema, el Modelo Cliente-Servidor, la diferencia entre RAM y Persistencia, las 3 plataformas (Web/Desktop/Mobile) y el mindset de desarrollador asistido por IA con la metodología ADD. Objetivo final: que entiendas dónde vive cada pieza de un sistema real y domines la visión end-to-end antes de escribir tu primera línea de código.'
WHERE curso_id = 1
  AND titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*:'
  AND CHAR_LENGTH(descripcion) < 400;

UPDATE lecciones
SET descripcion = '¿Qué se verá en esta clase? La diferencia entre "picar código" y hacer ingeniería, y los 3 pilares: Dato (unidad mínima aislada), Información (dato con contexto) y Sistema (automatización con reglas de negocio). Objetivo final: que aprendas a clasificar cualquier elemento de una app real —un número, un mensaje, un algoritmo— como Dato, Información o Sistema, porque toda la arquitectura posterior se construye sobre esa clasificación.'
WHERE curso_id = 1
  AND titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*\.1[[:space:]]*:'
  AND CHAR_LENGTH(descripcion) < 400;

UPDATE lecciones
SET descripcion = '¿Qué se verá en esta clase? Cómo se divide el software en cliente, servidor y base de datos, y cómo viajan las peticiones y respuestas a través de la red con el Modelo Cliente-Servidor, incluyendo la regla de oro de la arquitectura: nunca confiar en el Frontend. Objetivo final: que puedas trazar el viaje completo de una acción del usuario —desde el clic hasta la respuesta pintada en pantalla— identificando en qué capa ocurre cada paso.'
WHERE curso_id = 1
  AND titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*\.2[[:space:]]*:'
  AND CHAR_LENGTH(descripcion) < 400;

UPDATE lecciones
SET descripcion = '¿Qué se verá en esta clase? La delimitación estricta de responsabilidades: el Frontend pide y muestra (presentación e interacción, captura de eventos y validaciones estéticas en el dispositivo del usuario), el Backend decide y procesa (autoridad central: autenticación, permisos, reglas de negocio y orquestación de la persistencia) y la Base de Datos almacena y recuerda (conservación duradera, integridad referencial y consultas rápidas). También el mapa de las 4 ubicaciones del dato (RAM del cliente → payload HTTP/JSON en tránsito → memoria del servidor → disco persistente, Single Source of Truth) y la comunicación entre capas vía HTTP/REST (GET/POST/PUT/DELETE) y SQL/NoSQL (drivers u ORM). Objetivo final: que sepas clasificar cada función y cada dato de una aplicación en la capa correcta y justificar con propiedad por qué vive ahí.'
WHERE curso_id = 1
  AND titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = '¿Qué se verá en esta clase? Las diferencias de recursos, ventana y contexto de uso entre las 3 plataformas: Web (navegador, acceso bajo demanda, PHP/Laravel), Desktop (ventana instalada, potencia máxima, Python) y Mobile (sensores y portabilidad, Flutter). Objetivo final: que aprendas a decidir qué plataforma conviene según el contexto del usuario —por ejemplo, si un sistema necesita cámara, GPS o impresora— en lugar de elegir por moda.'
WHERE curso_id = 1
  AND titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*\.4[[:space:]]*:'
  AND CHAR_LENGTH(descripcion) < 400;

-- ============================================================
-- 2) ACTIVIDADES: agregar EJEMPLO RESUELTO a cada una
-- (JOIN por título de lección para ser robusto ante ids)
-- ============================================================

-- Actividad de la Clase Maestra 1 (Quest #01 Desmontaje)
UPDATE actividades a
JOIN lecciones l ON l.id = a.leccion_id
SET a.descripcion = CONCAT(a.descripcion, '

EJEMPLO RESUELTO (WhatsApp):
[1] Persistencia: mensajes, contactos, número verificado, fotos enviadas, configuración de privacidad.
[2] Capas: Frontend captura el teclado y pinta el ✓/✓✓; Backend valida el número, cifra de extremo a extremo y entrega el mensaje.
[3] Plataformas: en Mobile hay notificaciones push, cámara y GPS; en Web no hay sensores y depende del navegador abierto.')
WHERE l.titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*:';

-- Actividad de la Clase 1.1 (Análisis Anatómico)
UPDATE actividades a
JOIN lecciones l ON l.id = a.leccion_id
SET a.descripcion = CONCAT(a.descripcion, '

EJEMPLO RESUELTO (Plataforma de delivery):
[1] Dominio: PedidosYa.
[2] 3 Datos crudos: latitud del repartidor (-34.6037), ID del pedido (4521), tiempo estimado (25 min).
[3] Información: "El repartidor está a 5 minutos y tu pedido 4521 llega caliente".
[4] Sistema: regla de negocio "si la distancia < 1 km, notificar llegada" → acción automatizada: push "¡Tu pedido está cerca!".')
WHERE l.titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*\.1[[:space:]]*:';

-- Actividad de la Clase 1.2 (Autopsia Anatómica)
UPDATE actividades a
JOIN lecciones l ON l.id = a.leccion_id
SET a.descripcion = CONCAT(a.descripcion, '

EJEMPLO RESUELTO (Spotify):
[1] 3 eventos de Frontend: tocar Play/Pausa, arrastrar el slider de la canción, crear una playlist desde el menú lateral.
[2] 2 reglas de Backend anti-fraude: validar que la cuenta tenga suscripción Premium antes de permitir descargas, y detectar reproducciones automáticas falsas (bots) para no pagar regalías.
[3] 4 datos críticos en la BD: biblioteca musical, playlists, historial de reproducción y canciones descargadas por el usuario.')
WHERE l.titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*\.2[[:space:]]*:';

-- Actividad de la Clase 1.3 (Clasificación Front/Back/BD)
UPDATE actividades a
JOIN lecciones l ON l.id = a.leccion_id
SET a.descripcion = CONCAT(a.descripcion, '

EJEMPLO RESUELTO (Instagram — función "Dar Me Gusta"):
- Frontend: captura el doble tap, cambia el corazón a rojo en la UI y envía POST /posts/123/like.
- Backend: valida la autenticación del usuario y evita likes duplicados.
- Base de Datos: inserta un registro en la tabla likes (user_id, post_id).
- Ubicación del dato: persistente en la Base de Datos (con contador en caché).')
WHERE l.titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*\.3[[:space:]]*:';

-- Actividad de la Clase 1.4 (Tabla Comparativa POS)
UPDATE actividades a
JOIN lecciones l ON l.id = a.leccion_id
SET a.descripcion = CONCAT(a.descripcion, '

EJEMPLO RESUELTO (POS - Punto de Venta):
- Web: ventaja → se accede desde cualquier navegador sin instalar y se actualiza central; limitación → necesita internet y no controla la impresora física de la caja.
- Desktop: ventaja → funciona offline y con máxima velocidad sobre la caja registradora; limitación → hay que instalar y actualizar cada máquina manualmente.
- Mobile: ventaja → portátil, escanea códigos de barras y sirve para entregas con GPS; limitación → pantalla y batería limitadas, y la tienda de apps revisa cada actualización.')
WHERE l.titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*\.4[[:space:]]*:';