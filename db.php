<?php 
	class Database
	{
		private $connect;
		// Initializ PDO and connect Database
		public function __construct($host, $db, $user, $password)
		{
			$connect = "mysql:host=".$host.";dbname=".$db.";charset=utf8";
			$this->connect = new PDO($connect,$user,$password);
		} 
		// Dinamic Insert Function
		/*
		* Arguments
		* 1 - Table name
		* 2 - Insert datas asoc-array ['field' => 'content']
		*/
		public function register($table, $data)
		{
			if( is_array($data) && !empty($data))
			{
				$query = "INSERT INTO `$table` (`";
				$index = 0;
				foreach ($data as $field => $content) 
				{
					if( count($data) - 1 == $index )
					{
						$query .= $field."`) VALUES ('";
					}
					else
					{
						$query .= $field."`,`";
						$index ++;
					}
				}
				$index = 0;
				foreach ($data as $field => $content) 
				{
					if( count($data) - 1 == $index )
					{
						$query .= $content."')";
					}
					else
					{
						$query .= $content."','";
						$index ++;
					}
				}
				$result = $this->connect->query($query);
				if($result == null)
				{
					echo "<h3>ERROR IN QUERY: Syntax error in Field(s)</h3>";die;
				}
				return $this->connect->lastInsertId();
			}
			else
			{
				echo "<h3>ERROR-ARRAY: 2-th argument must be ARRAY and NOT EMPTY !</h3>";die;
			}
		}
		// Dinamic Select Function
		/*
		* Arguments
		* 1 - Table name
		* 2 - array (order by) ['sort' => ['field', 'DESC'] ]
		* 3 - array (limit) ['limit' => [$count, $offset] ]
		* 4 - array (where) default is null array
		* 5 - AND | OR , default AND
		* return Result and Count-Result
		*/
		public function getFromDb($table,$fields = "*",$where = array(),$sort = array('', 'ASC'),$limit = array(0,0) , $if = 'AND')
		{
			if(is_array($fields)){
				$Fieldes = '';
				$fieldsCount = count($fields);
				foreach ($fields as $key => $field) {
					if($fieldsCount == ($key+1)){
						$Fieldes .= $field;
					}else{
						$Fieldes .= $field.',';
					}
				}
				$fields = $Fieldes;
			}
			$query = "SELECT $fields FROM $table ";
			if(!empty($where['ml']) && $where['ml'] == 1)
			{
				$query .= "LEFT JOIN ".$table."_data ON ".$table.".".$table."_id"."=".$table."_data.".$table."_data_self_id ";
				unset($where['ml']);
			}
			if(!empty($where))
			{
				$query .= "WHERE ";
				$index = 1;
				foreach ($where as $field => $content) 
				{
					if($index == count($where))
					{
						$query .= $field." = '".$content."'";
					}
					else
					{
						$query .= $field." = '".$content."' $if ";
						$index ++;
					}
				}
			}
			
			if(!empty($sort[0]) && !empty($sort[1]))
			{
				$query .= " ORDER BY ".$sort[0]." ".$sort[1];
			}
			elseif(!empty($sort[0]))
			{
				$query .= " ORDER BY ".$sort[0]." ASC";
			}

			if(!empty($limit[0]) && !empty($limit[1]))
			{
				$query .= " LIMIT ".$limit[0].", ".$limit[1];
			}
			elseif(!empty($limit[0]))
			{
				$query .= " LIMIT ".$limit[0];
			}
			//print_r($query);die;
			$get = $this->connect->query($query);
			$result['result'] = $get->fetchAll(PDO::FETCH_ASSOC);
			$result['count'] = $get->rowCount();
			return $result;
		}
		// Dinamic Update Function
		/*
		* Arguments
		* 1 - Table name
		* 2 - updating datas (array)
		* 3 - where, default null array
		* 4 - AND | OR , default 'AND'
		* return 1 | 0
		*/
		public function updateInDb($table, $datas, $where = array(), $if = "AND")
		{
			$query = "UPDATE $table SET ";
			$index = 1;
			foreach ($datas as $field => $content) 
			{
				if( $index == count($datas) )
				{
					$query .= $field." = '".$content."'";
				}
				else
				{
					$query .= $field." = '".$content."', ";
					$index ++;
				}
			}
			if(!empty($where))
			{
				$query .= " WHERE ";
				$index = 1;
				foreach ($where as $field => $content) 
				{
					if($index == count($where))
					{
						$query .= $field." = '".$content."'";
					}
					else
					{
						$query .= $field." = '".$content."' $if ";
						$index ++;
					}
				}
			}
			if( count( $this->connect->query($query) ) > 0 )
			{
				return 1;
			}
			else
			{
				return 0;
			}
		}
		// Dinamic Delete Function
		/*
		* Arguments
		* 1 - Table name
		* 2 - where, default null array
		* 3 - AND | OR , default 'AND'
		* return 1 | 0
		*/
		public function deleteInDb($table, $where = array(), $if = "AND")
		{
			$query = "DELETE FROM $table";
			if(!empty($where))
			{
				$query .= " WHERE ";
				$index = 1;
				foreach ($where as $field => $content) 
				{
					if($index == count($where))
					{
						$query .= $field." = '".$content."'"; 
					}
					else
					{
						$query .= $field." = '".$content."' $if ";
						$index ++;
					} 
				}
			}
			if( count( $this->connect->query($query) ) > 0 )
			{
				return 1;
			}
			else
			{
				return 0;
			}
		}
		// Some query
		public function someQuery($query)
		{
			return $this->connect->query($query);
		}
		// END Some query 

		// Join function
		/*
			join( 'users','LEFT',array(array('phones','phones.phone_user_id','users.user_id')) )
		*/
		public function join( $table,$select, $position = '', $ON = array(), $where = array(), $sort = array('','ASC'), $limit = array(0,0), $if = 'AND' )
		{
			$query = "SELECT $select FROM $table";
			if(is_array($ON) && !empty($ON))
			{
				foreach ($ON as $key => $item) 
				{
					if(is_array($item) && !empty($item))
					{
						$query .= " $position JOIN $item[0] ON $item[1] = $item[2]";
					}
				}
			}
			if(!empty($where))
			{
				$query .= " WHERE ";
				$countWhere = count($where);
				$index = 0;
				foreach ($where as $field => $content) 
				{
					if($index == ($countWhere-1))
					{
						$query .= $field."='".$content."'";
					}
					else
					{
						$query .= $field."='".$content."' $if ";
						$index++;
					}
					
				}
			}
			if(!empty($sort[0]))
			{
				$query .= " ORDER BY ".$sort[0]." ".$sort[1];
			}
			if(!empty($limit[0]) && !empty($limit[1]))
			{
				$query .= " LIMIT ".$limit[0].", ".$limit[1];
			}
			elseif(!empty($limit[0]))
			{
				$query .= " LIMIT ".$limit[0];
			}
			$Query = $this->connect->query($query);
			$result = $Query->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}
		// END Join function

		/*******
		argument:
		1. table name
		2. select field|fields, array or string
		3. language id
		4. multi language, default is true
		*** Must by fields(example table` menu):menu_id,menu_active; (menu_ml table):menu_ml_self_id,menu_ml_lng_id,menu_ml_parent_id
		*******/
		public function getTree($tableName,$select = '*',$lngid = 1,$ml = 1){
			if(is_array($select)){
				$Fieldes = '';
				$fieldsCount = count($select);
				foreach ($select as $key => $field) {
					if($fieldsCount == ($key+1)){
						$Fieldes .= $field;
					}else{
						$Fieldes .= $field.',';
					}
				}
				$select = $Fieldes;
			}
			if($ml == 1){
				$ON = array(
						array($tableName.'_ml',$tableName.'_id',$tableName.'_ml_self_id')
					);
				$where = array($tableName.'_parent_id' => 0,$tableName.'_active' => 1,$tableName.'_ml_lng_id' => $lngid);
				$parent_categories = $this->join($tableName,$select,'LEFT',$ON,$where);
			}else{
				$where = array($tableName.'_parent_id' => 0,$tableName.'_active' => 1);
				$parent_categories = $this->getFromDb($tableName,$select,$where);
				$parent_categories = $parent_categories['result'];
			}
			return $this->getChiled($parent_categories,$tableName,$select,$lngid,$ml);
		}
		// END

		// 1. array, 2. table name, 3. select = * , 4. lng Id
		public function getChiled($parent_categories,$tableName,$select = '*',$lngId = 1,$ml = 1){
			foreach ($parent_categories as $key => $category) {
				if($ml == 1){
					$ON = array(
							array($tableName.'_ml',$tableName.'_id',$tableName.'_ml_self_id')
						);
					$where = array($tableName.'_parent_id' => $parent_categories[$key][$tableName.'_id'],$tableName.'_ml_lng_id' => $lngId,$tableName.'_active' => 1);
					$data = $this->join($tableName,$select,'LEFT',$ON,$where);
					if(!empty($data)){
						$parent_categories[$key]['childs'] = $this->getChiled($data,$tableName,$select,$lngId,$ml);
					}
				}else{
					$where = array($tableName.'_parent_id' => $parent_categories[$key][$tableName.'_id'],$tableName.'_active' => 1);
					$data = $this->getFromDb($tableName,$select,$where);
					$data = $data['result'];
					if(!empty($data)){
						$parent_categories[$key]['childs'] = $this->getChiled($data,$tableName,$select,$lngId,$ml);
					}
				}
				
			}
			return $parent_categories;
		}
		// END
	}