<?php
abstract class user{
    private string $name;
    protected string $email;
    public abstract function sendEmail($message);
    public function setName($name){
        $this->name = $name;
    }
    public function getName($name){
        return $this->name;
    }
    public function register(){
        //code
    }
    public function login(){
        //code
    }
} 
class Admin extends user{
    public function sendemail($message){
        echo $this->email;
    }
}
?>