<?php 

include 'peopleDB_class.php';
include 'peopleWorklist_class.php';

$connection = new mysqli('localhost', 'root', 'root');

if($connection->connect_error){
    die('Ошибка: ' . $connection->connect_error);
}

echo 'Подключение успешно установлено';

$connection->close();


$id = 1;
$name = 'Tom';
$surname = 'Drow';
$birthday = array(12, 4, 1971); 
$sex = true; 
$city = 'Denver';


$array = array(
    "id" => $id,
    "name" => $name, 
    "surname" => $surname, 
    "birthday" => $birthday,
    "sex" => $sex,
    "city" => $city
);


$person = new People_DB($array);
$person-> save();
$person-> delete(2);
$person_age = People_DB::age($person);
$person_sex = People_DB::string_sex($person);
$new_object = $person->formatting($person, true, true);


$worklist = new WorkList(1, 2);
$arr = $worklist->get_array_of_people();
$worklist->delete_people();