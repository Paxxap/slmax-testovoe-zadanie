<?php 


class People_DB
{ 
	private $id, $name, $surname, $birthday, $sex, $city_of_birth;


	function __construct($array)
	{
		$count = count($array);

		switch($count)
		{
			case 1: 
				if(isset($array["id"]) && gettype($array["id"]) == 'integer')
				{
					$id = $array["id"];
					$connection = $this->bd_connect();
					$sql = "SELECT * FROM first_task WHERE id = $id";
					$result = $connection->query($sql);
					if($result->num_rows == 0) {
						die ('Ошибка: пользователь с таким id не найден');
					}
					elseif($result = $connection->query($sql)) {
	    				foreach($result as $row) {
	        				$this->id = $row['id'];
	        				$this->name = $row['name'];
	        				$this->surname = $row['surname'];
	        				$this->birthday = $row['date_of_birth'];
	        				$this->sex = $row['sex'];
	        				$this->city_of_birth = $row['city'];
	    				}
	    			}
					$connection->close();
					break;
				}
				else {
					die ('Ошибка: id введен неверно');
				}
			case 6:
				if($this->validation($array)) {
					$this->id = $array['id']; 
					$this->name = $array['name']; 
					$this->surname = $array['surname']; 
					$this->birthday = date('Y-m-d', mktime(0, 0, 0, $array['birthday'][0], $array['birthday'][1], $array['birthday'][2]));
					$this->sex = $array['sex']; 
					$this->city_of_birth = $array['city_of_birth']; 	
					break;
				}
				else {
					die ('Ошибка: заданные поля некорректны');
				}		
			default:
				die('Ошибка: неверное количество параметров');
		}
	}


	public function save()
	{
		$connection = $this->bd_connect();

		$sql = "INSERT INTO first_task (id, name, surname, date_of_birth, sex, city) VALUES 
		($this->id, 
		'$this->name',
		'$this->surname',
		'$this->birthday',
	     $this->sex,
		'$this->city_of_birth')";

		if($connection->query($sql)) {
    		echo 'Данные успешно добавлены';
		} 
		else {
    		echo 'Ошибка: ' . $connection->error;
		}

		$connection->close();
	}

	public function delete($id)
	{
		$connection = $this->bd_connect();

		$sql = "DELETE FROM first_task WHERE id=$id";

		if($connection->query($sql)) {
    		echo 'Данные успешно удалены';
		} 
		else {
    		echo 'Ошибка: ' . $connection->error;
		}

		$connection->close();
	}

	public static function age($person)
	{
		$age = date('Ymd') - date('Ymd', strtotime($person->birthday));

		return substr($age, 0, 2);
	}

	public static function string_sex($person)
	{ 
		$string = ' ';
		if($person->sex) { 
			$string = 'man';
		}
		else { 
			$string = 'woman';
		}
		return $string;
	}

	public function formatting($person, $age = false, $string_sex = false)
	{
		if($age) {
			$this->birthday = People_DB::age($person);
		}
		if ($string_sex) {
			$this->sex = People_DB::string_sex($person);
		}
		$object_new = (object) array(
			'id'=>$this->id, 
			'name'=>$this->name, 
			'surname'=>$this->surname, 
			'birthday'=>$this->birthday, 
			'sex'=>$this->sex, 
			'city_of_birth'=>$this->city_of_birth,
		);
		return $object_new;
	}

	public function bd_connect()
	{ 
		$connection = new mysqli('localhost', 'root', 'root', 'test_bd');

		if($connection->connect_error) {
    		die('Ошибка: ' . $connection->connect_error);
		}
		
		return $connection;
	}

	public function validation($array)
	{
		if(isset($array["id"], $array["name"], $array["surname"], $array["birthday"], $array["sex"], $array["city"])) {
			if(gettype($array["id"]) != 'integer' 
				|| gettype($array["name"]) != 'string'
				|| gettype($array["surname"]) != 'string'
				|| gettype($array["city"]) != 'string'
				|| gettype($array["sex"]) != 'boolean' 
				|| gettype($array["birthday"]) != 'array'
				|| count($array["birthday"]) != 3 
				|| ctype_alpha($array["name"]) == false 
				|| ctype_alpha($array["surname"]) == false) {
				return false;
			}
			$true_date = checkdate($array["birthday"][0], $array["birthday"][1], $array["birthday"][2]);
			if ($true_date == false) {
				return false;
			}
				else {
				return true;
			}
		}
	}
} 