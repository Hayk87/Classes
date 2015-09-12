<?php 

	class Database
	{
		private $connect;

		// Initializ PDO and connect Database
		public function __construct($host, $db, $user, $password)
		{
			$connect = "mysql:host=".$host.";dbname=".$db.";";
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
				return true;
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
		public function getFromDb($table, $sort = array('sort' => array('', '')), 
			$limit = array( 'limit' => array(0,0) ), 
			$where = array(), 
			$if = 'AND')
		{
			$query = "SELECT * FROM $table ";
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
			
			if(!empty($sort['sort'][0]))
			{
				$query .= " ORDER BY ".$sort['sort'][0]." ".$sort['sort'][1];
			}
			if(!empty($limit['limit'][0]) && $limit['limit'][0] > 0 && !empty($limit['limit'][1]) && $limit['limit'][1] > 0)
			{
				$query .= " LIMIT ".$limit['limit'][1].", ".$limit['limit'][0];
			}
			elseif($limit['limit'][0] > 0)
			{
				$query .= " LIMIT ".$limit['limit'][0];
			}
			$get = $this->connect->query($query);
			$result['result'] = $get->fetchAll();
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
	}