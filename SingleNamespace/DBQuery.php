<?php
/**
 * Created by PhpStorm.
 * User: kucherenko_bv
 * Date: 18.12.2015
 * Time: 11:16
 * Version: 1.0
 */

namespace SingleNamespace;

use DBConnectionInterface;
use DBQueryInterface;
use PDO;
use Exception;

require __DIR__  . '/DBQueryInterface.php';

class DBQuery implements DBQueryInterface
{

    /**
     * @var PDO
     */
    private $connection;
    private $lastQueryTime;

    /**
     * Create new instance DBQuery.
     *
     * @param DBConnectionInterface $DBConnection
     */
    public function __construct(DBConnectionInterface $DBConnection)
    {
        $this->setDBConnection($DBConnection);
    }

    /**
     * Returns the DBConnection instance.
     *
     * @return DBConnectionInterface
     */
    public function getDBConnection()
    {
        return $this->connection;
    }

    /**
     * Change DBConnection.
     *
     * @param DBConnectionInterface $DBConnection
     *
     * @return void
     */
    public function setDBConnection(DBConnectionInterface $DBConnection)
    {
        $this->connection = $DBConnection;
    }

    /**
     * Returns the PDO instance.
     *
     *
     * @return PDO instance
     */
    public function getPDO()
    {
        return $this->connection->getPdoInstance();
    }

    /**
     * Executes the SQL statement and returns query result.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return mixed if successful, returns a PDOStatement on error false
     */
    public function query($query,array $params = null)
    {
        try {
            //$this->getPDO()->query('set profiling=1');
            $this->lastQueryTime['start'] = microtime(true);
            $sth = $this->getPDO()->prepare($query);
            $sth->execute($params);
            $this->lastQueryTime['end'] = microtime(true);
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
    public function queryAll($query, array $params = null)
    {
        $queryAll = $this->query($query, $params);
        if($queryAll !=false){
            return $queryAll->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    /**
     * Executes the SQL statement returns the first row of the query result
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return array
     */
    public function queryRow($query, array $params = null)
    {
        $queryRow = $this->query($query, $params);
        if($queryRow !=false) {
            return $queryRow->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    /**
     * Executes the SQL statement and returns the first column of the query result.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return array
     */
    public function queryColumn($query, array $params = null)
    {
        $queryCol = $this->query($query, $params);
        if($queryCol !=false) {
            return $queryCol->fetchAll(PDO::FETCH_COLUMN);
        } else {
            return false;
        }
    }

    /**
     * Executes the SQL statement and returns the first field of the first row of the result.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return mixed  column value
     */
    public function queryScalar($query, array $params = null)
    {
        $queryScalar = $this->query($query, $params);
        if($queryScalar !=false) {
            return $queryScalar->fetchColumn();
        } else {
            return false;
        }
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
    public function execute($query, array $params = null)
    {
        return $this->query($query, $params)->rowCount();
    }

    /**
     * Returns the last query execution time in seconds
     *
     * @return float query time in seconds
     */
    public function getLastQueryTime()
    {
        return number_format(($this->lastQueryTime['end'] - $this->lastQueryTime['start']), 8);
    }

    /**
     * Check if a table exists in the current database.
     *
     * @param string $table_name Table to search for.
     * @return bool TRUE if table exists, FALSE if no table found.
     */
    public function tableExists($table_name)
    {
        try {
            $this->getPDO()->query("SELECT 1 FROM $table_name LIMIT 1");
        } catch (Exception $e) {
            return FALSE;
        }
        return true;
    }

}