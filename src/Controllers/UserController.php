<?php

namespace App\Controllers;

use \Core\View;
use \Core\AbstractController;

use App\Entity\Users;
use App\Entity\Messages;

class UserController extends AbstractController
{
    private $users;
    private $messages;

    public function __construct() 
    {
        $this->users = new Users();
        $this->messages = new Messages();
    }

    public function signinAction()
    {
        if(isset($this->getSession()->iduser)){
            header('Location: /tchat/');
            die();
        }
        View::renderView('user/signin.html');
    }

    public function loginAction()
    {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        
        if(!empty($username) && !empty($password)){ 
            $getUser = $this->users->getUser($username,$password);
            
            if(!empty($getUser)){
                $_SESSION['iduser']  = $getUser->id;
                $_SESSION['username'] = $getUser->username; 
                $this->users->setConnected($getUser->id);
                $this->messages->setSentAllMsg($getUser->id); 
                header("location: /tchat");
            }else{
                header("location: /tchat/signin");
            }
        }
    }
    
    public function disconnectAction()
    {
        if(isset($this->getSession()->iduser)){ 
            $setDisconnected = $this->users->setDisconnected($this->getSession()->iduser);

            if($setDisconnected){
                session_destroy();
                header("location: /tchat/signin");
            }
        }
    }

    public function signupAction()
    {
        View::renderView('user/signup.html');
    }

    public function createAccountAction()
    {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirmation_password']);

        if($password == $confirm_password){
            $this->users->setUsername($username);
            $this->users->setEmail($email);
            $this->users->setPassword($password);
            $this->users->createAccount();

            header('Location: /tchat/signin');
        }
    }

    
}

?>