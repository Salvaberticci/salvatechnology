-- ============================================================
-- Migración 013: Distribución correcta de las Quests de la
-- Semana 1 del Curso 1.
--   Clase 1    (771) -> Quest original "Autopsia de un Software Comercial"
--   Clase 1.1  (772) -> Quest "Análisis Anatómico de Dato, Información y Sistema"
--   Clase 1.2  (773) -> Quest "Autopsia Anatómica de una Aplicación Comercial"
-- Fecha: 2026-08-02
-- ============================================================

-- Clase 1: restaura la Quest original
UPDATE actividades
SET titulo = '🎯 Quest #01: "Autopsia de un Software Comercial"',
    descripcion = 'Instrucciones:

Elige una aplicación comercial que utilices todos los días (ejemplos: Spotify, Uber, MercadoLibre, WhatsApp, PedidosYa). En un documento o PDF de una página, realiza el siguiente desmontaje técnico:

Definición de Capas:
- Identifica 2 funciones que ocurran estrictamente en el Frontend (interfaz/captura).
- Identifica 2 validaciones que obligatoriamente deben procesarse en el Backend por seguridad.
- Lista 3 tipos de datos que la app guarde de forma permanente en la Base de Datos.

Dato vs. Información:
- Da un ejemplo de un Dato suelto de esa app y cómo el sistema lo transforma en Información útil para el usuario.

Ecosistema Multiplataforma:
- Si la app tiene versión Web, Desktop o Mobile, explica qué ventaja técnica ofrece usarla en esa plataforma específica (ej. "Uso la versión mobile de Uber porque necesita acceso al GPS en tiempo real").'
WHERE id = 771;

-- Clase 1.1
UPDATE actividades
SET titulo = 'Quest 1.1: Análisis Anatómico de Dato, Información y Sistema',
    descripcion = 'Análisis Anatómico de Dato, Información y Sistema.

Tu misión para esta lección consiste en:

1. Seleccionar un dominio de negocio de la vida real (por ejemplo: un sistema de reservas de vuelos, una plataforma de delivery de comida o una red social).

2. Identificar y documentar 3 Datos crudos que circulen en esa plataforma.

3. Explicar cómo el software envuelve esos 3 datos en un marco de contexto para transformarlos en Información valiosa.

4. Describir la Regla de Negocio y la Acción Automatizada que ejecuta el Sistema al procesar esa información.'
WHERE id = 772;

-- Clase 1.2
UPDATE actividades
SET titulo = 'Quest 1.2: Autopsia Anatómica de una Aplicación Comercial',
    descripcion = 'Autopsia Anatómica de una Aplicación Comercial.

Tu misión técnica consiste en redactar un informe de autopsia que responda a tres preguntas clave:

1. Análisis de Frontend: Identifica y describe 3 Eventos de Usuario específicos que la aplicación capture en su interfaz visual.

2. Análisis de Backend: Especifica 2 Reglas de Negocio estrictas que la aplicación deba validar en el servidor para evitar que los usuarios hagan trampa o cometan fraude.

3. Análisis de Persistencia: Enumera 4 Datos críticos que la aplicación deba guardar obligatoriamente en su Base de Datos permanente para funcionar de un día para otro.'
WHERE id = 773;