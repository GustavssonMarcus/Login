<?php

require_once ('UserDatabase.php');


class DBContext
{


    private $pdo;
    private $usersDatabase;

    function getUsersDatabase()
    {
        return $this->usersDatabase;
    }

    function __construct()
    {
        $host = $_ENV['host'];
        $db = $_ENV['db'];
        $user = $_ENV['user'];
        $pass = $_ENV['pass'];
        $dsn = "mysql:host=$host;dbname=$db";
        $this->pdo = new PDO($dsn, $user, $pass);
        $this->usersDatabase = new UserDatabase($this->pdo);
        $this->initIfNotInitialized();
    }

    function updateCustomer($Id, $email, $password, $repeatPassword, $name, $street, $postal, $city)
    {
        $prep = $this->pdo->prepare("UPDATE UserDetails SET
            email=:email, password=:password, repeat_password=:repeat_password, 
            name=:name, street=:street, postal=:postal, city=:city,                        
            WHERE Id=:Id;
        ");
        $prep->execute([
            "email" => $email,
            "password" => $password,
            "repeat_password" => $repeatPassword,
            "name" => $name,
            "street" => $street,
            "postal" => $postal,
            "city" => $city,
            "Id" => $Id
        ]);

    }

    function initIfNotInitialized()
    {

        static $initialized = false;
        if ($initialized)
            return;


        $sql = "CREATE TABLE IF NOT EXISTS `UserDetails` (
            `Id` int NOT NULL AUTO_INCREMENT,
            `email` varchar(50) NOT NULL,
            `password` varchar(50) NOT NULL,
            `repeat_password` varchar(50) NOT NULL,
            `name` varchar(50) NOT NULL,
            `street` varchar(10) NOT NULL,
            `postal` varchar(30) NOT NULL,
            `city` varchar(50) NOT NULL,
            `userId` INT NOT NULL,
            PRIMARY KEY (`Id`),
            FOREIGN KEY (`userId`)
                REFERENCES users(id)
          ) ";

        $this->pdo->exec($sql);

        $this->usersDatabase->setupUsers();
        $this->usersDatabase->seedUsers();

        $initialized = true;
    }

}


?>