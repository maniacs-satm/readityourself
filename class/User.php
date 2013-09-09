<?php 

class User
{
    private $login = null;
    private $password = null;
    private $name = null;
    private $surname = null;

    public function getLogin() {
        return $this->login;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getSurname() {
        return $this->surname;
    }

    public function setSurname($surname) {
        $this->surname = $surname;
    }
    
    public function save($path) {
        if(is_dir($path)) {
            $user = serialize($this);
            // save user in path with login as filename
            file_put_contents($path.'/'.$this->login.'.user', $user);
        } else {
            return false;
        }
    }

    public static function getHashPassword($password) {
        return md5($password);
    }

    public static function getUser($path, $login) {
        if(file_exists($path.'/'.$login.'.user')) {
            // read user in path with login as filename
            $user = file_get_contents($path.'/'.$login.'.user');
            return unserialize($user);
        } else {
            return false;
        }
    }
}
