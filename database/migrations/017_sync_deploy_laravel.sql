-- 017_sync_deploy_laravel.sql
-- Alinea el deploy de la Web (Curso 1) con el stack Laravel/PHP:
--   antes: Vercel/Netlify (SPA hosting de frontend)
--   ahora: Laravel Cloud / Namecheap / Hostinger (hosteo PHP/Laravel)
-- Segun fuentes MD002/local, la web stack es PHP + Laravel.

-- Lección 12.4 (título de sublección)
UPDATE lecciones SET titulo = 'Clase 12.4: Despliegue Laravel en Hostinger/Namecheap/Laravel Cloud'
WHERE id = 1173;

-- Actividad: Deploy Automatizado (acto de la 12.4)
UPDATE actividades SET descripcion = 'Conecta tu repo con Laravel Cloud, Namecheap o Hostinger para deploy automático de la app Laravel (PHP).'
WHERE id = 1160;
