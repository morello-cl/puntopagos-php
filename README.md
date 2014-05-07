Puntopagos PHP SDK
==================

Antes de iniciar cualquier integración con Puntopagos, favor leer **Manual Técnico de PuntoPagos**.

En la plataforma de PuntoPagos existe una cuenta Cliente sandbox para poder ejecturar de forma inmediata el ejemplo (test.htm).

* Tomese en cuenta que el comportamiento de la plataforma de Puntopagos para servidores seguros (SSL) es distinta. El ejemplo funciona solo sin SSL, pero el SDK permite los dos tipos de comunicación.

Contactar a soporte@puntopagos.com para personalizar las opciones.

Llaves para desarrollo
----------------------
* [URL sandbox](https://sandbox.puntopagos.com)

Las llaves generadas como comercio "*Desarrollo PHP*" en ambiente sandbox
- KEY kLaQleQUIuwfeXXkxyTC
- Secret o0PQThCnmmGoWD8KxLARy2MNb577k2IpqFWTMxVC

URL PreConfiguradas en plataforma Puntopagos
--------------------------------------------
La plataforma de Puntopagos, requiere 3 URL's, la cuales quedan cargadas en un inicio, y solo pueden ser cambiadas
- URL Exito: (http://localhost/puntopagos/exito.php)
- URL Fracaso: (http://localhost/puntopagos/fracaso.php)
- URL Notificacion: (http://localhost/puntopagos/notificacion.php)

Es necesiario que configures correctamente tu servidor web en la maquina que vas a desarrollar la aplicación.

Licence
-------
[MIT Licence](http://maoo.mit-license.org/)
