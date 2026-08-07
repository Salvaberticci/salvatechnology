-- ============================================================
-- Migración 026: DESCRIPCIONES UNIFORMES para TODAS las
-- lecciones del Curso 1 (22 clases maestras + 88 subclases).
-- Formato profesional tipo "resumen de YouTube":
--   Párrafo 1: "En esta clase se hablará de ..."
--   Párrafo 2: "El objetivo final de la clase es ..."
-- Separados por línea en blanco (nl2br respeta los párrafos).
-- Idempotente: UPDATE por título (robusto ante distintos ids).
-- Fecha: 2026-08-07
-- ============================================================

-- ========== CLASE 1 (maestra + subclases 1.1 - 1.4) ==========

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la anatomía completa de los sistemas modernos: la jerarquía Dato → Información → Sistema, el Modelo Cliente-Servidor, la diferencia entre la memoria RAM y la Persistencia, las 3 plataformas (Web, Desktop y Mobile) y la mentalidad del desarrollador asistido por IA con la metodología ADD.

El objetivo final de la clase es que entiendas dónde vive cada pieza de un sistema real y domines la visión end-to-end —de la interfaz al disco— antes de escribir tu primera línea de código.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la diferencia entre "picar código" y hacer ingeniería, y de los 3 pilares del oficio con ejemplos concretos: el Dato (unidad mínima aislada, sin significado propio), la Información (el dato con contexto que responde una pregunta) y el Sistema (información + regla de negocio + automatización).

El objetivo final de la clase es que aprendas a clasificar cualquier elemento de una app real como Dato, Información o Sistema y a identificar la regla de negocio que lo automatiza, porque toda la arquitectura posterior se construye sobre esa clasificación.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*\.1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de cómo se divide el software en cliente, servidor y base de datos, y de cómo viajan las peticiones y respuestas a través de la red con el Modelo Cliente-Servidor, incluyendo la regla de oro de la arquitectura: nunca confiar en el Frontend.

El objetivo final de la clase es que puedas trazar el viaje completo de una acción del usuario —desde el clic hasta la respuesta pintada en pantalla— identificando en qué capa ocurre cada paso.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*\.2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la delimitación estricta de responsabilidades: el Frontend pide y muestra (presentación, captura de eventos y validaciones estéticas), el Backend decide y procesa (autenticación, permisos, reglas de negocio y orquestación) y la Base de Datos almacena y recuerda (persistencia, integridad referencial y consultas rápidas). También del mapa de las 4 ubicaciones del dato (RAM del cliente → payload HTTP/JSON → memoria del servidor → disco persistente) y de la comunicación entre capas vía HTTP/REST y SQL/NoSQL.

El objetivo final de la clase es que sepas clasificar cada función y cada dato de una aplicación en la capa correcta y justificar con propiedad por qué vive ahí.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de las diferencias de recursos, ventana y contexto de uso entre las 3 plataformas: Web (navegador, acceso bajo demanda, PHP/Laravel), Desktop (ventana instalada, potencia máxima, Python) y Mobile (sensores y portabilidad, Flutter).

El objetivo final de la clase es que aprendas a decidir qué plataforma conviene según el contexto del usuario —por ejemplo, si un sistema necesita cámara, GPS o impresora— en lugar de elegir por moda.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*\.4[[:space:]]*:';

-- ========== CLASE 2 (maestra + subclases 2.1 - 2.4) ==========

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de qué es un algoritmo, de las estructuras de control (condicionales y bucles) y de cómo escribir lógica en pseudocódigo antes de codificar.

El objetivo final de la clase es que domines el pensamiento algorítmico y seas capaz de convertir cualquier problema real en una secuencia lógica de pasos, lista para programar en cualquier lenguaje.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de cómo representar la lógica paso a paso mediante diagramas de flujo y flujogramas, con sus símbolos estándar y reglas de construcción.

El objetivo final de la clase es que aprendas a dibujar y leer flujogramas que modelen procesos reales, para diseñar algoritmos de forma visual antes de escribir una sola línea de código.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+2[[:space:]]*\.1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de cómo almacenar y manipular datos usando variables, tipos de datos primitivos (entero, decimal, texto, booleano) y operadores aritméticos, lógicos y de comparación.

El objetivo final de la clase es que sepas elegir el tipo de dato correcto y usar operadores para resolver cálculos reales —como un carrito con IVA y descuento— dentro de tus algoritmos.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+2[[:space:]]*\.2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de las decisiones lógicas con estructuras condicionales (if/else y casos múltiples) aplicadas a reglas de negocio reales.

