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
// Creamos objeto de la clase myDBC.
// para hacer uso del m?odo seleccionar_usuario().
$db = new myDBC();

// Recibimos el usuario de la encuesta a través del formulario en un hidden.
$usuario = "";

  if( isset($_POST['seleccionaUsuario']))
  {
    $usuario = $db->userIdRespuesta($_POST['seleccionaUsuario']); //$_POST['seleccionaUsuario'];
  }elseif (isset($_GET["rid"], $_GET["action"]) && $_GET["action"] == "list") {
    $usuario = $db->userIdRespuesta($_GET["rid"]);
  }

if($usuario==""){
  echo'<script type="text/javascript">
      alert("Ning?n registro");
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
  <title>Entrevista Inicial</title>
	<link rel ="stylesheet" type ="text/css" href = "impresion.css" />
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
      <h3>METODOLOGÍA PARA LA PREVENCIÓN Y SEGUIMIENTO DE LA DESERCIÓN</h3>
      <h5>ANEXO 2</h5>
      <h4>FORMATO DE ENTREVISTA INICIAL</h4>
      <span class="fecha">Izúcar de Matamoros, Puebla. a <?php echo $fecha; ?></span>
    </div>
  </div>

<?php

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

  <table id="datosAlumno">
    <tr>
      <td class="label">Nombre</td>
      <td class="info"><?php echo $db->respuestatxt($usuario, 'ei_p02'); echo " ".$db->respuestatxt($usuario, 'ei_p03'); ?> </td>
      <td class=" label espi">Dirección</td>
      <td class="info"><?php echo $db->respuestatxt($usuario, 'ei_p05'); ?></td>
    </tr>
    <tr>
      <td class="label">Matricula:</td>
      <td class="info"><?php echo $db->respuestatxt($usuario, 'ei_p01'); ?></td>
      <td class=" label espi">Teléfono:</td>
      <td class="info"><?php echo $db->respuestatxt($usuario, 'ei_p06'); ?></td>
    </tr>
    <tr>
      <td class="label">Programa educativo:</td>
      <td class="info"><?php echo $datoscmb[0] != "" ? $datoscmb[0] : "-"; ?></td>
      <td class=" label espi">Celular:</td>
      <td class="info"><?php echo $db->respuestatxt($usuario, 'ei_p07'); ?></td>
    </tr>
    <tr>
      <td class="label">Grupo escolar:</td>
      <td class="info"><?php echo $datoscmb[1] != "" ? $datoscmb[1] : "-"; ?></td>
      <td class=" label espi">Estado civil:</td>
      <td class="info"><?php echo $db->respuestatxt($usuario, 'ei_p11'); ?></td>
    </tr>
    <tr>
      <td class="label">Nombre del tutor</td>
      <td class="info"><?php echo $datoscmb[2] != "" ? $datoscmb[2] : "-"; ?></td>
      <td class=" label espi">Edad</td>
      <td class="info"><?php echo $db->respuestatxt($usuario, 'ei_p08'); ?></td>
    </tr>
  </table>
  <div class="contenedor">
    <p class="title">I. ASPECTOS SOCIOECONÓMICOS </p>
    <ol>
      <li>
        <div>
          <?php $respYN = $db->respuestabool($usuario, 'ei_p12'); ?>
          <p>¿Resides en esta ciudad?:  <strong><span class="lineac"> <?php echo $respYN; ?></span> </strong>
          <?php if($respYN == "SI"){ ?>
            Tiempo: <span class="lineac"><?php echo $db->respuestatxt($usuario, 'ei_p13'); ?></span>
            <?php
          }else if($respYN == "NO"){ ?>
            Especifica: <span class="lineag"><?php echo $db->respuestatxt($usuario, 'ei_p14'); ?></span>
          <?php } ?></p>
        </div></li>
      <li>
        <p>¿Con quién vives actualmente? <span class="lineam"><?php echo $db->respuestatxt($usuario, 'ei_p15'); ?></span></p></li>
      <li>
        <div>
          <?php $respYN = $db->respuestabool($usuario, 'ei_p16'); ?>
          <p>¿Trabajas?: <strong><span class="lineac"> <?php echo $respYN; ?></span></strong>
            <?php if($respYN == "SI"){ ?>
             ¿En dónde?: <span class="lineam"><?php echo $db->respuestatxt($usuario, 'ei_p17'); ?></span>
              Horas semanales: <span class="lineac"><?php echo $db->respuestatxt($usuario, 'ei_p18'); ?></span>
              Ingreso: <span class="lineac"><?php echo $db->respuestatxt($usuario, 'ei_p19'); ?></span>
            <?php
            } ?>
          </p>
        </div></li>
      <li>
        <p>¿De quién dependes económicamente? <span class="lineam"><?php echo $db->respuestatxt($usuario, 'ei_p20'); ?></span> Ingreso mensual de quién dependes: <span class="lineam"><?php echo $db->respuestatxt($usuario, 'ei_p21'); ?></span></p></li>
      <li>
        <p>¿A qué se dedica tu papá? <span class="lineag"><?php echo $db->respuestatxt($usuario, 'ei_p22'); ?></span></p></li>
      <li>
        <p>¿A qué se dedica tu mamá? <span class="lineag"><?php echo $db->respuestatxt($usuario, 'ei_p23'); ?></span></p></li>
      <li>
        <p> Si tienes hermanos señala cuántos son: <span class="lineac"><?php echo $db->respuestatxt($usuario, 'ei_p24'); ?></span> &nbsp;&nbsp;&nbsp;Señala la actividad principal de cada uno: </p>
        <p class="lineacompleta"><?php echo $db->respuestatxt($usuario, 'ei_p25'); ?></p>
      </li>
      <li><p>La casa que habitas es: <span class="lineam"><?php echo $datoscmb[3] != "" ? $datoscmb[3] : "-"; ?></span></p></li>
      <li>
        <p>Ingreso familiar mensual (aproximado): <span class="lineag"><?php echo $db->respuestatxt($usuario, 'ei_p27'); ?></span></p></li>
    </ol>
  </div>

  <div class="contenedor">
    <p class="title">II. ASPECTOS PERSONALES</p>
    <ol>
      <li>
      <?php $respYN = $db->respuestabool($usuario, 'ei_p28'); ?>
      <p>¿Padeces alguna enfermedad o alergia? <strong><span class="lineac"> <?php echo $respYN; ?></span></strong>
       <?php if($respYN == "SI"){ ?>
        Especifica: <span class="lineag"><?php echo $db->respuestatxt($usuario, 'ei_p29'); ?></span>
      <?php } ?></p>
      </li>
      <li><p>¿Con qué frecuencia presentas enfermedades menores como gripe, infecciones estomacales, dolores de de cabeza, etc.? (Especifica enfermedad y frecuencia): <span class="lineag"><?php echo $db->respuestatxt($usuario, 'ei_p30'); ?></span></p></li>
      <li>De los siguientes aspectos, marque con una (X) aquellos que usted observa en el alumno de forma evidente:
        <table id="observaciones">
        <?php
          $obesidad = $delgadez = $manchas = $energia = $dentadura = $visuales = $auditivos = $discapacidades = $otros = false;
          $respuestas = $db->respuestamultiple($usuario, 'ei_p31');
          foreach ($respuestas as $respuesta => $value) {
            $texto = $db->respuestaElecta($value);
            switch ($texto) {
              case 'Obesidad':
               $obesidad = true;
                break;
              case 'Delgadez Extrema':
               $delgadez = true;
                break;
              case 'Manchas en la piel':
               $manchas = true;
                break;
              case 'Falta de Energía':
               $energia = true;
                break;
              case 'Problemas de Dentadura':
               $dentadura = true;
                break;
              case 'Problemas Visuales':
               $visuales = true;
                break;
              case 'Problemas Auditivos':
               $auditivos = true;
                break;
              case 'Discapacidades':
               $discapacidades = true;
                break;
              case 'Otros':
               $otros = true;
                break;
              default:
                break;
            }
          }
          $esp = "&nbsp;&nbsp;&nbsp;";
        ?>
          <tr><td>OBESIDAD</td><td>(<?php echo $obesidad ? "X" : $esp; ?>)</td></tr>
          <tr><td>DELGADEZ EXTREMA</td><td>(<?php echo $delgadez ? "X" : $esp; ?>)</td></tr>
          <tr><td>MANCHAS EN LA PIEL</td><td>(<?php echo $manchas ? "X" : $esp; ?>)</td></tr>
          <tr><td>FALTA DE ENERGÍA</td><td>(<?php echo $energia ? "X" : $esp; ?>)</td></tr>
          <tr><td>PROBLEMAS DE DENTADURA</td><td>(<?php echo $dentadura ? "X" : $esp; ?>)</td></tr>
          <tr><td>PROBLEMAS VISUALES</td><td>(<?php echo $visuales ? "X" : $esp; ?>)</td></tr>
          <tr><td>PROBLEMAS AUDITIVOS</td><td>(<?php echo $auditivos ? "X" : $esp; ?>)</td></tr>
          <tr><td>DISCAPACIDADES</td><td>(<?php echo $discapacidades ? "X" : $esp; ?>)</td></tr>
          <tr><td>OTROS</td><td>(<?php echo $otros ? "X" : $esp; ?>)
            <?php if($otros){ ?>
           <span class="lineag"><?php echo $db->respuestatxt($usuario, 'ei_p32'); ?></span>
          <?php } ?>
           </td></tr>
        </table>
      </li>
    </ol>
  </div>

  <div id="pageheader" class="secondheader">
    <h3>METODOLOGÍA PARA LA PREVENCIÓN Y SEGUIMIENTO DE LA DESERCIÓN</h3>
    <h5>ANEXO 2</h5>
    <h4>FORMATO DE ENTREVISTA INICIAL</h4>
  </div>

  <div class="contenedor">
    <ol start="4">
      <li>
        <?php $respYN = $db->respuestabool($usuario, 'ei_p33'); ?>
        <p>¿Tomas algún medicamento periodicamente? <strong><span class="lineac"> <?php echo $respYN; ?></span></strong> 
        <?php if($respYN == "SI"){ ?>
        ¿Cuál?<span class="lineag"><?php echo $db->respuestatxt($usuario, 'ei_p34'); ?></span>
        <?php } ?></p>
      </li>
      <li>
        <?php $respYN = $db->respuestabool($usuario, 'ei_p35'); ?>
        <p>¿Fumas? <strong><span class="lineac"> <?php echo $respYN; ?></span></strong> 
        <?php if($respYN == "SI"){ ?>
        Especifica cantidad y frecuencia: <span class="lineag"><?php echo $db->respuestatxt($usuario, 'ei_p36'); ?></span>
        <?php } ?></p>
      </li>
      <li>
        <?php $respYN = $db->respuestabool($usuario, 'ei_p37'); ?>
        <p>¿Ingieres bebidas alcohólicas? <strong><span class="lineac"> <?php echo $respYN; ?></span></strong> 
        <?php if($respYN == "SI"){ ?>
        Especifica cantidad y frecuencia: <span class="lineag"><?php echo $db->respuestatxt($usuario, 'ei_p38'); ?></span>
        <?php } ?></p>
      </li>
      <li><p>¿Cuáles consideras que son tus principales cualidades?
      <span class="subrayado"><?php echo $db->respuestatxt($usuario, 'ei_p39'); ?></span></p></li>
      <li><p>¿Cuáles consideras que son tus principales defectos? <span class="subrayado"><?php echo $db->respuestatxt($usuario, 'ei_p40'); ?></span></p></li>
      <li><p>¿Qué valores aprecias más en la gente? <span class="subrayado"><?php echo $db->respuestatxt($usuario, 'ei_p41'); ?></span></p></li>
      <li><p>¿Qué es lo que más te disgusta de la gente? <span class="subrayado"><?php echo $db->respuestatxt($usuario, 'ei_p42'); ?></span></p></li>
      <li><p>Señala tres situaciones o aspectos que te provocan temor:  <span class="subrayado"><?php echo $db->respuestatxt($usuario, 'ei_p43'); ?></span></p></li>
      <li><p>¿Actualmente tienes novio(a)?  <span class="subrayado"><?php echo $db->respuestatxt($usuario, 'ei_p44'); ?></span></p></li>
      <li><p>¿Tienes planes de matrimonio en el corto plazo?  <span class="subrayado"><?php echo $db->respuestatxt($usuario, 'ei_p45'); ?></span></p></li>
      <li><p>¿Qué planes tienes para tu futuro personal?  <span class="subrayado"><?php echo $db->respuestatxt($usuario, 'ei_p46'); ?></span></p></li>
      <li><p>¿Qué planes tienes para tu futuro acad?ico?  <span class="subrayado"><?php echo $db->respuestatxt($usuario, 'ei_p47'); ?></span></p></li>
      <li><p>¿Qué planes tienes para tu futuro profesional?  <span class="subrayado"><?php echo $db->respuestatxt($usuario, 'ei_p48'); ?></span></p></li>
      <li><p>¿A qué te dedicas en tu tiempo libre?  <span class="subrayado"><?php echo $db->respuestatxt($usuario, 'ei_p49'); ?></span></p></li>
    </ol>
  </div> 

  <div class="contenedor">
    <p class="title">III. ASPECTOS ACADÉMICOS</p>
    <ol>
      <li>
        <p><span class="etiqueta">Bachillerato:</span><span class="subrayado2"><?php echo $db->respuestatxt($usuario, 'ei_p50'); ?></span></p>
        <p><span class="etiqueta">Turno:</span><span class="subrayado2"><?php echo $db->respuestatxt($usuario, 'ei_p51'); ?></span></p>
        <p><span class="etiqueta">Localidad: </span><span class="linea40"><?php echo $db->respuestatxt($usuario, 'ei_p52'); ?></span>  &nbsp;&nbsp;&nbsp;Entidad: <span class="lineam"><?php echo $db->respuestatxt($usuario, 'ei_p53'); ?></span></p>
        <p><span class="etiqueta">Especialidad:</span><span class="subrayado2"><?php echo $db->respuestatxt($usuario, 'ei_p54'); ?></span></p>
        <p><span class="etiqueta">Promedio:</span><span class="subrayado2"><?php echo $db->respuestatxt($usuario, 'ei_p55'); ?></span></p>
        </li>
        <li>
          <p>Puntaje Examen CENEVAL: <span class="lineag"><?php echo $db->respuestatxt($usuario, 'ei_p56'); ?></span></p></li>
        <li>
          <p>¿Por qué elegiste estudiar en una Universidad Tecnológica?: <span class="subrayado"><?php echo $db->respuestatxt($usuario, 'ei_p57'); ?></span></p></li>
        <li>
          <p>¿Ésta Universidad era tu primera opción? <strong><span class="lineac"> <?php echo $db->respuestabool($usuario, 'ei_p37'); ?></span></strong></p></li>
        <li>
          <p>¿Esta carrera era tu primera opción? <strong><span class="lineac"> <?php echo $db->respuestabool($usuario, 'ei_p37'); ?></span></strong></p></li>
        <li><p>¿Qué esperas de esta carrera? </p>
          <p class="lineacompleta"><?php echo $db->respuestatxt($usuario, 'ei_p60'); ?></p></li>
         <li><p>¿Tienes planeado presentar examen de admisión para ingresar a otra escuela o carrera?  
          <span class="lineam"><?php echo $db->respuestatxt($usuario, 'ei_p61'); ?></span></p>
        </li>
        <li>¿Qué materias se te dificultan más?
          <span class="lineag"><?php echo $db->respuestatxt($usuario, 'ei_p62'); ?></span></li>
        <li>
        <?php $respYN = $db->respuestabool($usuario, 'ei_p63'); ?>
          <p>¿Has reprobado alguna materia o presentado examen extraordinario? <strong><span class="lineac"> <?php echo $respYN; ?></span></strong> 
          <?php if($respYN == "SI"){ ?>
           ¿Qué materia(s)? <span class="lineag"><?php echo $db->respuestatxt($usuario, 'ei_p64'); ?></span>
          <?php } ?>
           </p></li>
        <li>
          <?php $respYN = $db->respuestabool($usuario, 'ei_p65'); ?>
          <p>¿Utilizas alguna manera o técnica de estudio? <strong><span class="lineac"> <?php echo $respYN; ?></span></strong> 
          <?php if($respYN == "SI"){ ?>
          ¿cuál?  <span class="lineag"><?php echo $db->respuestatxt($usuario, 'ei_p66'); ?></span>
          <?php } ?></p>
        </li>
        <li>
        <?php $respYN = $db->respuestabool($usuario, 'ei_p67'); ?>
          <p>¿Cuentas en tu casa con algunos libros que apoyan tus estudios?  <strong><span class="lineac"> <?php echo $respYN; ?></span></strong> 
          <?php if($respYN == "SI"){ ?>
          ¿cuántos?  <span class="lineam"><?php echo $db->respuestatxt($usuario, 'ei_p68'); ?></span>
          <?php } ?>
          </p>
          </li>
        <li>
        <p>¿Tienes computadora en tu casa como apoyo para tus trabajos y tareas escolares? <strong><span class="lineac"> <?php echo $db->respuestabool($usuario, 'ei_p69'); ?></span></strong> </p></li>
    </ol>
  </div>

  <div id="pageheader" class="secondheader">
    <h3>METODOLOGÍA PARA LA PREVENCIÓN Y SEGUIMIENTO DE LA DESERCIÓN</h3>
    <h5>ANEXO 2</h5>
    <h4>FORMATO DE ENTREVISTA INICIAL</h4>
  </div>

  <div style="margin-top:16px;">
    <p><span class="negrita">De acuerdo a la información obtenida en los aspectos I, II y III, ?Se considera al alumno como elemento de uno o más grupos altamente vulnerables?</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    SI (&nbsp;&nbsp;) &nbsp;&nbsp;&nbsp;&nbsp; NO (&nbsp;&nbsp;)</p>
    <p class="negrita">Marque los grupos en los que se considera se incluye al alumno como altamente vulnerable.</p>
    <table id="vulnerabilidades">
          <tr><td>Aspectos Socioeconóicos</td><td>(&nbsp;&nbsp;)</td></tr>
          <tr><td>Aspectos Personales </td><td>(&nbsp;&nbsp;)</td></tr>
          <tr><td>Aspectos Académicos</td><td>(&nbsp;&nbsp;)</td></tr>
    </table>

    <div><p class="negrita">OBSERVACIONES DEL TUTOR: </p>
    <p class="lineacompleta">
    <p class="lineacompleta">
    <p class="lineacompleta">
    <p class="subrayado"></p></div>
  </div>
</body>
</html>

<?php
}
?>