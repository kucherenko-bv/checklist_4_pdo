<?php

//include('pdo_2.php');
//$db = dbConn::getConnection();
// ***** //

include('pdo_singleton.php');

define('DB_HOST','localhost');
define('DB_NAME','pdo');
define('DB_USER','root');
define('DB_PASS','root');
$dsn ='mysql:host='.DB_HOST.';dbname='.DB_NAME;

try {
    $singleton_db = new singleton_db($dsn, DB_USER, DB_PASS, array());
    $singleton_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );  //Error Handling

    if(tableExists($singleton_db, 'table_test') == false){
        $sql ="CREATE table table_test(
         ID INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
         Name VARCHAR( 250 ) NOT NULL,
         Param1 VARCHAR( 150 ) NOT NULL,
         Param2 VARCHAR( 150 ) NOT NULL,
         Param3 VARCHAR( 150 ) NOT NULL);";
        $singleton_db->exec($sql);

        $sql = "INSERT INTO table_test (Param1, Param2) VALUES ('1', '2')";
        $singleton_db->exec($sql);

        print("Table was created.\n");
    }
    else print("Table exist.\n");

    //$result = $singleton_db->query("SELECT Param1 FROM table_test");

    $sql = 'SELECT * from table_test';

    $result = $singleton_db->queryFetchAllAssoc($sql);

    echo '<pre>' . print_r($result[0], true) . '</pre>'; exit;



} catch(PDOException $e) {
    echo $e->getMessage();  //Remove or change message in production code
}






/**
 * Check if a table exists in the current database.
 *
 * @param PDO $pdo PDO instance connected to a database.
 * @param string $table Table to search for.
 * @return bool TRUE if table exists, FALSE if no table found.
 */
function tableExists($singleton_db, $table_name) {

    // Try a select statement against the table
    // Run it in try/catch in case PDO is in ERRMODE_EXCEPTION.
    try {
        $result = $singleton_db->query("SELECT 1 FROM $table_name LIMIT 1");
    } catch (Exception $e) {
        // We got an exception == table not found
        return FALSE;
    }
    // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
    return $result = true;
}