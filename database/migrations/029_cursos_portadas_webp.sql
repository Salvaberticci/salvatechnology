-- Portadas de cursos en formato webp (más liviano y optimizado)
-- Los archivos .png originales fueron convertidos a .webp en img/.
UPDATE cursos SET imagen = REPLACE(imagen, '.png', '.webp') WHERE imagen LIKE '%.png';
