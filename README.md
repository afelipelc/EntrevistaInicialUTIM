Entrevista Inicial
========================


Automatización de la Entrevista Inicial del Programa Institucional de Tutorías de la [Universidad Tecnológica de Izúcar de Matamoros](http://www.utim.edu.mx/).

Una adecuación de Moodle 2.6 + el plugin Questionnaire para solventar una necesidad y contribuir al Sistema de Gestión de la Calidad en este proceso académico dentro de la institución.

Un aporte de:
[@mrjona86](https://twitter.com/mrjona86) y [@afelipelc](https://twitter.com/afelipelc)



*** Actualización 13.05.2015 *** 
* Ya no se tienen que modificar prefijos en las consultas, por default se toma de la configuración de Moodle.
* La configuración de los datos de conexión a la BD ya no son necesarios, también se toma de la configuración de Moodle.
* Se agregó la función: respuesta($usuario, $pregunta, $tipo) { ... } donde $tipo: text, boolean, multiple, y list
* Se incluyó otro ejemplo de formato a generar - estudios.php junto con impresion-est.css - utilizando el acceso a las respuestas.