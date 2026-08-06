-- Sync info del curso y descripciones de lecciones con .guiones/002-curso-1-contenido-completo.md
-- 1) Información general del curso (hero de curso_landing): descripción y duración real vs el guion (110 lecciones, 22 semanas, metodología ADD).
UPDATE cursos SET
  titulo = 'Desarrollo de Software con Inteligencia Artificial',
  descripcion = 'Curso completo de 110 lecciones para llevar de los fundamentos a la construcción de software real potenciado por IA. Arquitectura de software, lógica de programación, bases de datos, APIs, desarrollo web (PHP/Laravel + JS), apps de escritorio (Python) y móviles (Flutter), proyectos reales y negociación comercial como desarrollador. Bajo la metodología ADD (AI-Driven Developer) con OpenCode como herramienta de Vibe Coding.',
  duracion = '6+ meses · 22 semanas',
  categoria = 'Programación'
WHERE id = 1;

-- 2) Descripciones por lección: se aplican los textos oficiales del guion donde existan.
UPDATE lecciones SET descripcion = 'Arquitectura de software end-to-end; jerarquía Dato → Información → Sistema; Modelo Cliente-Servidor; RAM vs Persistencia; las 3 plataformas (Web/Desktop/Mobile); mindset de desarrollador asistido por IA.' WHERE id = 1114;
UPDATE lecciones SET descripcion = 'Diferencia entre "picar código" y hacer ingeniería; los 3 pilares: Dato (unidad mínima aislada), Información (dato con contexto), Sistema (automatización con reglas de negocio).' WHERE id = 1115;
UPDATE lecciones SET descripcion = 'Cómo se divide el software en cliente, servidor y base de datos, y cómo viajan las peticiones/respuestas.' WHERE id = 1116;
UPDATE lecciones SET descripcion = 'Qué hace cada capa, dónde vive cada dato y cómo se comunican.' WHERE id = 1117;
UPDATE lecciones SET descripcion = 'Diferencias de recursos, ventana y contexto de uso entre las 3 plataformas.' WHERE id = 1118;
UPDATE lecciones SET descripcion = 'Qué es un algoritmo, estructuras de control y cómo escribir lógica en pseudocódigo antes de codificar.' WHERE id = 1119;
UPDATE lecciones SET descripcion = 'Representar la lógica paso a paso con flujogramas.' WHERE id = 1120;
UPDATE lecciones SET descripcion = 'Almacenar y manipular datos con tipos primitivos y operadores.' WHERE id = 1121;
UPDATE lecciones SET descripcion = 'Decisiones lógicas con if/else dentro de reglas de negocio.' WHERE id = 1122;
UPDATE lecciones SET descripcion = 'Repetición de procesos y recorrido de listas de datos.' WHERE id = 1123;
UPDATE lecciones SET descripcion = 'Cómo diseñar esquemas de bases de datos relacionales sin redundancias y bien conectados.' WHERE id = 1124;
UPDATE lecciones SET descripcion = 'Qué es una entidad, sus atributos y la clave primaria (PK).' WHERE id = 1125;
UPDATE lecciones SET descripcion = 'Cómo las claves foráneas (FK) conectan tablas entre sí.' WHERE id = 1126;
UPDATE lecciones SET descripcion = 'Relaciones muchos a muchos con tablas puente.' WHERE id = 1127;
UPDATE lecciones SET descripcion = 'Aplicar formas normales para eliminar redundancias y anomalías.' WHERE id = 1128;
UPDATE lecciones SET descripcion = 'Cómo viajan los datos por la web, qué es JSON y cómo funcionan las APIs REST.' WHERE id = 1129;
UPDATE lecciones SET descripcion = 'Verbos GET/POST/PUT/DELETE y sus usos.' WHERE id = 1130;
UPDATE lecciones SET descripcion = 'Formato de intercambio de datos.' WHERE id = 1131;
UPDATE lecciones SET descripcion = '200, 201, 400, 401, 404, 500 y sus significados.' WHERE id = 1132;
UPDATE lecciones SET descripcion = 'Consumir APIs públicas reales.' WHERE id = 1133;
UPDATE lecciones SET descripcion = 'La vida interna de una página: DOM, renderizado y protocolos.' WHERE id = 1134;
UPDATE lecciones SET descripcion = 'Estructura del documento y manipulación en vivo.' WHERE id = 1135;
UPDATE lecciones SET descripcion = 'Maquetación moderna de interfaces.' WHERE id = 1136;
UPDATE lecciones SET descripcion = 'Renderizado en cliente vs servidor.' WHERE id = 1137;
UPDATE lecciones SET descripcion = 'localStorage, sessionStorage, cookies/IndexedDB.' WHERE id = 1138;
UPDATE lecciones SET descripcion = 'Recursos del sistema: archivos, procesos, hardware y bases de datos locales.' WHERE id = 1139;
UPDATE lecciones SET descripcion = 'Lectura/escritura en disco.' WHERE id = 1140;
UPDATE lecciones SET descripcion = 'Concurrencia y administración de tareas.' WHERE id = 1141;
UPDATE lecciones SET descripcion = 'Integración con dispositivos físicos.' WHERE id = 1142;
UPDATE lecciones SET descripcion = 'SQLite y persistencia local.' WHERE id = 1143;
UPDATE lecciones SET descripcion = 'Cómo funcionan las apps móviles en cada Sistema Operativo.' WHERE id = 1144;
UPDATE lecciones SET descripcion = 'Estados de la app en primer y segundo plano.' WHERE id = 1145;
UPDATE lecciones SET descripcion = 'Cámara, GPS, giroscopio y permisos.' WHERE id = 1146;
UPDATE lecciones SET descripcion = 'Envío de notificaciones con payload JSON.' WHERE id = 1147;
UPDATE lecciones SET descripcion = 'Interfaces que responden a cualquier pantalla.' WHERE id = 1148;
UPDATE lecciones SET descripcion = 'Ciclo de vida de software y trabajo colaborativo con versiones.' WHERE id = 1149;
UPDATE lecciones SET descripcion = 'Crear commits y trabajar con ramas.' WHERE id = 1150;
UPDATE lecciones SET descripcion = 'Integrar ramas y resolver conflictos.' WHERE id = 1151;
UPDATE lecciones SET descripcion = 'Revisión de código colaborativa.' WHERE id = 1152;
UPDATE lecciones SET descripcion = 'Integración y despliegue continuo.' WHERE id = 1153;
UPDATE lecciones SET descripcion = 'Entorno de trabajo con IA y generación de código.' WHERE id = 1154;
UPDATE lecciones SET descripcion = 'Construir backend con ayuda de IA.' WHERE id = 1159;
UPDATE lecciones SET descripcion = 'Integrar la vista con el servidor.' WHERE id = 1164;
UPDATE lecciones SET descripcion = 'Depurar, refactorizar y publicar.' WHERE id = 1169;
UPDATE lecciones SET descripcion = 'Fundamentos de apps de escritorio multiplataforma.' WHERE id = 1174;
UPDATE lecciones SET descripcion = 'Cómo posicionarte como consultor, no como simple coder.' WHERE id = 1194;
