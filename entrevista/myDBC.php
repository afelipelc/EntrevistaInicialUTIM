<?php
/**
* Clase que ejecuta las consultas a la BD para obtener las respuestas del cuestionario respondido por el alumno
*
* Elaborado por Mtro. Yonatan Cruz e Ing. Felipe Lima
* 
* Octubre de 2014.
*/
class myDBC{
	public $mysqli=null;
	public function __construct()
	{
		include_once("dbconfig.php");
		$this->mysqli=new mysqli(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
		
		if($this->mysqli->connect_errno){
			echo "Error MySQLi: (".$this->mysqli->connect_errono.")".$this->mysqli->connect_error;
			exit();			
		}
		$this->mysqli->set_charset("utf8");
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
			from mdl_questionnaire_response_text as r, mdl_questionnaire_attempts as t, mdl_questionnaire_response as q, mdl_questionnaire_question as p
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
			from mdl_questionnaire_response_bool as r, mdl_questionnaire_attempts as t, mdl_questionnaire_response as q, mdl_questionnaire_question as p
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
			from mdl_questionnaire_resp_multiple as r, mdl_questionnaire_attempts as t, mdl_questionnaire_response as q, mdl_questionnaire_question as p
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
  	$sql = "select content from mdl_questionnaire_quest_choice where id=".$idPregunta." limit 1";
  	return $this->render_to_one($this->mysqli->query($sql));
  }

	/**
	* Función que obtiene el nombre del cuestionario
	* 
	* @param int $idQuestionnaire
	* @return String
	*/
  public function questionnaire($idQuestionnaire){
  	$sql = "select name from mdl_questionnaire where id=".$id." limit 1";
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
  	$row = mysqli_fetch_array($data, MYSQLI_NUM);
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
			from mdl_questionnaire_quest_choice as c, mdl_questionnaire_resp_single as t, mdl_questionnaire_response as f
			where c.question_id=t.question_id
			and t.choice_id=c.id
			and t.response_id=f.id
			and f.username='$usuario'";
		return $this->render_to_array($this->mysqli->query($sql));
	}
}
?>
