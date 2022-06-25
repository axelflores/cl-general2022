<?php
  class db{
      //Variables
      private $dbHost = 'casadelasluces.com';
      private $dbUser = 'wwcasa_desarrollo2022';
      private $dbPass = 'desarrollo2022';
      private $dbName = 'wwcasa_general_2022';

      //Conexión
      public function conectDB(){
        $mysqlConnect = "mysql:host=$this->dbHost;dbname=$this->dbName";
        $dbConnection = new PDO($mysqlConnect, $this->dbUser, $this->dbPass);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbConnection;
      }
  }
?>
