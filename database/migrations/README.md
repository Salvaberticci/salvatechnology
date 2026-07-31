# Migraciones SQL

Esta carpeta contiene las migraciones de base de datos de **SalvaTechnology Academy**.

## Convención de nombres

```
{numero}_{descripcion_corta}.sql
```

Ejemplos:
- `001_create_base_tables.sql`
- `002_add_columna_nueva.sql`
- `003_alter_table_usuarios.sql`

Los archivos se ejecutan **en orden numérico**, de menor a mayor.

## Cómo ejecutar las migraciones

Desde cualquier navegador, visita en el servidor:

```
https://tudominio.com/migrate.php
```

Esto ejecutará **todas** las migraciones pendientes (las que aún no se han aplicado).
Las migraciones ya aplicadas se registran en la tabla `migrations_log` y no se re-ejecutan.

## Protección con token (recomendado en producción)

Para evitar que cualquiera ejecute migraciones, define un token en `config/app.php`:

```php
define('MIGRATION_TOKEN', 'tu_token_secreto');
```

Y ejecuta:

```
https://tudominio.com/migrate.php?token=tu_token_secreto
```

Si no defines `MIGRATION_TOKEN`, la URL estará abierta sin protección (solo recomendado en desarrollo local).

## Aplicar una migración específica

Para ejecutar solo un archivo concreto:

```
https://tudominio.com/migrate.php?file=002_add_columna.sql
```

## Escribir migraciones seguras

- Envuelve cada migración en `BEGIN; ... COMMIT;` cuando sea posible (solo DML).
- Para DDL (CREATE/ALTER), ten en cuenta que MySQL no soporta transacciones atómicas; haz los cambios idempotentes cuando sea posible (`IF NOT EXISTS`, `IF EXISTS`).
- Asegúrate de que el cambio sea **no destructivo** (no borres columnas/tablas sin confirmación).
- Una migración que falla se detiene y el error se muestra en pantalla; los archivos anteriores ya aplicados quedan registrados.
