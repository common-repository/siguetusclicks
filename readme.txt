=== SigueTusClicks ===
Contributors: wokomedia, gwannon 
Tags: seo, mapa calor, analizar clicks, medir clicks web, web seo, visual seo, mapa de calor clicks, wokocalor, puntos click web
Requires at least: 3.9.0
Tested up to: 3.9.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Este plugin permite generar mapas de calor de clicks de tu página en Wordpress.

== Description ==

Este plugin permite generar de forma sencilla mapas de calor de clicks de tu página en Wordpress. Con este plugin podrás saber que zonas de tu página obtienen mayor número de clicks. No es necesario registro en página de terceros. Este plugin es por cortesía de [Wokomedia](http://wokomedia.com/ "Marketing online y diseño web en Bilbao")

== Installation ==

Para instalar:

1. Instala "SigueTusClicks" automáticamente, subiendo el archivo ZIP o descomprimiendo el fifichero zip del plugin y subiendolo a /wp-content/plugins/.
2. Activa el plugin a través del Menú "Plugins" en Wordpress.
3. Configura el plugin entrando en el menú del plugin.

== Frequently Asked Questions ==

= ¿Qué es un mapa de calor de clicks? =

Un mapa de calor o heatmap es una forma gráfica de interpretar e identificar las zonas de una página web que más atención despiertan entre los usuarios que la visitan.

En el caso del mapa de calor elaborado por wokomedia “SigueTusClicks” se basa en un código concreto de colores sobre unos criterios establecidos, para que se tomen como referencia los principales puntos de la web en los que hace click el usuario.

Se trata de una herramienta muy útil para conocer si estamos perdiendo oportunidades de negocio o si tenemos que mejorar ciertas parte del diseño de nuestra web o de la arquitectura de navegación.

= ¿Qué información puedo sacar de un mapa de calor de clicks? =

Estos mapas lo que te ofrecen es una visión de las zonas más interesantes de tus páginas, entendiendo como zonas interesantes  los sitios donde más clicks hacen tus visitantes.

Por ejemplo, nos puede decir qué banners y reclamos tienen más efectividad o qué elemento de un menú tienen más tráfico.

= ¿Cómo interpreto los datos? =

La interpretación de los datos es muy sencilla. Cada click genera un círculo rojo semitransparente. Según se acumulan puntos en una zona el color rojo se hace más intenso.

De esta forma, podemos ver sobre nuestra propia web qué zonas tienen más clicks y cuáles no. De todas formas, desde wokomedia estaremos encantados de ayudarte a interpretar tus mapas de calor. Para ello ponte en contacto con nosotros a través de este formulario.

= ¿Cuántos clicks mínimos deben recogerse para hacer una interpretación? =

Consideramos que debe haber unos 300 clicks por página como mínimo para que se pueda considerar una muestra representativa de la navegación que hacen los usuarios en tu página web.

= ¿Dónde se guardan los datos? =

Debido a la gran cantidad de datos que pueden llegar a generarse, los datos no se guardan en la base de datos, sino que se guardan en unos ficheros de texto en el directorio “/wp-content/uploads/siguetusclicks/”. Si se borra este directorio desapareceran todos los datos de clicks almacenados sobre tu web.

= ¿Qué clicks se registran? =

Se puede configurar mediante la opción “Registrar clicks” de la página de configuración del plugin que se registren todos los clicks, que se registren solo los que hagan los visitantes que no esten logeados con su usuario dentro de wordpress o que solo se registren los clicks de los usuarios logeados. Estas opciones son así para que si nos interesa los clicks de administradores, editores, … no falseen los datos de los usuarios normales con sus clicks.

= ¿Se registran clicks en versiones moviles? =

Por ahora no, por ello es importante establecer el tamaño mínimo. No se registran clicks de pantallas más pequeñas del tamaño mínimo establecido.

= ¿Es completamente gratuito? =

SigueTusClicks, a diferencia de otras opciones para hacer mapas de calor, es completamente gratuito y no exige ni registros, ni sumistrar datos de ningún tipo a terceros.

= ¿Ralentizará mi web? =

Hemos tratado de hacer un plugin que consuma el menor número posible de recursos, pero en webs con miles de visitas diarias es recomendable borrar periódicamente los ficheros de visitas (borrandolos del directorio /wp-content/uploads/siguetusclicks/ o a través del administrador del plugin) porque a medida que esos ficheros crecen de tamaño pueden hacer que el rendimiento del servidor sea peor.

Aun así, tampoco recomendamos su uso continuado, sino que aconsejamos activarlo para ocasiones especiales y dejarlo desactivado el resto del tiempo. Por ejemplo, si hacemos una campaña nueva de Google Adwords lo activaremos para estudiar su efecto, pero volveremos a desactivarlo una vez se acabe la campaña de Adwords.

= ¿Usa cookies? =

No usa cookies debido a que no necesitamos guardar más información de los visitantes que dónde ha hecho click. No necesitamos saber si es un usuario recurrente o si ha estado hace menos de media hora en la web, y por lo tanto no generamos ningún tipo de cookie que guarde esa información.

He cambiado el contenido de una página y ahora no coinciden los zonas de calor con los contenidos.

Cuando cambiamos el contenido de una página cambia su estructura y la posición de sus elementos con lo que los clicks registrados hasta ese momento dejan de tener valor real.

Recomendamos siempre que se hagan cambios en el contenido de una página el borrar los datos de esa paǵina para tener datos correctos y basados en el contenido actual.

= ¿Cómo averiguo el ancho de mi pagina web? =

La forma más sencilla es que hagas una captura de pantalla con el botón “Imp pant” (Imprimir pantalla) y abrás un programa de dibujo tipo Paint y lo peges en una nueva imagen. Estos programas de dibujo tiene herramientas para medir en pixeles las zonas que elijas.

== Screenshots ==

1. Ejemplo de mapa de calor en modo IMAGEN
2. Ejemplo de mapa de calor en modo HTML

== Changelog ==

= 1.2.1 =
* Soporte para tipos de posts personalizados
* Corrección de bug en registro de clicks de categorías y etiquetas

= 1.2 =
* Opción de ver todos los clicks en una sola vista

= 1.1.1 =
* Errores menores solucionados
* Posibilidad de elegir de que tipos de usuariosregistramos los clicks

= 1.1 =
* Modo imagen añadido, necesita librerias GD

= 1.0 =
* Versión inicial
