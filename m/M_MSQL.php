<?php
//
// Помощник работы с БД
//
class M_MSQL
{
	private static $instance;	// экземпляр класса
    private $db;

	//
	// Получение экземпляра класса
	// результат	- экземпляр класса MSQL
	//
	public static function Instance()
	{
		if (self::$instance == null){
			self::$instance = new M_MSQL();
        }
		return self::$instance;
	}

	private function __construct()
	{
		// Языковая настройка.
		setlocale(LC_ALL, 'ru_RU.UTF-8');	
		
		// Подключение к БД.
		$this->db = mysqli_connect(MYSQL_SERVER, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB) or die('No connect with data base'); 
		mysqli_query($this->db, 'SET NAMES UTF-8');
		//mysql_select_db(MYSQL_DB) or die('No data base');
	}
	
	//
	// Выборка строк
	// $query    	- полный текст SQL запроса
	// результат	- массив выбранных объектов
	//
	public function Select($query)
	{
		$result = mysqli_query($this->db, $query);
		
		if (!$result)
			die(mysqli_error($this->db));
		
		$n = mysqli_num_rows($result);
		$arr = array();
	
		for($i = 0; $i < $n; $i++)
		{
			$row = mysqli_fetch_assoc($result);		
			$arr[] = $row;
		}

		return $arr;				
	}
	
	//
	// Вставка строки
	// $table 		- имя таблицы
	// $object 		- ассоциативный массив с парами вида "имя столбца - значение"
	// результат	- идентификатор новой строки
	//
	public function Insert($table, $object)
	{			
		$columns = array();
		$values = array();
	
		foreach ($object as $key => $value)
		{
			$key = mysqli_real_escape_string($this->db, $key . '');
			$columns[] = $key;
            //print_r($value.'___');
			
			if (($value === null) || ($value === 'null'))
			{
				$values[] = 'NULL';
                print_r($value.'_set_');
			}
			else
			{
				$value = mysqli_real_escape_string($this->db, $value . '');							
				$values[] = "'$value'";
			}
		}
		
		$columns_s = implode(',', $columns);
		$values_s = implode(',', $values);
			
		$query = "INSERT INTO `$table` ($columns_s) VALUES ($values_s)";
        print_r($query);
		$result = mysqli_query($this->db, $query);
								
		if (!$result)
			die(mysqli_error($this->db));
			
		return mysqli_insert_id($this->db);
	}
	
	//
	// Изменение строк
	// $table 		- имя таблицы
	// $object 		- ассоциативный массив с парами вида "имя столбца - значение"
	// $where		- условие (часть SQL запроса)
	// результат	- число измененных строк
	//	
	public function Update($table, $object, $where)
	{
		$sets = array();
	
		/*foreach ($object as $key => $value)
		{
			$key = mysqli_real_escape_string($this->db, $key . '');
			
			if ($value === null)
			{
				$sets[] = "$key=NULL";			
			}
			else
			{
				$value = mysqli_real_escape_string($this->db, $value . '');					
				$sets[] = "$key='$value'";			
			}			
		}
		
		$sets_s = implode(',', $sets);*/			
		$query = "UPDATE $table SET $object WHERE $where";
        //print_r($query);
		$result = mysqli_query($this->db, $query);
		
		if (!$result)
			die(mysqli_error($this->db));

		return mysqli_affected_rows($this->db);	
	}
	
	//
	// Удаление строк
	// $table 		- имя таблицы
	// $where		- условие (часть SQL запроса)	
	// результат	- число удаленных строк
	//		
	public function Delete($table, $where)
	{
		$query = "DELETE FROM $table WHERE $where";
        //print_r($query);
		$result = mysqli_query($this->db, $query);
						
		if (!$result)
			die(mysqli_error($this->db));

		return mysqli_affected_rows($this->db);	
	}
}
