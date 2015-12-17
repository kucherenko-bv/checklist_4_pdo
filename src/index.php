<?php

require __DIR__  . '/DB.php';

require __DIR__ . '/DBQuery.php';
/*
spl_autoload_register(function ($class) {
    include $class.'.php';
});
*/

$db = DB::connect('mysql:host=localhost;dbname=pdo','root','root');


$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Error Handling


$query = new DBQuery($db);


if ($query->tableExists('users')==false){
    $query->query("CREATE table users(
     ID INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
     Name VARCHAR( 250 ) NOT NULL,
     email VARCHAR( 150 ) NOT NULL,
     password VARCHAR( 150 ) NOT NULL);");
    $query->query("INSERT INTO users (Name, email, password) VALUES ('Name1', 'zotov1_mv@groupbwt.com', '1qazwsx')");
    $query->query("INSERT INTO users (Name, email, password) VALUES ('Name1', 'zotov2_mv@groupbwt.com', '2qazwsx')");
    $query->query("INSERT INTO users (Name, email, password) VALUES ('Name1', 'zotov3_mv@groupbwt.com', '3qazwsx')");
    $query->query("INSERT INTO users (Name, email, password) VALUES ('Name1', 'zotov4_mv@groupbwt.com', '4qazwsx')");
    $query->query("INSERT INTO users (Name, email, password) VALUES ('Name1', 'zotov5_mv@groupbwt.com', '5qazwsx')");
    $query->query("INSERT INTO users (Name, email, password) VALUES ('Name2', 'mail@groupbwt.com', 'hhhhhhh')");
    //var_dump($pdo->lastInsertId('pdotable'));
}

//print_r($query->queryAll('SELECT * FROM users'));

/**
 *  Array
    (
        [0] => Array
        (
            [id] => 1
            [email] => zotov_mv@groupbwt.com
            [password] => $2y$10$0MTsYsnMVJ7iq6bTvIpUGOK8WVN1uErud
        )

        [1] => Array
        (
            [id] => 2
            [email] => admin@groupbwt.com
            [password] => $2y$10$0MTsYsnMVJ7iq6bTvIpUGOK8WVN1uErud
        )
    )
 */


//print_r($query->queryRow('SELECT * FROM users limit 1'));

/**
 * Array
    (
        [id] => 1
        [email] => zotov_mv@groupbwt.com
        [password] => $2y$10$0MTsYsnMVJ7iq6bTvIpUGOK8WVN1uErud
    )
 */



//print_r($query->queryColumn('SELECT email FROM users'));
/**
    Array
    (
        [0] => zotov_mv+24787@groupbwt.com
        [1] => zotov_mv+47748@groupbwt.com
        [2] => zotov_mv@groupbwt.com
    )
 */


//echo $query->queryScalar('SELECT email FROM users');

/**
 * admin@groupbwt.com
 */
/*
$db->reconnect();


$data = [
    ':email' => 'zotov_mv+' . rand(1,99999) . '@groupbwt.com',
    ':password' => password_hash('qwerty' . time() ,PASSWORD_DEFAULT)
];

$rowCount = $query->execute("INSERT INTO users (email, password) VALUES (:email, :password)", $data);

echo "\ncount inserts row -> " . $rowCount . "\n";
*/
$lastId = $db->getLastInsertID();

//print_r($query->queryRow('SELECT * FROM users where id = :id', ['id' => $lastId]));

/**
  Array
    (
        [id] => 20
        [email] => zotov_mv+70773@groupbwt.com
        [password] => $2y$10$m7ai3oLBxbF4akWMLXEDteF.0zbv6deN0
    )
 */

/*
$updateData = [
    'password' => password_hash('qwerty' . time() ,PASSWORD_DEFAULT),
    'id' => $lastId
];

$rowCountUpdate = $query->execute("Update users SET password = :password where id = :id", $updateData);

echo "\ncount update row -> " . $rowCountUpdate . "\n";


$rowCountDelete = $query->execute("DELETE FROM users where id = :id", ['id' => $lastId]);

echo "\ncount delete row -> " . $rowCountDelete . "\n";


echo "\nlast query execution time -> ".$query->getLastQueryTime() . "\n";
*/