El objetivo final de la clase es que programes la toma de decisiones de un sistema —como la aprobación de un crédito según el score del cliente— usando condiciones claras y bien estructuradas.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+2[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la repetición de procesos con bucles (for y while) y del recorrido de listas de datos.

El objetivo final de la clase es que automatices tareas repetitivas en tus algoritmos —como revisar el stock de todo el inventario y disparar una alerta cuando un producto baje de 5 unidades.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+2[[:space:]]*\.4[[:space:]]*:';

-- ========== CLASE 3 (maestra + subclases 3.1 - 3.4) ==========

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de cómo diseñar esquemas de bases de datos relacionales sin redundancias y bien conectados, usando el modelo Entidad-Relación y las formas de normalización.

El objetivo final de la clase es que modeles la estructura de datos de cualquier negocio —como una clínica médica con pacientes, médicos, citas e historial— antes de crear las tablas en SQL.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de qué es una entidad, cuáles son sus atributos y qué es la clave primaria (PK) que identifica cada registro de forma única.

El objetivo final de la clase es que definas correctamente las entidades de un modelo —como Usuario y Producto— con sus atributos y claves primarias, la base de todo esquema de base de datos.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+3[[:space:]]*\.1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de cómo las claves foráneas (FK) conectan tablas entre sí y mantienen la integridad referencial de los datos.

El objetivo final de la clase es que relaciones tablas reales —como Clientes con sus Facturas— usando claves foráneas correctas que garanticen que ningún dato quede huérfano.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+3[[:space:]]*\.2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de los tipos de relaciones entre entidades, con foco en las relaciones muchos a muchos (M:N) y su resolución con tablas puente.

El objetivo final de la clase es que modeles relaciones complejas —como Estudiantes y Cursos— creando la tabla intermedia correcta que las represente fielmente.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+3[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la normalización de bases de datos: la Primera (1FN), Segunda (2FN) y Tercera Forma Normal (3FN) para eliminar redundancias y anomalías.

El objetivo final de la clase es que apliques las formas normales a un esquema real —como una factura— y lo conviertas en un modelo limpio, sin datos duplicados ni inconsistencias.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+3[[:space:]]*\.4[[:space:]]*:';

-- ========== CLASE 4 (maestra + subclases 4.1 - 4.4) ==========

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de cómo viajan los datos por la web: el protocolo HTTP, el formato JSON y el funcionamiento de las APIs REST.

El objetivo final de la clase es que entiendas el lenguaje de comunicación entre sistemas y seas capaz de documentar los endpoints de una aplicación —como los de una tienda online— como lo haría un desarrollador profesional.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+4[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del protocolo HTTP/HTTPS y de los métodos (verbos) más importantes: GET, POST, PUT y DELETE, con sus usos típicos.

El objetivo final de la clase es que sepas elegir el verbo correcto para cada operación de tu sistema —consultar, crear, actualizar o eliminar— mapeando cada acción de negocio a su método HTTP.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+4[[:space:]]*\.1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la estructura del formato JSON: objetos, arreglos, llaves y valores, y cómo se usa como lenguaje de intercambio de datos entre sistemas.

El objetivo final de la clase es que escribas y leas JSON correctamente —como el perfil de un usuario— porque es el formato que viaja por las APIs que consumirás a lo largo del curso.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+4[[:space:]]*\.2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de los códigos de estado HTTP: 200, 201, 400, 401, 404, 500 y sus significados en la comunicación cliente-servidor.

El objetivo final de la clase es que interpretes cualquier respuesta de una API con solo mirar su código de estado y sepas diagnosticar errores —como un 404 o un 500— de inmediato.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+4[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de qué es una API RESTful: los principios de diseño, los endpoints, los verbos y el formato de las respuestas.

El objetivo final de la clase es que consumas APIs públicas reales desde tu código, haciendo peticiones correctas y parseando las respuestas para usarlas en tu aplicación.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+4[[:space:]]*\.4[[:space:]]*:';

-- ========== CLASE 5 (maestra + subclases 5.1 - 5.4) ==========

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la vida interna de una página web: el DOM, el renderizado del navegador y los protocolos que la hacen funcionar.

El objetivo final de la clase es que entiendas qué ocurre dentro del navegador cuando una página se carga y se pinta, para que puedas diseñar el plano funcional de una app web —como una de reservas de cine— con conocimiento técnico real.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+5[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del Document Object Model (DOM): la estructura del documento HTML y su manipulación en vivo con JavaScript.

El objetivo final de la clase es que modifiques el DOM de una página real desde la consola del navegador (F12), cambiando textos, estilos y elementos al vuelo.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+5[[:space:]]*\.1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la maquetación moderna de interfaces con CSS Flexbox y CSS Grid.

El objetivo final de la clase es que maquetes layouts profesionales y responsivos —como la estructura de una landing page— usando las técnicas que usan los frameworks actuales.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+5[[:space:]]*\.2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la diferencia entre renderizado en el cliente (CSR) y renderizado en el servidor (SSR), con sus ventajas y desventajas.

El objetivo final de la clase es que compares ambos enfoques y sepas elegir cuál conviene según la aplicación —SEO, velocidad de carga o interactividad— explicando por qué.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+5[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del almacenamiento en el navegador: localStorage, sessionStorage, cookies e IndexedDB, y cuándo usar cada uno.

El objetivo final de la clase es que decidas correctamente dónde guardar datos del lado del cliente —como el estado de sesión de WhatsApp Web— y apliques la técnica adecuada para cada caso.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+5[[:space:]]*\.4[[:space:]]*:';

-- ========== CLASE 6 (maestra + subclases 6.1 - 6.4) ==========

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la programación de escritorio de alto rendimiento: acceso a archivos, procesos, hardware y bases de datos locales.

El objetivo final de la clase es que entiendas qué puede hacer un programa de escritorio que la web no puede, para que diseñes soluciones potentes —como un punto de venta offline que sincroniza— con recursos reales del sistema.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+6[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de los sistemas de archivos locales: lectura y escritura en disco desde tus programas.

El objetivo final de la clase es que leas y proceses archivos reales —como un CSV de ventas— y escribas resultados en disco, dominando la persistencia local de una app de escritorio.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+6[[:space:]]*\.1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la gestión de procesos e hilos: la concurrencia y la administración de tareas en un sistema operativo.

El objetivo final de la clase es que entiendas cómo el sistema operativo reparte el tiempo de la CPU y sepas identificar procesos reales de tu equipo, conectando la teoría con el administrador de tareas.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+6[[:space:]]*\.2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la integración con dispositivos físicos: periféricos y hardware desde un programa de escritorio.

El objetivo final de la clase es que conectes tu software con hardware real —como un lector de código de barras y una impresora térmica— para construir sistemas de punto de venta completos.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+6[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de las bases de datos embebidas, con foco en SQLite y la persistencia local dentro de una app de escritorio.

El objetivo final de la clase es que construyas una base de datos que funcione sin servidor —como el respaldo offline de tus ventas— y sepas cuándo usarla frente a una base de datos en red.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+6[[:space:]]*\.4[[:space:]]*:';

-- ========== CLASE 7 (maestra + subclases 7.1 - 7.4) ==========

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de cómo funcionan las apps móviles en cada sistema operativo: iOS y Android, y de la diferencia entre desarrollo nativo y multiplataforma.

El objetivo final de la clase es que entiendas el ecosistema móvil por dentro para diseñar aplicaciones completas —como una app de delivery con seguimiento en vivo— sabiendo qué es posible en cada plataforma.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+7[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del ciclo de vida de una app móvil: los estados por los que pasa la aplicación en primer y segundo plano.

El objetivo final de la clase es que entiendas los estados de una app real —como los de Google Maps al minimizarse— para diseñar comportamientos correctos al pausar, reanudar o cerrar tu aplicación.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+7[[:space:]]*\.1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de los sensores del dispositivo móvil: cámara, GPS, giroscopio y el sistema de permisos de cada plataforma.

El objetivo final de la clase es que solicites y uses correctamente los permisos de cámara y ubicación en tus apps, entendiendo las reglas de privacidad de iOS y Android.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+7[[:space:]]*\.2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de las notificaciones push: cómo se envían al dispositivo mediante payloads JSON desde un servidor.

El objetivo final de la clase es que construyas y analices payloads de notificaciones reales, con título, cuerpo y datos extra, entendiendo cómo llegan a la pantalla del usuario.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+7[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del diseño adaptativo UI/UX: interfaces que responden correctamente a cualquier tamaño de pantalla.

El objetivo final de la clase es que diseñes interfaces móviles fluidas —como una tabla que se reorganiza en pantallas pequeñas— aplicando principios de UI/UX centrados en el usuario.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+7[[:space:]]*\.4[[:space:]]*:';

-- ========== CLASE 8 (maestra + subclases 8.1 - 8.4) ==========

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del ciclo de vida del desarrollo de software (SDLC), de Git y GitHub para el control de versiones, y de las metodologías ágiles y CI/CD.

El objetivo final de la clase es que trabajes de forma profesional y colaborativa: con versiones, ramas, revisiones y despliegues automáticos, como en un equipo real de desarrollo.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+8[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de los repositorios, los commits y las ramas en Git, la base del trabajo con control de versiones.

El objetivo final de la clase es que crees commits correctos y organices tu proyecto con ramas —main, develop y features— manteniendo un historial limpio y recuperable.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+8[[:space:]]*\.1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de cómo integrar ramas y resolver conflictos de código cuando varios cambios chocan.

El objetivo final de la clase es que resuelvas un conflicto de merge real sin perder trabajo, entendiendo qué ocurre por debajo cuando Git pide tu decisión.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+8[[:space:]]*\.2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de las Pull Requests: la revisión de código colaborativa antes de integrar cambios al proyecto.

El objetivo final de la clase es que crees y revises pull requests profesionales, con descripciones claras, como parte del flujo de trabajo en equipo.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+8[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de CI/CD: la integración continua y el despliegue continuo con pipelines automáticos.

El objetivo final de la clase es que entiendas cómo funciona un pipeline y lo apliques para que tus pruebas y despliegues se ejecuten solos en cada cambio.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+8[[:space:]]*\.4[[:space:]]*:';

-- ========== CLASE 9 (maestra + subclases 9.1 - 9.4) ==========

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del entorno de trabajo Vibe Coding: la configuración de OpenCode, los motores de generación de código y la IA como compañero de desarrollo.

El objetivo final de la clase es que prepares tu entorno de desarrollo con IA —repositorio, archivo AGENTS.md y maqueta generada— para trabajar a la velocidad de un equipo completo.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+9[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de cómo instalar y configurar OpenCode, la herramienta de Vibe Coding del curso, y de cómo enlazar los proveedores de IA.

El objetivo final de la clase es que tengas tu entorno funcional de punta a punta, listo para que la IA genere, explique y refactorice código contigo.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+9[[:space:]]*\.1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del archivo AGENTS.md: el manual de instrucciones que le das a la IA para que entienda tu proyecto y tus convenciones.

El objetivo final de la clase es que escribas un AGENTS.md profesional para tu proyecto, enseñándole a la IA tus reglas, stack y estilo antes de trabajar.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+9[[:space:]]*\.2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la generación de interfaces de usuario con IA: cómo pedir pantallas completas con prompts visuales precisos.

El objetivo final de la clase es que generes una interfaz completa —como un panel de administración— con IA y la refines hasta dejarla lista para producción.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+9[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del prompting técnico: cómo estructurar peticiones efectivas a la IA con Contexto, Entrada, Reglas y Salida.

El objetivo final de la clase es que domines el prompt estructurado y obtengas respuestas útiles a la primera, convirtiéndote en el director de la IA y no en su empleado.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+9[[:space:]]*\.4[[:space:]]*:';

-- ========== CLASE 10 (maestra + subclases 10.1 - 10.4) ==========

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de cómo construir backend con ayuda de IA: APIs, tablas de base de datos y autenticación de usuarios.

El objetivo final de la clase es que generes un backend funcional completo —API, base de datos, autenticación y CRUD— con la IA como socio de desarrollo.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+10[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de cómo diseñar esquemas de base de datos con ayuda de IA: migraciones SQL bien estructuradas y coherentes con el modelo de negocio.

El objetivo final de la clase es que generes y entiendas migraciones SQL —como las de un sistema de productos— validadas por ti, no solo escritas por la IA.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+10[[:space:]]*\.1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la creación de endpoints CRUD (Crear, Leer, Actualizar, Eliminar) con la asistencia de la IA.

El objetivo final de la clase es que construyas el CRUD completo de una entidad —como Productos— con sus rutas, controladores y validaciones funcionando de punta a punta.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+10[[:space:]]*\.2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la autenticación con JWT: el registro y el inicio de sesión de usuarios con tokens seguros.

El objetivo final de la clase es que implementes registro y login reales en tu API, protegiendo los endpoints que requieren identidad de usuario.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+10[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la validación en el servidor: la capa de middleware que protege tu API de datos inválidos o ataques.

El objetivo final de la clase es que implementes middleware de validación y protección en tus endpoints, asegurando que ningún dato tramposo llegue a tu base de datos.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+10[[:space:]]*\.4[[:space:]]*:';

-- ========== CLASE 11 (maestra + subclases 11.1 - 11.4) ==========

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de cómo conectar el frontend con el backend: la integración de la vista con el servidor usando IA.

El objetivo final de la clase es que construyas una app web interactiva de punta a punta, donde lo que el usuario ve refleje fielmente lo que vive en la base de datos.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+11[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de cómo consumir APIs desde el frontend: fetch, promesas y renderizado de datos del servidor en pantalla.

El objetivo final de la clase es que conectes tu interfaz con tu backend —mostrando, creando y editando datos reales sin recargar la página.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+11[[:space:]]*\.1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de formularios y estados: la validación visual de entradas y la gestión del estado de la interfaz.

El objetivo final de la clase es que construyas formularios robustos con validación visual inmediata, que guíen al usuario y rechacen errores antes de llegar al servidor.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+11[[:space:]]*\.2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de los estados de carga y error en las interfaces: skeleton loaders y manejo elegante de fallos de red.

El objetivo final de la clase es que tu app nunca se vea "rota": cargando con esqueletos, mostrando errores claros y manteniendo al usuario informado.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+11[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de los filtros en tiempo real: buscadores y listados que responden mientras escribes, sin recargar la página.

El objetivo final de la clase es que implementes un buscador instantáneo sobre datos del servidor, combinando frontend reactivo y backend eficiente.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+11[[:space:]]*\.4[[:space:]]*:';

-- ========== CLASE 12 (maestra + subclases 12.1 - 12.4) ==========

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la auditoría de código con IA: depurar errores, refactorizar y desplegar tu aplicación en producción.

El objetivo final de la clase es que publiques tu Proyecto 1 en una URL pública, con código limpio, sin errores y con despliegue automático funcionando.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+12[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del debugging con logs: rastrear errores paso a paso con registros bien colocados y la ayuda de la IA.

El objetivo final de la clase es que corrijas errores reales de tu aplicación —al menos 2 con OpenCode— usando logs para ver exactamente qué ocurre dentro del sistema.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+12[[:space:]]*\.1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la refactorización: mejorar el código sin cambiar su comportamiento, con auditorías guiadas por IA.

El objetivo final de la clase es que audites tu propio código con IA, identifiques olores de código y lo reescribas más limpio, más corto y más mantenible.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+12[[:space:]]*\.2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de las variables de entorno: la configuración sensible y secreta de tu aplicación con archivos .env.

El objetivo final de la clase es que separes configuraciones y claves de tu código, manteniendo los secretos fuera del repositorio como un profesional.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+12[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del despliegue de Laravel en Hostinger, Namecheap o Laravel Cloud, con despliegue automático.

El objetivo final de la clase es que lleves tu aplicación a producción con un despliegue configurado para actualizarse solo en cada cambio, como hacen las startups.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+12[[:space:]]*\.4[[:space:]]*:';

-- ========== CLASE 13 (maestra + subclases 13.1 - 13.4) ==========

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de los fundamentos de las aplicaciones de escritorio multiplataforma con Python y la asistencia de la IA.

El objetivo final de la clase es que inicies tu Proyecto 2: un prototipo de app de escritorio con persistencia local, armado con OpenCode desde la primera línea.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+13[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de cómo inicializar un proyecto de escritorio con Python: estructura base, dependencias y configuración inicial generadas con IA.

El objetivo final de la clase es que tengas la base de tu app de escritorio corriendo, lista para crecer con lógica e interfaz nativa.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+13[[:space:]]*\.1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la interfaz nativa de escritorio: ventanas, paneles y controles de una app de PC generados con IA.

El objetivo final de la clase es que construyas la interfaz de tu app —con al menos 3 paneles funcionales— con estética nativa de escritorio.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+13[[:space:]]*\.2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del manejo de archivos en disco local: guardar y leer datos, como reportes, desde tu app de escritorio.

El objetivo final de la clase es que tu app guarde y recupere información real —como reportes de ventas— en archivos locales del sistema.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+13[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la base de datos SQLite dentro de tu app de escritorio: la persistencia local sin servidor.

El objetivo final de la clase es que implementes la persistencia offline de tu app con SQLite, guardando y consultando datos en la máquina del usuario.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+13[[:space:]]*\.4[[:space:]]*:';

-- ========== CLASE 14 (maestra + subclases 14.1 - 14.4) ==========

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la conexión de tu app de escritorio con el hardware: atajos de teclado, impresoras y empaquetado final.

El objetivo final de la clase es que publiques tu Proyecto 2: una app de escritorio instalable (.exe/.dmg) que interactúa con periféricos reales.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+14[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de los atajos de teclado y la experiencia de usuario avanzada en aplicaciones de escritorio.

El objetivo final de la clase es que tu app responda a atajos profesionales (Ctrl+N, Ctrl+S) y a comandos rápidos, como las aplicaciones de escritorio de verdad.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+14[[:space:]]*\.1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la impresión y la generación de PDF desde tu app de escritorio: tickets y documentos físicos.

El objetivo final de la clase es que tu app imprima tickets y exporte PDFs —como comprobantes de un punto de venta— usando las capacidades nativas del sistema.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+14[[:space:]]*\.2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del empaquetado de aplicaciones de escritorio: cómo convertir tu código Python en un instalador .exe.

El objetivo final de la clase es que generes un instalador ejecutable de tu app, que cualquier usuario pueda instalar sin tener Python instalado.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+14[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la prueba de instalación: validar que tu .exe funcione en una máquina limpia, de punta a punta.

El objetivo final de la clase es que tu aplicación instalada funcione sin errores en otro equipo, completando el ciclo profesional de entrega de software.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+14[[:space:]]*\.4[[:space:]]*:';

-- ========== CLASE 15 (maestra + subclases 15.1 - 15.4) ==========

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del desarrollo móvil multiplataforma con Flutter asistido por IA: la configuración del proyecto y sus primeras pantallas.

El objetivo final de la clase es que inicies tu Proyecto 3: una app móvil que consume tu API real, armada con IA desde la instalación de Flutter hasta el código de pantallas.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+15[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de cómo configurar Flutter: instalación, creación del proyecto y estructura de archivos inicial.

El objetivo final de la clase es que tengas un proyecto Flutter corriendo en tu máquina, listo para generar pantallas con IA.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+15[[:space:]]*\.1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la generación de pantallas móviles con IA: la pantalla Home y la navegación con pestañas (Tabs).

El objetivo final de la clase es que generes la estructura visual de tu app móvil —home y pestañas— con IA, con diseño moderno y navegación fluida.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+15[[:space:]]*\.2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de cómo consumir tu API desde el celular: la conexión entre tu app móvil y tu backend real.

El objetivo final de la clase es que tu app móvil muestre datos reales de tu servidor, cerrando el ciclo cliente-servidor desde un dispositivo físico.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+15[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del acceso a la cámara y la galería desde tu app móvil: capturar y seleccionar imágenes con permisos correctos.

El objetivo final de la clase es que tu app capture fotos y seleccione imágenes de la galería —solicitando permisos como una app profesional— y las envíe a tu API.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+15[[:space:]]*\.4[[:space:]]*:';

-- ========== CLASE 16 (maestra + subclases 16.1 - 16.4) ==========

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de las funciones avanzadas del móvil: geolocalización, notificaciones y la distribución de tu app en las tiendas.

El objetivo final de la clase es que publiques tu Proyecto 3: una app móvil con GPS y notificaciones, compilada en .apk y lista para las tiendas de apps.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+16[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la geolocalización: el GPS y los mapas dentro de tu app móvil con permisos y precisión.

El objetivo final de la clase es que tu app obtenga la ubicación del usuario y la muestre en un mapa, como las apps de delivery o transporte.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+16[[:space:]]*\.1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de las notificaciones locales: avisos programados que tu app muestra sin necesidad de servidor.

El objetivo final de la clase es que tu app envíe notificaciones locales —recordatorios o alertas— en el momento correcto, aunque esté en segundo plano.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+16[[:space:]]*\.2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de cómo compilar tu app móvil en un archivo .apk instalable para Android.

El objetivo final de la clase es que generes el .apk de tu aplicación y lo instales en un teléfono real, viendo tu app funcionar fuera del entorno de desarrollo.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+16[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la publicación en tiendas de apps: los pasos para publicar en Google Play y la App Store.

El objetivo final de la clase es que prepares tu app para las tiendas —cuenta de desarrollador, íconos, capturas y fichas— lista para su distribución comercial.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+16[[:space:]]*\.4[[:space:]]*:';

-- ========== CLASE 17 (maestra + subclases 17.1 - 17.4) ==========

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del salto de programador a solucionador de negocios: cómo empaquetar tu oferta y construir tu identidad comercial.

El objetivo final de la clase es que te posiciones como consultor de software, no como simple coder, y definas tu nicho, tu oferta y tu portafolio con criterio de negocio.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+17[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de cómo elegir tu nicho de mercado: el sector específico de negocios al que vas a servir.

El objetivo final de la clase es que definas tu nicho con datos —un tipo de negocio con dolor claro— en lugar de competir contra miles de desarrolladores genéricos.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+17[[:space:]]*\.1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de cómo construir una oferta irresistible para tus servicios de software: el paquete que el cliente no puede rechazar.

El objetivo final de la clase es que diseñes tu oferta de servicios —con entregables, resultados y precio— que compita por valor y no por precio.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+17[[:space:]]*\.2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la estrategia de precios: cómo cobrar por el valor que entregas y no por las horas que inviertes.

El objetivo final de la clase es que definas tu estructura de precios —proyecto, paquetes y mantenimiento— con márgenes sanos y confianza en tu valor.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+17[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del portafolio vivo: mostrar tu trabajo real en acción para atraer clientes sin vender.

El objetivo final de la clase es que construyas un portafolio que demuestre resultados —con casos reales y métricas— que venda por ti.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+17[[:space:]]*\.4[[:space:]]*:';

-- ========== CLASE 18 (maestra + subclases 18.1 - 18.4) ==========

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la prospección activa outbound: salir a buscar clientes en frío por Google Maps, Instagram y LinkedIn.

El objetivo final de la clase es que lances tu primera campaña de prospección —15 prospectos reales— y llenes tu agenda de negocios sin depender de que te encuentren.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+18[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de cómo encontrar negocios potenciales usando Google Maps: búsqueda por rubro, zona y datos de contacto.

El objetivo final de la clase es que construyas tu lista de prospectos con Google Maps, filtrando negocios que encajen con tu oferta y tu nicho.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+18[[:space:]]*\.1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de los guiones de contacto: mensajes de WhatsApp e Instagram que captan la atención del dueño de negocio.

El objetivo final de la clase es que escribas guiones de contacto efectivos —cortos, específicos y sin vender— que generen respuestas reales.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+18[[:space:]]*\.2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la auditoría flash: un diagnóstico rápido y valioso del negocio del prospecto que demuestra tu criterio.

El objetivo final de la clase es que prepares una auditoría flash de un negocio real —encontrando problemas y oportunidades visibles— que conviertas en puerta de entrada.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+18[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del follow-up: el seguimiento estratégico que convierte los "ahora no" en clientes después.

El objetivo final de la clase es que domines el arte del seguimiento —con mensajes de valor y ritmo correcto— porque la mayoría de las ventas se ganan en el segundo y tercer contacto.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+18[[:space:]]*\.4[[:space:]]*:';

-- ========== CLASE 19 (maestra + subclases 19.1 - 19.4) ==========

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la reunión de ventas: la llamada de diagnóstico, la propuesta económica y el cierre del acuerdo.

El objetivo final de la clase es que conduzcas una venta completa de servicios de software —del diagnóstico al cierre— con seguridad y manejo profesional de objeciones.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+19[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la llamada de diagnóstico: la entrevista que revela el dolor, el presupuesto y la urgencia del cliente.

El objetivo final de la clase es que hagas preguntas de diagnóstico efectivas —menos tú, más el cliente— y salgas de la llamada con un plan claro de propuesta.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+19[[:space:]]*\.1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la propuesta económica: cómo presentar tu oferta con precio, alcance y entregables de forma profesional.

El objetivo final de la clase es que armes propuestas claras y atractivas —con fases, plazos y condiciones— que inviten al sí.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+19[[:space:]]*\.2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del manejo de la objeción de precio: la objeción más común y cómo responder sin descontar de más.

El objetivo final de la clase es que respondas al "es muy caro" con técnicas probadas —valor, comparación y alternativas— sin regalar tu trabajo.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+19[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de los contratos de software: los acuerdos que protegen al cliente y a ti en todo proyecto.

El objetivo final de la clase es que entiendas las cláusulas esenciales de un contrato de desarrollo —alcance, pagos, propiedad del código— y cierres con seguridad legal.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+19[[:space:]]*\.4[[:space:]]*:';

-- ========== CLASE 20 (maestra + subclases 20.1 - 20.4) ==========

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la gestión de proyectos y de las ventas recurrentes: mantenimiento, hosting e ingresos que se repiten.

El objetivo final de la clase es que conviertas tus proyectos en clientes de largo plazo, con ingresos mensuales recurrentes que hagan crecer tu negocio sin empezar de cero cada mes.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+20[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del onboarding del cliente: los primeros días de un proyecto que definen la relación completa.

El objetivo final de la clase es que des una primera experiencia impecable —comunicación clara, entregas tempranas y expectativas alineadas— que fidelice desde el día uno.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+20[[:space:]]*\.1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la gestión de cambios: qué hacer cuando el cliente pide funciones que no estaban en el alcance.

El objetivo final de la clase es que manejes los cambios de alcance con reglas claras —cotización de extras y control del proyecto— sin conflictos y sin perder dinero.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+20[[:space:]]*\.2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de los planes de mantenimiento: la oferta de soporte mensual que te genera ingresos recurrentes.

El objetivo final de la clase es que diseñes tu plan de mantenimiento —soporte, actualizaciones y hosting— y lo ofrezcas como parte natural de cada proyecto.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+20[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la venta cruzada: detectar nuevas necesidades del cliente que ya confía en ti.

El objetivo final de la clase es que identifiques oportunidades de ampliar proyectos —nuevas funcionalidades, apps o integraciones— y las propongas cuando el momento sea correcto.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+20[[:space:]]*\.4[[:space:]]*:';

-- ========== CLASE 21 (maestra + subclases 21.1 - 21.4) ==========

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del taller de vanguardia tecnológica: la herramienta de IA o framework más nuevo y cómo dominarlo antes que los demás.

El objetivo final de la clase es que te mantengas a la vanguardia del mercado —desplegando una plantilla comercial con herramientas nuevas— y conviertas la novedad en ventaja competitiva.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+21[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la nueva herramienta del mes: investigar, probar y evaluar el último framework o motor de IA del mercado.

El objetivo final de la clase es que desarrolles el hábito de aprender herramientas nuevas rápido, sabiendo cuáles valen la pena y cuáles son humo.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+21[[:space:]]*\.1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del Prompt Deck: tu biblioteca personal de prompts probados y plantillas reutilizables.

El objetivo final de la clase es que construyas tu colección de prompts efectivos y boilerplates que te hagan más rápido en cada proyecto.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+21[[:space:]]*\.2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la personalización del Vibe Coding: ajustar tu flujo de trabajo con IA a tu estilo y tus proyectos.

El objetivo final de la clase es que configures tu entorno de IA —agentes, reglas y herramientas— a tu medida, como un artesano de su propio taller.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+21[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la optimización de costos de API: usar la IA de forma inteligente sin gastar de más.

El objetivo final de la clase es que apliques estrategias de ahorro —modelos baratos, caché y prompts eficientes— para que tu negocio con IA sea rentable.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+21[[:space:]]*\.4[[:space:]]*:';

-- ========== CLASE 22 (maestra + subclases 22.1 - 22.4) ==========

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la clínica de proyectos reales: casos de estudio y bugs en vivo de clientes reales, resueltos en el momento.

El objetivo final de la clase es que recibas soporte senior sobre tu propio proyecto y aprendas a resolver problemas reales de producción, no ejemplos de manual.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+22[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de casos de estudio: analizar proyectos reales de estudiantes para aprender de sus aciertos y errores.

El objetivo final de la clase es que aprendas de experiencias reales —cómo otros resolvieron problemas concretos— y apliques esas lecciones a tu proyecto.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+22[[:space:]]*\.1[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la refactorización en vivo: reescribir y mejorar código de proyectos reales ante tus ojos.

El objetivo final de la clase es que veas el proceso de mejora de código en acción y lo repliques en tu propio proyecto con criterio profesional.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+22[[:space:]]*\.2[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de la negociación avanzada: técnicas de nivel superior para cerrar acuerdos con clientes exigentes.

El objetivo final de la clase es que eleves tus habilidades de negociación —más valor, mejores condiciones y clientes más grandes— para crecer como consultor.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+22[[:space:]]*\.3[[:space:]]*:';

UPDATE lecciones
SET descripcion = 'En esta clase se hablará del networking: construir una red de contactos que te genere oportunidades sin buscarlas.

El objetivo final de la clase es que crees tu estrategia de networking —comunidad, referidos y presencia— que multiplique tus oportunidades de negocio.'
WHERE curso_id = 1 AND titulo REGEXP '^Clase[[:space:]]+22[[:space:]]*\.4[[:space:]]*:';