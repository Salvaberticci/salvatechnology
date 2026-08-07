-- ============================================================
-- Migración 027: Clase 1.4 según guion oficial de voz —
-- descripción uniforme del Módulo 1 + actividad "Matriz de
-- Selección Arquitectónica" con ejemplo resuelto (POS).
-- Idempotente: UPDATE por título (robusto ante distintos ids).
-- Fecha: 2026-08-07
-- ============================================================

UPDATE lecciones
SET descripcion = 'En esta clase se hablará de los tres grandes entornos de ejecución del software y sus diferencias técnicas: la Arquitectura Web (navegador, sandbox, cero fricción de instalación, actualización centralizada, dependencia de conectividad), la Arquitectura Desktop (acceso directo al hardware nativo, periféricos I/O como impresoras y lectores de código de barras, operatividad offline ininterrumpida con SQLite y sincronización en la nube) y la Arquitectura Mobile (sensores físicos como GPS, cámara y biometría, notificaciones push, gestión estricta de batería y el ecosistema App Store/Google Play con comisiones del 15% al 30%). También de la Matriz de Selección para decidir qué plataforma usar según las necesidades del negocio.

El objetivo final de la clase es que aprendas a decidir la arquitectura correcta evaluando hardware y periféricos, requerimientos de conectividad y facilidad de distribución —en lugar de elegir por moda— y que asumas el rol de Consultor Principal para resolver casos reales como un sistema POS de supermercado, una plataforma de educación en línea, una app de conductores o el software de una ferretería industrial.'
WHERE curso_id = 1
  AND titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*\.4[[:space:]]*:';

UPDATE actividades a
JOIN lecciones l ON l.id = a.leccion_id
SET a.titulo = 'Act: Matriz de Selección Arquitectónica',
    a.descripcion = 'Asume el rol de Consultor Principal de Software. Se te presentarán 3 problemas de negocio reales. Tu tarea es analizar cada caso, seleccionar la arquitectura principal más adecuada (Web, Desktop o Mobile) y justificar tu decisión evaluando:

1. Necesidades de hardware y periféricos.
2. Requerimientos de conectividad (Online vs Offline).
3. Facilidad de distribución e interacción con el usuario.

EJEMPLO RESUELTO (Caso de Referencia: Sistema Punto de Venta para una Cadena de Supermercados con 10 Cajas por Sucursal):
- Arquitectura Seleccionada: DESKTOP (con sincronización Backend en la Nube).
- Hardware: conexión física USB/Serial a la gaveta de dinero, balanza digital de peso y lector láser de código de barras.
- Conectividad: si el proveedor de Internet falla, el supermercado no puede detener las ventas; la app Desktop guarda las facturas en SQLite y sincroniza cuando regresa la red.
- Rendimiento: la impresión del ticket térmico y la respuesta del lector deben ser en milisegundos, sin latencia de red HTTP.

CASOS A RESOLVER:

Caso A: Una plataforma de educación en línea donde estudiantes toman cursos, ven videos en alta definición, hacen exámenes y gestionan sus certificados.

Caso B: Una aplicación para conductores de transporte privado y repartidores de comida rápida que reciben viajes en tiempo real mientras conducen por la ciudad.

Caso C: Un software de control de inventario y facturación para una ferretería industrial que utiliza lectoras de código de barras y debe seguir facturando aunque falle el servicio de Internet.

Documenta tu propuesta justificando cada elección técnica y súbela a la plataforma de SalvaTechnology Academy.'
WHERE l.titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*\.4[[:space:]]*:';