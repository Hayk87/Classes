<?php 

	class Validate
	{
		// return 0 if variable(s) is null else return 1
		public static function NotNull($variables = '')
		{
			if(is_array($variables))
			{
				foreach ($variables as $variable) 
				{
					if(empty($variable))
					{
						return 0;
					}
				}
				return 1;
			}
			else
			{
				if(!empty($variables))
				{
					return 1;
				}
				return 0;
			}
		}

		// return 1 if strings is name (a-zA-Z) else return 0
		public static function ifName($Name, $simbols = 0, $limit = 0)
		{
			
			if(!empty($simbols) && !empty($limit))
			{
				$pattern = "/^[a-zA-Z]{".$simbols.",".$limit."}$/";
			}
			elseif (empty($simbols)) 
			{
				$pattern = "/^[a-zA-Z]+$/";
			}
			elseif (!empty($simbols)) 
			{
				$pattern = "/^[a-zA-Z]{".$simbols.",}$/";
			}
			if(is_array($Name))
			{
				foreach ($Name as $name) 
				{
					if(!preg_match($pattern, $name))
					{
						return 0;
					}
				}
				return 1;
			}
			else
			{
				if(preg_match($pattern, $Name))
				{
					return 1;
				}
				return 0;
			}
		}

		// return 1 if strings is login (a-zA-Z0-9-_\.) else return 0
		public static function ifLogin($Login, $simbols = 0, $limit = 0)
		{
			
			if(!empty($simbols) && !empty($limit))
			{
				$pattern = "/^[a-zA-Z0-9-_\.]{".$simbols.",".$limit."}$/";
			}
			elseif (empty($simbols)) 
			{
				$pattern = "/^[a-zA-Z0-9-_\.]+$/";
			}
			elseif (!empty($simbols)) 
			{
				$pattern = "/^[a-zA-Z0-9-_\.]{".$simbols.",}$/";
			}
			if(is_array($Login))
			{
				foreach ($Login as $login) 
				{
					if(!preg_match($pattern, $login))
					{
						return 0;
					}
				}
				return 1;
			}
			else
			{
				if(preg_match($pattern, $Login))
				{
					return 1;
				}
				return 0;
			}
		}

		// return 1 if nombers is nomber (0-9) else return 0
		public static function ifNumeric($Login, $simbols = 0, $limit = 0)
		{
			
			if(!empty($simbols) && !empty($limit))
			{
				$pattern = "/^[0-9]{".$simbols.",".$limit."}$/";
			}
			elseif (empty($simbols)) 
			{
				$pattern = "/^[0-9]+$/";
			}
			elseif (!empty($simbols)) 
			{
				$pattern = "/^[0-9]{".$simbols.",}$/";
			}
			if(is_array($Login))
			{
				foreach ($Login as $login) 
				{
					if(!preg_match($pattern, $login))
					{
						return 0;
					}
				}
				return 1;
			}
			else
			{
				if(preg_match($pattern, $Login))
				{
					return 1;
				}
				return 0;
			}
		}

		// return 1 if emailss is email (a-zA-Z0-9-_\.) else return 0
		public static function ifEmail($emails)
		{
			$pattern = "/^([a-zA-Z0-9-_\.]{3,})@([a-zA-Z0-9-_]{4,})\.([a-zA-Z]{2,6})$/";
			if(is_array($emails))
			{
				foreach ($emails as $email) 
				{
					if(!preg_match($pattern, $email))
					{
						return 0;
					}
				}
				return 1;
			}
			else
			{
				if(preg_match($pattern, $emails))
				{
					return 1;
				}
				return 0;
			}
		}
	}