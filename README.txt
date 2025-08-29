Aplicación PHP + MySQL para gestión documental (Comercializadora COILE)
--------------------------------------------------
Archivos incluidos:
 - config.php      : configurar conexión MySQL (host, usuario, contraseña y BD)
 - db.sql          : script SQL para crear la base de datos y tablas e insertar datos iniciales
 - index.php       : interfaz principal (formularios, reportes)
 - actions.php     : acciones para guardar, eliminar e imprimir actas
 - assets/coile.webp : logo (si fue subido)

Instrucciones rápidas:
1) Copia los archivos al directorio de tu servidor web (ej. /var/www/html/coile/).
2) Edita config.php y pon las credenciales correctas.
3) Importa db.sql en MySQL: mysql -u root -p < db.sql
4) Asegúrate que el servidor web tenga permisos para leer assets/.
5) Abre http://tu-servidor/coile/index.php
