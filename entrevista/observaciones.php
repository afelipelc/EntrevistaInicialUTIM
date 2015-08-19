<?php
header('Content-Type: text/html; charset="utf-8"', true);
include_once("myDBC.php");
// Creamos objeto de la clase myDBC.
// para hacer uso del modo seleccionar_usuario().
$db = new myDBC();

// Recibimos el usuario de la encuesta a través del formulario.
$usuario = "";

  if( isset($_POST['seleccionaUsuario']))
  {
    $usuario = $db->userIdRespuesta($_POST['seleccionaUsuario']); 
  }elseif (isset($_GET["rid"], $_GET["action"]) && $_GET["action"] == "obs") {
    $usuario = $db->userIdRespuesta($_GET["rid"]);

  }

if($usuario==""){
  echo'<script type="text/javascript">
      alert("Ning?n registro");
      //window.location="http://sav.utim.edu.mx";
    </script>';
}

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
<html lang="es">
<head>
  <title>Entrevista Inicial :: Observaciones del tutor</title>
  <link rel ="stylesheet" type ="text/css" href = "impresion.css" />
</head>
<body>
  <div id="pageheader" class="secondheader">
    <h3>METODOLOGÍA PARA LA PREVENCIÓN Y SEGUIMIENTO DE LA DESERCIÓN</h3>
    <h5>ANEXO 2</h5>
    <h4>FORMATO DE ENTREVISTA INICIAL</h4>
  </div>

  <table id="datosAlumno">
    <tr>
      <td class="label">Nombre</td>
      <td class="info"><?php echo $db->respuesta($usuario, 'ei_p02', 'text'); echo " ".$db->respuestatxt($usuario, 'ei_p03'); ?> </td>
      <td class=" label espi">Dirección</td>
      <td class="info"><?php echo $db->respuesta($usuario, 'ei_p05', 'text'); ?></td>
    </tr>
    <tr>
      <td class="label">Matricula:</td>
      <td class="info"><?php echo $db->respuestatxt($usuario, 'ei_p01'); ?></td>
      <td class="label">Programa educativo:</td>
      <td class="info"><?php echo $datoscmb[0] != "" ? $datoscmb[0] : "-"; ?></td>
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

<?php
  if(isset($_POST['storedata'])){
    //store data
    $vulnerable = $_POST['vulnerable'];
    $socioeconomico  = $_POST['seconomicos'];
    $personales  = $_POST['personales'];
    $academicos  = $_POST['academicos'];
    $observaciones  = $_POST['observaciones'];
    $guardado = $db->guardar_observaciones($usuario,$vulnerable, $socioeconomico, $personales, $academicos, $observaciones);

    echo '<span class="negrita"><br><br><br>';
    echo $guardado == true ? "Se han guardado los datos" : "Ocurrió un error al intentar guardar, repórtelo.";
echo '</span>';

    if(!$guardado){
      die();
    }

?>
    <div style="margin-top:16px;">
    <p><span class="negrita">De acuerdo a la información obtenida en los aspectos I, II y III, ¿Se considera al alumno como elemento de uno o más grupos altamente vulnerables?</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <?php echo $db->respuestas_comp($usuario, 'vulnerable') == '1' ? "SI (X) &nbsp;&nbsp;&nbsp;&nbsp;  NO (&nbsp;&nbsp;&nbsp;)" :  "SI (&nbsp;&nbsp;&nbsp;)   &nbsp;&nbsp;&nbsp;&nbsp; NO (X)"; ?> </p>
    <p class="negrita">Marque los grupos en los que se considera se incluye al alumno como altamente vulnerable.</p>
    <table id="vulnerabilidades">
          <tr><td>Aspectos Socioeconóicos</td><td> <?php echo $db->respuestas_comp($usuario, 'socioeconomico') == '1' ? "(X)" :  "(&nbsp;&nbsp;&nbsp;)"; ?> </td></tr>
          <tr><td>Aspectos Personales </td><td> <?php echo $db->respuestas_comp($usuario, 'personales') == '1' ? "(X)" :  "(&nbsp;&nbsp;&nbsp;)"; ?> </td></tr>
          <tr><td>Aspectos Académicos</td><td> <?php echo $db->respuestas_comp($usuario, 'academicos') == '1' ? "(X)" :  "(&nbsp;&nbsp;&nbsp;)"; ?> </td></tr>
    </table>

    <div><p class="negrita">OBSERVACIONES DEL TUTOR: </p>
    <p class="subrayado"><?php echo $db->respuestas_comp($usuario, 'observaciones');?></p></div>
  </div>

<?php
  }else
  {
    ?>

    <form name="observaciones" method="POST">
      <div style="margin-top:16px;">
        <p><span class="negrita">De acuerdo a la información obtenida en los aspectos I, II y III, ¿Se considera al alumno como elemento de uno o más grupos altamente vulnerables?</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    
        <?php 
        $vuln = $db->respuestas_comp($usuario, 'vulnerable'); 
        $soc = $db->respuestas_comp($usuario, 'socioeconomico');
        $pers = $db->respuestas_comp($usuario, 'personales');
        $acad = $db->respuestas_comp($usuario, 'academicos');
        ?>
        <input type="radio" name="vulnerable" value="1" <?php echo $vuln == '1' ? "checked" : ""; ?> > SI  <input type="radio" name="vulnerable" value="0" <?php echo $vuln == '0' ? "checked" : ""; ?> > NO </p>
        <p class="negrita">Marque los grupos en los que se considera se incluye al alumno como altamente vulnerable.</p>
        <table id="vulnerabilidades">
              <tr><td>Aspectos Socioeconóicos</td><td>  <input type="radio" name="seconomicos" value="1" <?php echo $soc == '1' ? "checked" : ""; ?> > SI  <input type="radio" name="seconomicos" value="0" <?php echo $soc == '0' ? "checked" : ""; ?> > NO </p> </td></tr>
              <tr><td>Aspectos Personales </td><td> <input type="radio" name="personales" value="1" <?php echo $pers == '1' ? "checked" : ""; ?> > SI  <input type="radio" name="personales" value="0" <?php echo $pers == '0' ? "checked" : ""; ?> > NO </td></tr>
              <tr><td>Aspectos Académicos</td><td> <input type="radio" name="academicos" value="1" <?php echo $acad == '1' ? "checked" : ""; ?> > SI  <input type="radio" name="academicos" value="0" <?php echo $acad == '0' ? "checked" : ""; ?>> NO </td></tr>
        </table>

        <div><p class="negrita">OBSERVACIONES DEL TUTOR: </p>
        <textarea name="observaciones" rows="6" cols="50"><?php echo $db->respuestas_comp($usuario, 'observaciones'); ?></textarea>
        </div>
      </div>
      <br><br>
      <input type="submit" value="Guardar información" name="storedata">
    </form>
  <?php
  }

  ?>
</body>