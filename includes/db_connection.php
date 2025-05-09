<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $servername = "localhost";
    $username = "root"; 
    $password = ""; 
    $dbname = "sistemaexamenes"; 

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    class connection{

        private static $server = "localhost";
        private static $user = "root"; 
        private static $pass = "root"; 
        private static $db = "sistemaexamenes"; 
    
    
        public static function get_connection() {
            $connection = mysqli_connect(
                self::$server,
                self::$user,
                self::$pass,
                self::$db);
                if ($connection === false){
                    echo 'could not connect to MySQL';
                    die;
                } else {
                    return $connection;
                }
        }
    }


?>
