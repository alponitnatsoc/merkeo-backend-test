# merqueo-backend-test
back end test for merqueo.com

Instrucciones para ejecutar
------------------------------
Asegurese de crear y editar editar las credenciales de mysql en el archivo .env creando una copia a partir del archivo .env.dist que existe en este repositorio.

asegurese de que el usuario y contraseña en la url de la base de datos seab correctos y despues dele el nombre que desea a la base de datos para el proyecto

  ```
  DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
  ```

Inicialmente para correr el servidor, dirijase al directorio raiz del projecto desde la terminal de comandos y ejecute:

```php bin/console server:run```

luego asegurese de iniciar el servicio de mysql y llamar el comando para crear la base de datos e inicializar el esquema

  ```
  mysql.server start
  php bin/console doctrine:database:create
  php bin/console doctrine:schema:update --force
  ```

La url del servidor por defecto es 127.0.0.1:8000 y el path para la funcionalidad de la prueba es product/show

http://127.0.0.1:8000/product/show

Instrucciones para la base de datos
-----------------------------------
La estructura utiliza herencia para los grupos de productos por lo tanto la tabla Product tiene una columna para el tipo de producto que se genera automaticamante con las anotaciones ORM si se crea desde symfony, pero para hacerlo manualmente toca utilizar 'product' o 'bunlde_product' 

la tabla bundle_product solo salvara los IDs de los productos que son bundles de la tabla product como manera de busqueda rapida de los bundles y finalmente la relacion se ve en la tabla intermedia product_bundle_product en la que se lista si un product_id pertenece a un bundle_product_id

a manera de prueba puede entrar a la url http://127.0.0.1:8000/product/test para generar un esquema de ejemplo en la base de datos
