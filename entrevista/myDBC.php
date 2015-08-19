<?php
/**
* Clase que ejecuta las consultas a la BD para obtener las respuestas del cuestionario respondido por el alumno
* Actualización: Ya no se tiene que modificar prefijos en las consultas, por default se toma de la configuración de Moodle.
* La configuración de los datos de conexión ya no son necesarios, también se toma de la configuración de Moodle.
*
* Elaborado por Mtro. Yonatan Cruz e Ing. Felipe Lima
* 
* Octubre de 2014.
* Actualizado el 13 de mayo de 2015
*/

class myDBC{
	private $mysqli=null;
	private $prefix="";


	public function __construct()
	{
		//include_once("dbconfig.php");
		//$this->mysqli=new mysqli(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
		//tomar el archivo de configuración de Moodle
		require_once("../../../config.php");
		$this->prefix = $CFG->prefix; //guardar el prefijo
		$this->mysqli=new mysqli($CFG->dbhost,$CFG->dbuser,$CFG->dbpass,$CFG->dbname);
		
		if($this->mysqli->connect_errno){
			echo "Error MySQLi: (".$this->mysqli->connect_errono.")".$this->mysqli->connect_error;
			exit();			
		}
		$this->mysqli->set_charset("utf8");
	}

	public function __destruct(){
		$this->mysqli->close();
	}

	/**
	* Función que obtiene el ID del usuario que respondió a la escuesta a mostrar
	*
	* @param int $respuesta
	* @return int
	*
	*/
	public function userIdRespuesta($respuesta){
		//$sql = "SELECT username from ".$this->prefix."questionnaire_response 
		//	where id = $respuesta limit 1";
		if ($stmt = $this->mysqli->prepare("SELECT username from ".$this->prefix."questionnaire_response where id = ? limit 1")){

			$stmt->bind_param("i", $respuesta);
			
			if (!$stmt->execute()) {
    		echo "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
			}

			$stmt->bind_result($usern);
			$stmt->fetch();
			$stmt->close();
  		return $usern;
		}
		else
		{
			//echo "Falló la preparación: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
			return "";
		}
	}

	/**
	* Función que obtiene la respuesta del usuario a una pregunta identificada por nombre y el tipo especificado de pregunta 
	* Tipo: text, boolean, multiple, list
	* 
	* @param int $usuario
  *	@param String $pregunta
  *	@param String $tipo
	* @return String
	*/
	public function respuesta($usuario, $pregunta, $tipo){
		switch ($tipo) {
			case 'text':
				return $this->respuestatxt($usuario, $pregunta);
				break;
			case 'boolean':
				return $this->respuestabool($usuario, $pregunta);
				break;
			case 'multiple':
				return $this->respuestamultiple($usuario, $pregunta);
				break;
			case 'list':
				return $this->respuestas_cmb($usuario);
				break;
			default:
				return null;
				break;
		}
	}

	/**
	* Función que obtiene la respuesta del usuario a una pregunta de tipo Text identificada por nombre
	* 
	* @param int $usuario
  *	@param String $pregunta
	* @return String
	*/
  public function respuestatxt($usuario, $pregunta){
  	$sql = "SELECT r.response as respuesta1
			from ".$this->prefix."questionnaire_response_text as r, 
				".$this->prefix."questionnaire_attempts as t, 
				".$this->prefix."questionnaire_response as q, 
				".$this->prefix."questionnaire_question as p 
			where r.response_id=t.rid 
			and q.id=r.response_id 
			and r.question_id = p.id 
			and p.name = '$pregunta' 
			and q.username='$usuario'";
			return $this->render_to_one($this->mysqli->query($sql));
  }

	/**
	* Función que obtiene la respuesta del usuario a una pregunta de Si|No identificada por nombre
	* 
	* @param int $usuario
  *	@param String $pregunta
	* @return String
	*/
  public function respuestabool($usuario, $pregunta){
  	$sql = "SELECT r.choice_id as respuesta1
			from ".$this->prefix."questionnaire_response_bool as r, 
				".$this->prefix."questionnaire_attempts as t, 
				".$this->prefix."questionnaire_response as q, 
				".$this->prefix."questionnaire_question as p
			where r.response_id=t.rid 
			and q.id=r.response_id 
			and r.question_id = p.id 
			and p.name = '$pregunta' 
			and q.username='$usuario'";
			return $this->render_to_one($this->mysqli->query($sql)) == "y" ? "SI" : "NO";
  }

