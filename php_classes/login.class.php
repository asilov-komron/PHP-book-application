<?php 


class LoginUser{
    private $username;
    private $password;
    private $login_time;
    public $error;
    public $success;
    private $db_path = "db/users.json";
    private $stored_users;


    public function __construct($uname, $psswd) {
        $this->username = filter_var(trim($uname), FILTER_SANITIZE_STRING);
        $this->password = filter_var(trim($psswd), FILTER_SANITIZE_STRING);

        $this->stored_users = json_decode(file_get_contents($this->db_path), true);

        $this->login();
    }










    private function login(){
        foreach ($this->stored_users as $user) {
            if ($this->username == $user['username']) {
                if(password_verify($this->password, $user['password'])){
                    $this->success = "Successfull login!";

                    session_start();
                    $_SESSION['user'] = $this->username;
                    header("location: index.php"); exit();
                }
            }
        }

        if(empty($this->username) || empty($this->password)){
            return $this->error = "Both fields are required!";
        }

        return $this->error = "Wrong username or password, please try again!";

    }



}







?>