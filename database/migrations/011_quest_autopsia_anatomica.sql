-- ============================================================
-- Migración 011: Nueva consigna de la Quest #01 del Curso 1
-- "Autopsia Anatómica de una Aplicación Comercial"
-- (Frontend: 3 eventos / Backend: 2 reglas / Persistencia: 4 datos)
-- Fecha: 2026-08-02
-- ============================================================

UPDATE actividades
SET titulo = '🎯 Quest #01: "Autopsia Anatómica de una Aplicación Comercial"',
    descripcion = 'Autopsia Anatómica de una Aplicación Comercial.

Tu misión técnica consiste en redactar un informe de autopsia que responda a tres preguntas clave. Elige una aplicación comercial que uses a diario (Spotify, Uber, MercadoLibre, WhatsApp, PedidosYa).

1. Análisis de Frontend: Identifica y describe 3 Eventos de Usuario específicos que la aplicación capture en su interfaz visual.

2. Análisis de Backend: Especifica 2 Reglas de Negocio estrictas que el servidor deba validar para evitar que los usuarios hagan trampa o cometan fraude.

3. Análisis de Persistencia: Enumera 4 Datos críticos que el sistema deba guardar obligatoriamente en su Base de Datos permanente para funcionar de un día para otro.

Entrega un documento o PDF de una página con tu informe.'
WHERE id = 771;