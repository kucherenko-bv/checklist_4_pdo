<?php

spl_autoload_register(function ($class) {
    include 'vendor/'.$class . '.class.php';
});


define('DB_HOST','localhost');
define('DB_NAME','pdo');
define('DB_USER','root');
define('DB_PASS','root');
$dsn ='mysql:host='.DB_HOST.';dbname='.DB_NAME;

$table_name = 'table_test';


$singleton_db = new singleton_db($dsn, DB_USER, DB_PASS, array());
$singleton_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );  //Error Handling

if(tableExists($singleton_db, $table_name) == false){

    $sql ="CREATE table $table_name(
     ID INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
     Name VARCHAR( 250 ) NOT NULL,
     Param1 VARCHAR( 150 ) NOT NULL,
     Param2 VARCHAR( 150 ) NOT NULL,
     Param3 VARCHAR( 150 ) NOT NULL);";
    $singleton_db->exec($sql);

    $singleton_db->exec("INSERT INTO $table_name (Name, Param1, Param2, Param3) VALUES ('Singleton PDO', '1', '2', '3')");

    print("Table $table_name was created.\n");
}
else print("Table $table_name exist.\n");

print_r($singleton_db->queryFetchAllAssoc("SELECT * from table_test")[0]);




/**
 * Check if a table exists in the current database.
 *
 * @param PDO $singleton_db PDO instance connected to a database.
 * @param string $table_name Table to search for.
 * @return bool TRUE if table exists, FALSE if no table found.
 */
function tableExists($singleton_db, $table_name) {

    // Try a select statement against the table
    // Run it in try/catch in case PDO is in ERRMODE_EXCEPTION.
    try {
        $singleton_db->query("SELECT 1 FROM $table_name LIMIT 1");
    } catch (Exception $e) {
        // We got an exception == table not found
        return FALSE;
    }
    // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
    return true;
}