	/**
	* Función que obtiene las respuestas del usuario a una pregunta de selección múltiple identificada por nombre
	* 
	* @param int $usuario
  *	@param String $pregunta
	* @return Array
	*/
  public function respuestamultiple($usuario, $pregunta){
  	$sql = "SELECT r.choice_id as respuesta1
			from ".$this->prefix."questionnaire_resp_multiple as r, 
				".$this->prefix."questionnaire_attempts as t, 
				".$this->prefix."questionnaire_response as q, 
				".$this->prefix."questionnaire_question as p
			where r.response_id=t.rid
			and q.id=r.response_id
			and r.question_id = p.id
			and p.name = '$pregunta'
			and q.username='$usuario'";
			return $this->render_to_array($this->mysqli->query($sql));
  }

	/**
	* Función que obtiene el valor en Texto de la opción seleccionada por el usuario -complemento a la pregunta de selección múltiple-, la pregunda identificada por nombre
	* 
	* @param int $idPregunta
	* @return String
	*/
  public function respuestaElecta($idPregunta)
  {
  	$sql = "select content from ".$this->prefix."questionnaire_quest_choice where id=".$idPregunta." limit 1";
  	return $this->render_to_one($this->mysqli->query($sql));
  }

	/**
	* Función que obtiene el nombre del cuestionario
	* 
	* @param int $idQuestionnaire
	* @return String
	*/
  public function questionnaire($idQuestionnaire){
  	$sql = "select name from ".$this->prefix."questionnaire where id=".$id." limit 1";
  	return $this->render_to_one($this->mysqli->query($sql));

  }

	/**
	* Función que convierte un MySQLi Result a Array
	* 
	* @param MySQLResult $data
	* @return Array
	*/
	private function render_to_array($data){
		# Array asociativo que contendrá los datos de respuesta.
		$valores = array();
		while($row = mysqli_fetch_array($data, MYSQLI_NUM)) { 
			array_push($valores, $row[0]);
		}
		return $valores;
	}


	/**
	* Función que trasforma un único campo contenido en un único resultado de la consulta SQL
	* 
	* @param MySQLResult $data
	* @return String
	*/
	private function render_to_one($data){
		if($data->num_rows==0){
  		return "";
  	}
  	$row = $data->fetch_array(MYSQLI_NUM);//mysqli_fetch_array($data, MYSQLI_NUM);
  	return $row[0];
	}

	/**
	* Función que elimina los espacios adicionales en el texto recibido
	* @param string text
	* @return string
	*/
	public function clearText($text){
		$text=trim($text);
		return $this->mysqli->real_escape_string($text);
	}

  /**
	* Función que obtiene todas las respuestas de las preguntas de tipo dropdown list
	* 
	* @param int $usuario
	* @return Array
	*/
  public function respuestas_cmb($usuario){
		$sql = "SELECT c.content
			from ".$this->prefix."questionnaire_quest_choice as c, ".$this->prefix."questionnaire_resp_single as t, ".$this->prefix."questionnaire_response as f
			where c.question_id=t.question_id
			and t.choice_id=c.id
			and t.response_id=f.id
			and f.username='$usuario'";
		return $this->render_to_array($this->mysqli->query($sql));
	}


	/**
	* Función que obtiene las respuestas complementarias de observaciones del tutor
	* 
	* @param int $usuario
	* @return string
	*/
  public function respuestas_comp($usuario, $column){
		$sql = "SELECT $column from pit_complement where usuario= $usuario";
		return $this->render_to_one($this->mysqli->query($sql));
	}


	/**
	* Función que almacena las observaciones del tutor
	* 
	* @param int $usuario
	* @param int $vulnerable
	* @param int $socioeconomico
	* @param int $personales
	* @param int $academicos
	* @param string $observaciones
	* @return boolean
	*/
  public function guardar_observaciones($usuario, $vulnerable, $socioeconomico, $personales, $academicos, $observaciones){

		if ($stmt = $this->mysqli->prepare("SELECT usuario from pit_complement where usuario = ? limit 1")){

					$stmt->bind_param("i", $usuario);
					
					if (!$stmt->execute()) {
		    		echo "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
					}

					$stmt->bind_result($usern);
					$stmt->fetch();
					$stmt->close();

					$sql="";
		  		if ($usern == "" || $usern == null)
		  			$sql = "Insert into pit_complement values(0, ?, ?, ?, ?, ?, ?)";
		  		else
		  			$sql = "update pit_complement set vulnerable = ?, socioeconomico = ?, personales = ?, academicos = ?, observaciones = ? where usuario = ? limit 1";
		  		

		  		if ($stmt = $this->mysqli->prepare($sql)){

		  			if ($usern == "" || $usern == null)
		  				$stmt->bind_param('iiiiis',$usuario, $vulnerable, $socioeconomico, $personales, $academicos, $observaciones);
		  			else
		  				$stmt->bind_param('iiiisi',$vulnerable, $socioeconomico, $personales, $academicos, $observaciones, $usuario);

		  			if ($stmt->execute()) {
		    			return true;
						}
						else
						{
							echo "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
							return false;
						}

		  		}
				}
				else
				{
					return false;
				}
	}

}
?>
