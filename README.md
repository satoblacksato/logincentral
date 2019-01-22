# Ernesto Liberio

[![N|Solid](https://cdn.icon-icons.com/icons2/1254/PNG/128/1495494673-jd04_84463.png)](https://www.facebook.com/blacksato)

Componente que permite gestionar el login de una aplicación central

# Instalación

  - composer require satoblacksato/logincentral "v1.0.1"
  - php artisan vendor:publish 
            se escoge el tag 14 
  - **LoginCentralMiddleware::class** se debe agregar en $middlewareGroups del archivo kernel de app/http/Kernel.php en el array web con su respectivo use Eliberio\LoginCentral\LoginCentralMiddleware; en la parte superior de la clase
