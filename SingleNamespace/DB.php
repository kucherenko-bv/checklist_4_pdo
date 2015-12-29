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
use PDO;
use PDOException;

require __DIR__  . '/DBConnectionInterface.php';

class DB implements DBConnectionInterface
{
    /**
     * @var PDO
     */

    static private $Singleton;  // Instance of DB class
    static private $InstanceArr = array();  // Array of DB class instances

    private $conParam = array();    // Array of connection parameters
    private $PDOInstance;   // PDO instance

    /**
     * Creates new instance representing a connection to a database
     * For one user there isn't more than one conection to a database.
     *
     * @param string $dsn The Data Source Name, or DSN, contains the information required to connect to the database.
     *
     * @param string $username The user name for the DSN string.
     * @param string $password The password for the DSN string.
     * @see http://www.php.net/manual/en/function.PDO-construct.php
     * @throws  PDOException if the attempt to connect to the requested database fails.
     *
     * @return $this DB
     */
    public static function connect($dsn, $username = '', $password = '')
    {
        $conHash = self::currentConnectionHash($dsn, $username);
        if(array_key_exists($conHash, self::$InstanceArr)) {
            self::$Singleton = self::$InstanceArr[$conHash];
            return self::$Singleton;
        } else {
            try {
                self::$Singleton = new self;
                self::$Singleton->PDOInstance = new PDO($dsn, $username, $password);
                self::$Singleton->conParam = ['dsn' => $dsn, 'username' => $username, 'pass' => $password];
                self::$InstanceArr[$conHash] = self::$Singleton;
                return  self::$Singleton;
            } catch (PDOException $e) {
                print("PDO CONNECTION ERROR: " . $e->getMessage() . "<br/>");
                throw $e;
            }
        }
    }

    /**
     * Save hash of current dsn and username
     *
     * @param string $dsn The Data Source Name, or DSN, contains the information required to connect to the database.
     * @param string $username The user name for the DSN string.
     *
     * @return $this->currentConnectionHash
     */
    private function currentConnectionHash($dsn, $username = '')
    {
        return hash('md5',$username.$dsn);
    }

    /**
     * Completes the current session connection, and creates a new.
     *
     * @return void
     */
    public function reconnect()
    {
        $this->close();
        try {
            $this->PDOInstance = new PDO ($this->conParam['dsn'], $this->conParam['username'], $this->conParam['pass']);
        } catch (PDOException $e) {
            print("PDO CONNECTION ERROR: " . $e->getMessage() . "<br/>");
        }
    }

    /**
     * Protection from cloning.
     * Can be empty.
     *
     * @return void
     */
    private function __clone()
    {

    }

    /**
     * Returns the PDO instance.
     *
     * @return PDO the PDO instance, null if the connection is not established yet
     */
    public function getPdoInstance()
    {
        if($this->PDOInstance) {
            return $this->PDOInstance;
        } else {
            return null;
        }
    }

    /**
     * Returns the ID of the last inserted row or sequence value.
     *
     * @param string $sequenceName name of the sequence object (required by some DBMS)
     *
     * @return string the row ID of the last row inserted, or the last value retrieved from the sequence object
     * @see http://www.php.net/manual/en/function.PDO-lastInsertId.php
     */
    public function getLastInsertID($sequenceName = '')
    {
        return $this->getPdoInstance()->lastInsertId($sequenceName);
    }

    /**
     * Closes the currently active DB connection.
     * It does nothing if the connection is already closed.
     *
     * @return void
     */
    public function close()
    {
        if($this->PDOInstance) {
            $this->PDOInstance = null;
        }
    }

    /**
     * Sets an attribute on the database handle.
     * Some of the available generic attributes are listed below;
     * some drivers may make use of additional driver specific attributes.
     *
     * @param int $attribute
     * @param mixed $value
     *
     * @return bool
     * @see http://php.net/manual/en/pdo.setattribute.php
     */
    public function setAttribute($attribute, $value)
    {
       return $this->getPdoInstance()->setAttribute($attribute, $value);
    }

    /**
     * Returns the value of a database connection attribute.
     *
     * @param int $attribute
     *
     * @return mixed
     * @see http://php.net/manual/en/pdo.setattribute.php
     */
    public function getAttribute($attribute)
    {
        return $this->getPdoInstance()->getAttribute($attribute);
    }

    /**
     * Close connection.
     *
     * @return void
     */
    public function __destruct()
    {
        $this->close();
    }

}
