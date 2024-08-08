<?php
class DefaultSecuritySetting{
    private static $obj;
    private function __construct()
    {
        # default enabled, use cookie to store session ID
        ini_set('session.use_only_cookies', 1);
        ini_set('session.use_strict_mode', 1);

        session_set_cookie_params([
            'lifetime' => 1800,
            'domain' => 'localhost',
            'path' => '/',
            'secure' => true,
            'httponly' => true
        ]);

        session_start();

        if (!isset($_SESSION['last_regeneration'])) {
            #renew a session id after some amount of time
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        } else {
            $interval = 60 * 30;
            if (time() - $_SESSION['last_regeneration'] >= $interval) {
                $email = $_SESSION['email'];
                $password = $_SESSION['password'];
                session_regenerate_id(true);
                $_SESSION['last_regeneration'] = time();
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
            }
        }
    }

    public static function GetObject(){
        if(self::$obj == null){
            self::$obj = new DefaultSecuritySetting();
        }
        return self::$obj;
    }
}


