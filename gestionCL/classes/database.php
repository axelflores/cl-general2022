<?php

class db
{

    // used to connect to the database
    private $dbHost = 'www.lacasadelasluces.com.mx';
    private $dbUser = 'lacasade_production2021';
    private $dbPass = 'casadelasluces';
    private $dbName = 'lacasade_sistema_general';

    // get the database connection
    public function conectDB()
    {
        try {
          $mysqlConnect = "mysql:host=$this->dbHost;dbname=$this->dbName;charset=UTF8";
          $dbConnection = new PDO($mysqlConnect, $this->dbUser, $this->dbPass);
          $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Database Connection Error: " . $exception->getMessage();
        }
        return $dbConnection;
    }
}
