<?php 

class Db{
	private $dir = '';
	private $connect = null;

	public function __construct($host,$user,$password,$dbname){
		$this->connect = new mysqli($host,$user,$password,$dbname);
		$this->connect->autocommit(FALSE);
	}

	public function create($table,$data){
		if( empty($data) ) return null;
		$fields = array_keys($data);
		$values = array_values($data);
		foreach ($fields as $k => $field) {
			$fields[$k] = "`".$field."`";
		}
		$fieldsRow = !empty($fields)? implode(',', $fields):'';
		foreach ($values as $k => $value) {
			$values[$k] = "'".$value."'";
		}
		$valuesRow = !empty($values)? implode(',', $values):'';
		$query = "INSERT INTO `$table` ($fieldsRow) VALUES ($valuesRow)";
		$this->connect->query($query);
		$this->Error();
		$id = $this->connect->insert_id;
		return $id;
	}

	public function read($table,$datas = '*',$where = array(),$AndOr = 'AND',$order = array(0,0),$limit = array(0,0)){
		if(is_array($datas)){
			$fields = array_values($datas);
			$fieldsRow = !empty($fields)? implode(',', $fields):'';
		}else{
			$fieldsRow = $datas;
		}
		$query = "SELECT $fieldsRow FROM $table";
		if(!empty($where)){
			$query .= " WHERE ";
			$WHERE = array();
			foreach ($where as $field => $content){
				$WHERE[] = "`".$field."`='".$content."'";
			}
			$whereRow = implode(" $AndOr ", $WHERE);
			$query .= $whereRow;
		}
		if(!empty($order[0]) && !empty($order[1])){
			$query .= " ORDER BY `".$order[0]."` ".$order[1];
		}elseif(!empty($order[0]) && empty($order[1])){
			$query .= " ORDER BY `".$order[0]."` ASC";
		}else{
			$query .= "";
		}
		if(!empty($limit[0]) && !empty($limit[1])){
			$query .= " LIMIT ".$limit[0].", ".$limit[1];
		}elseif(!empty($limit[0]) && empty($limit[1])){
			$query .= " LIMIT ".$limit[0];
		}else{
			$query .= "";
		}
		$answer = $this->connect->query($query);
		$this->Error();
		if(!$answer) return null;
		$response = array();
		while ($row = $answer->fetch_assoc()) {
			$response['rows'][] = $row;
		}
		$response['count'] = $answer->num_rows;
		return $response;  
	}

	public function update($table,$datas,$where = array(),$AndOr = 'AND'){
		if(!$datas) return null;
		$query = "UPDATE `$table` SET ";
		$updated = array();
		foreach ($datas as $field => $data) {
			$updated[] = "`".$field."`='".$data."'";
		}
		$updatedRow = implode(", ", $updated);
		$query .= $updatedRow;
		$WHERE = array();
		if(!empty($where)){
			foreach ($where as $field => $content) {
				$WHERE[] = "`".$field."`='".$content."'";
			}
			$whereRow = implode(" $AndOr ", $WHERE);
			$query .= " WHERE ".$whereRow;
		}
		$answer = $this->connect->query($query);
		$this->Error();
		return $this->connect->affected_rows;
	}

	public function delete($table,$where = array(),$AndOr = 'AND'){
		$query = "DELETE FROM `$table`";
		$WHERE = array();
		if(!empty($where)){
			foreach ($where as $field => $content) {
				$WHERE[] = "`".$field."`='".$content."'";
			}
			$whereRow = implode(" $AndOr ", $WHERE);
			$query .= " WHERE ".$whereRow;
		}
		$answer = $this->connect->query($query);
		$this->Error();
		return $this->connect->affected_rows;
	}

	public function Error(){
		if($this->connect->affected_rows == -1){
			$content = "";
			foreach ($this->connect->error_list as $key => $error) {
				$content .= $error['error']."\n";
			}
			file_put_contents($this->dir.date('Y.m.d.h.i.s').'.txt', $content);
		}
	}

	public function commit(){
		$this->connect->commit();
	}

	public function rollback(){
		$this->connect->rollback();
	}
}
