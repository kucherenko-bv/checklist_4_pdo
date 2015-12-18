<?php

require __DIR__  . '/DBQueryInterface.php';

class DBQuery implements DBQueryInterface
{

    /**
     * @var PDO
     */
    private $connection;

    /**
     * Create new instance DBQuery.
     *
     * @param DBConnectionInterface $DBConnection
     */
    public function __construct(DBConnectionInterface $DBConnection){
        $this->setDBConnection($DBConnection);
    }

    /**
     * Returns the DBConnection instance.
     *
     * @return DBConnectionInterface
     */
    public function getDBConnection(){
        return $this->connection;
    }

    /**
     * Change DBConnection.
     *
     * @param DBConnectionInterface $DBConnection
     *
     * @return void
     */
    public function setDBConnection(DBConnectionInterface $DBConnection){
        $this->connection = $DBConnection->getPdoInstance();
    }

    /**
     * Executes the SQL statement and returns query result.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return mixed if successful, returns a PDOStatement on error false
     */
    public function query($query,array $params = null){
        try {
            $this->getDBConnection()->query('set profiling=1');
            $sth = $this->getDBConnection()->prepare($query);
            $sth->execute($params);
            return $sth;
        } catch (Exception $e) {
            echo 'Error: ',  $e->getMessage(), "\n";
            return false;
        }
    }


    /**
     * Executes the SQL statement and returns all rows of a result set as an associative array
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return array
     */
    public function queryAll($query, array $params = null){
        $var = $this->query($query, $params);
        if($var !=false) return $var->fetchAll(PDO::FETCH_ASSOC);
        else return false;
    }

    /**
     * Executes the SQL statement returns the first row of the query result
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return array
     */
    public function queryRow($query, array $params = null){
        $var = $this->query($query, $params);
        if($var !=false) return $var->fetch(PDO::FETCH_ASSOC);
        else return false;
    }

    /**
     * Executes the SQL statement and returns the first column of the query result.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return array
     */
    public function queryColumn($query, array $params = null){
        $var = $this->query($query, $params);
        $res = array();
        if($var !=false){
            foreach ($var->fetchAll(PDO::FETCH_NUM) as $arr) {
                $res[] = $arr[0];
            }
            return $res;
        }
        else return false;
    }


    /**
     * Executes the SQL statement and returns the first field of the first row of the result.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return mixed  column value
     */
    public function queryScalar($query, array $params = null){
        $var = $this->query($query, $params);
        if($var !=false)  return $var->fetchColumn();
        else return false;
    }

    /**
     * Executes the SQL statement.
     * This method is meant only for executing non-query SQL statement.
     * No result set will be returned.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return integer number of rows affected by the execution.
     */
    public function execute($query, array $params = null){
        return $this->query($query, $params)->rowCount();
    }


    /**
     * Returns the last query execution time in seconds
     *
     * @return float query time in seconds
     */
    public function getLastQueryTime(){
        $var = $this->getDBConnection()->query('SELECT query_id, SUM(duration) AS duration
              FROM information_schema.profiling GROUP BY query_id ORDER BY query_id DESC LIMIT 1 ');
        while($res = $var->fetch())
        {
            //print 'ID:'.$b['query_id'].'; Duration: '.$b['duration'].' seconds';
            return $res['duration'];
        }
    }


    /**
     * Check if a table exists in the current database.
     *
     * @param string $table_name Table to search for.
     * @return bool TRUE if table exists, FALSE if no table found.
     */
    function tableExists($table_name) {
        try {
            $this->getDBConnection()->query("SELECT 1 FROM $table_name LIMIT 1");
        } catch (Exception $e) {
            // We got an exception == table not found
            return FALSE;
        }
        return true;
    }

}