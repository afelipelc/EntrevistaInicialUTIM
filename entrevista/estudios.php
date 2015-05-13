<?php
//ini_set('display_errors', '1');
header('Content-Type: text/html; charset="utf-8"', true);
/**
* Script que genera el FDCI en formato HTML para su impresión
*
* Elaborado por Mtro. Yonatan Cruz e Ing. Felipe Lima
* 
*/
include_once("myDBC.php");
// Recibimos el usuario de la encuesta a través del formulario en un hidden.
$usuario = "";
  if( isset($_POST['seleccionaUsuario']))
  {
    $usuario = $_POST['seleccionaUsuario'];
  }elseif (isset($_GET["rid"], $_GET["action"]) && $_GET["action"] == "list") {
    $usuario = $_GET["rid"];
  }
if($usuario==""){
  echo'<script type="text/javascript">
      alert("Ningún registro");
      //window.location="http://sav.utim.edu.mx";
    </script>';
}
else{
  $dia=date("d");
  $mes=date("F");
  $anio=date("Y");
  $hora=date("h:i a"); 
  # Arreglo para imprimir el mes en espa?l.
  $a=array();
  $a[1] = "Enero"; 
  $a[2] = "Febrero"; 
  $a[3] = "Marzo"; 
  $a[4] = "Abril"; 
  $a[5] = "Mayo"; 
  $a[6] = "Junio"; 
  $a[7] = "Julio"; 
  $a[8] = "Agosto";
  $a[9] = "Septiembre";
  $a[10] = "Octubre";
  $a[11] = "Noviembre";
  $a[12] = "Diciembre";
  $fecha=$dia." de ".$a[date("n")]." de ".$anio." ".$hora;
?>
<html lang="es">
<head>
  <title>Permiso de Viaje de Estudios</title>
  <link rel ="stylesheet" type ="text/css" href = "impresion-est.css" />
  <script type="text/javascript">
<!--
//habilitar para imprimir al cargar
window.print(false);
-->
</script>
</head>
<body>
  <div id="cabecera">
    <img src="utim.png" />
    <div id="pageheader">
      <br>      
      <br>
      <h4>PERMISO DE VIAJE DE ESTUDIOS</h4>
      <br>
      <span class="fecha">Izúcar de Matamoros, Puebla. a <?php echo $fecha; ?></span>
    </div>
  </div>

<?php
  // Creamos objeto de la clase myDBC.
  // para hacer uso del método seleccionar_usuario().
  $db = new myDBC();
  $datoscmb = $db->respuestas_cmb($usuario);
  if(!$datoscmb)
  {
    echo'<script type="text/javascript">
          alert("Este usuario no ha respondido a la Entrevista Inicial");
          window.location="http://sav.utim.edu.mx"
        </script>';
    die();
  }
?>
  <div style="margin-top:16px;">
    <br>
    <br>
    <p>Autorizo a que mi hijo (a):
      <span class="negrita"><?php echo $db->respuesta($usuario, 've_p01', 'text'); ?></span>
      alummno (a) de la Universidad Tecnológica de Izúcar de Matamoros
      y del grupo:
      <span class="negrita"><?php echo $datoscmb[1] != "" ? $datoscmb[1] : "-"; ?></span>
      correspondiente al Programa Educativo:
      <span class="negrita"><?php echo $datoscmb[0] != "" ? $datoscmb[0] : "-"; ?></span>,
      pueda asistir al viaje de estudios que se llevará a cabo dentro
      del presente cuatrimestre en la empresa:
      <span class="negrita"><?php echo $db->respuesta($usuario, 've_p05', 'text'); ?>.      
    </p>    
    <p>
      Sin más por el momento quedo de usted como su más atento y seguro servidor.
    </p>
    <br>
    </div>
    <p align="center"><span class="negrita">ATENTAMENTE</span></p>
    <table id="datosAlumno">
    <tr>
      <td></td>      
      <td class="info">
        <?php echo $db->respuesta($usuario, 've_p02', 'text'); ?>
      </td>
      <td></td>
    </tr>
    <tr>
      <td></td>
      <td class="label">Padre de familia o tutor</td>
      <td></td>
    </tr>
    </table>  
</body>
</html>
<?php
}
?>