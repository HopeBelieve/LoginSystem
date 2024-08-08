<?php
class pdo_db_connection{
    private static $db_type_info = "mysql:";
    #IP addres of Host
    private static $db_host_info = "host=";
    private $db_host;
    #name of database
    private static $db_name_info = ";dbname=";
    private $db_name = "";
    private $username = "";
    private $password = "";
    private $conn;
    private static $check_any_user_existance = "SELECT COUNT(*) AS count FROM LoginInfo;";
    private static $check_user_existance = "SELECT COUNT(*) AS count FROM LoginInfo WHERE email = :UserEmail;";
    private static $get_user_name_info = "SELECT name FROM LoginInfo WHERE id = :UserId;";
    private static $write_new_user_into_db = "INSERT INTO LoginInfo (name, email, password) VALUES (:Name, :Email, :Password)";
    private static $try_login_user = "SELECT * FROM LoginInfo WHERE email = :Email; AND password = :Password;";

    function __construct($db_host_, $db_name_, $username_, $password_){
        $this->db_host = $db_host_;
        $this->db_name = $db_name_;
        $this->username = $username_;
        $this->password = $password_;
        try {
            $this->conn = new PDO(self::$db_type_info . self::$db_host_info . $this->db_host . self::$db_name_info . $this->db_name, $this->username, $this->password);
        }catch(PDOException $e){
            echo "Error" . $e->getMessage();
            exit();
        }
        #echo "Successfully connected to database";
        #echo "<br>";
    }
    #try to register a user
    function RegisterUser($name, $email, $password)
    {
        $sth = $this->conn->prepare(self::$check_user_existance);
        $sth->execute(["UserEmail" => $email]);
        $sth = $sth->fetch(PDO::FETCH_ASSOC);
        if($sth["count"] != 0){
            echo "User already exists";
            echo "<br>";
        }else{
            $sth = $this->conn->prepare(self::$write_new_user_into_db);
            $sth->execute(["Name" => $name, "Email" => $email, "Password" => $password]);
            var_dump($sth);
            echo "Successfully registered user";
            echo "<br>";
        }
    }

    #try to login a user
    function LoginUser($email, $password)
    {
        $sth = $this->conn->prepare(self::$try_login_user);
        $sth->execute(["Email" => $email, "Password" => $password]);
        $sth = $sth->fetch(PDO::FETCH_ASSOC);
        return $sth;
    }
    function testFunc(){
        $sth = $this->conn->prepare(self::$check_any_user_existance);
        $sth->execute();
        $res = $sth->fetchAll(PDO::FETCH_ASSOC);
        echo $res[0]["count"];
        echo "<br>";
    }
    function __destruct(){
        $this->conn = null;
    }

}




