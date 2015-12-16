<?php

spl_autoload_register(function ($class) {
    include $class.'.php';
});

//define('DB_HOST','localhost');
//define('DB_NAME','pdo');
//define('DB_USER','root');
//define('DB_PASS','root');
//$dsn ='mysql:host='.DB_HOST.';dbname='.DB_NAME;

$DBConnection = new DBConnection();
$pdo = $DBConnection->connect('mysql:host=localhost;dbname=pdo','root','root');
$DBConnection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );  //Error Handling
//$DBConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES,TRUE);

//print_r($DBConnection->getLastInsertID());

$DBQuerry = new DBQuery($DBConnection);

if ($DBQuerry->tableExists('pdotable')==false){
    $DBQuerry->query("CREATE table pdotable(
     ID INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
     Name VARCHAR( 250 ) NOT NULL,
     Param1 VARCHAR( 150 ) NOT NULL,
     Param2 VARCHAR( 150 ) NOT NULL,
     Param3 VARCHAR( 150 ) NOT NULL);");
    $DBQuerry->query("INSERT INTO pdotable (Name, Param1, Param2, Param3) VALUES ('Name1', '1', '2', '3')");
    $DBQuerry->query("INSERT INTO pdotable (Name, Param1, Param2, Param3) VALUES ('Name1', '4', '5', '6')");
    $DBQuerry->query("INSERT INTO pdotable (Name, Param1, Param2, Param3) VALUES ('Name1', '7', '8', '9')");
    $DBQuerry->query("INSERT INTO pdotable (Name, Param1, Param2, Param3) VALUES ('Name1', '10', '11', '12')");
    $DBQuerry->query("INSERT INTO pdotable (Name, Param1, Param2, Param3) VALUES ('Name1', '13', '14', '15')");
    $DBQuerry->query("INSERT INTO pdotable (Name, Param1, Param2, Param3) VALUES ('Name2', '16', '17', '18')");
    var_dump($pdo->lastInsertId('pdotable'));
}

//print_r($DBQuerry->execute('SELECT Param1, Param2, Param3 FROM mytable WHERE name = :var_name', $params = [':var_name' => 'Name3'] ));
//print_r($DBQuerry->queryAll('SELECT Param1, Param2, Param3 FROM mytable WHERE name = :var_name', $params = [':var_name' => 'Name3'] ));
//print_r($DBQuerry->queryRow('SELECT Param1, Param2, Param3 FROM mytable WHERE name = :var_name', $params = [':var_name' => 'Name3'] ));
//print_r($DBQuerry->queryColumn('SELECT Param1, Param2, Param3 FROM mytable WHERE name = :var_name', $params = [':var_name' => 'Name3'] ));
//print_r($DBQuerry->queryScalar('SELECT Param1, Param2, Param3 FROM mytable WHERE name = :var_name', $params = [':var_name' => 'Name3'] ));
//print_r($DBQuerry->getLastQueryTime());

var_dump($pdo->lastInsertId('pdotable'));