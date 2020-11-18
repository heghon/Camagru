<?php
class Database{

    private $pdo;

    public function __construct() 
    {
        require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "database.php";

        $this->pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }

    /**
     * @param $query
     * @param bool|array $params
     * @return PDOStatement
     */
    public function query($query, $params = false) {
        if($params) {
            $request = $this->pdo->prepare($query);
            $request->execute($params);    
        }
        else {
            $request = $this->pdo->query($query);
        }
        return $request;
    }

    public function lastInsertedId() {
        return $this->pdo->lastInsertId();
    }

}