<?php
class RegisterUser
{
    private $username;
    private $email;
    private $raw_password;
    private $encrypted_password;
    public $error;
    public $success;
    private $db_path = "db/users.json";
    private $db_path_2 = "db/reviews.json";
    private $new_user_for_review;
    private $stored_users_for_reviews;
    private $stored_users;
    private $new_user;


    public function __construct($username, $raw_password, $email)
    {

        $this->username = trim($username);
        $this->username = filter_var($username, FILTER_SANITIZE_STRING);

        $this->email = filter_var(trim($email), FILTER_VALIDATE_EMAIL);

        $this->raw_password = filter_var(trim($raw_password), FILTER_SANITIZE_STRING);

        $this->encrypted_password = password_hash($this->raw_password, PASSWORD_DEFAULT);

        $this->stored_users = json_decode(file_get_contents($this->db_path), true);


        $this->new_user = [
            "username" => $this->username,
            "password" => $this->encrypted_password,
            "email" => $this->email
        ];


        $this->stored_users_for_reviews = json_decode(file_get_contents($this->db_path_2), true);

        $this->new_user_for_review = [
            "username" => $this->username,
            "email" => $this->email,
            "books_read" => [],
            "reviews" => [],
            "rating" => []
        ];

        if ($this->checkFieldValues()) {
            $this->insertUsername();
        }
    }


    private function checkFieldValues()
    {
        if (empty($this->username) || empty($this->raw_password) || empty($this->email)) {
            $this->error = "All fields are required!";
            // if (filter_var(trim($this->email), FILTER_VALIDATE_EMAIL) == false && !empty($this->email)) {
            //     $this->error .= "\n\nEnter a valid email address!";
            // }
            return false;
        }

        return true;
    }

    private function usernameExists()
    {
        foreach ($this->stored_users as $user) {
            if ($this->username == $user['username']) {
                $this->error = "Username already exists, please choose different one!";
                return true;
            }
        }
        return false;
    }

    private function insertUsername()
    {
        if ($this->usernameExists() == FALSE) {
            array_push($this->stored_users, $this->new_user);
            array_push($this->stored_users_for_reviews, $this->new_user_for_review);
            if (file_put_contents($this->db_path, json_encode($this->stored_users, JSON_PRETTY_PRINT))) {
                if (file_put_contents($this->db_path_2, json_encode($this->stored_users_for_reviews, JSON_PRETTY_PRINT))) {

                    require("php_classes/login.class.php");

                    if (isset($_POST['submit'])) {
                        $new_user = new LoginUser($this->username, $this->raw_password);
                        return $this->success = "Successful registration!";
                    }
                }
            } else {
                return $this->error = "Something went wrong, please try again!";
            }
        }
    }
}
