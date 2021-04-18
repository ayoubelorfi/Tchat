<?php

namespace App\Controllers;

use \Core\View;
use \Core\AbstractController;
use \Core\Security;
use App\Entity\Users;
use App\Entity\Messages;

class MessagesController extends AbstractController
{
    private $users;
    private $messages;
    private $security;

    public function __construct( ) 
    {
        parent::__construct([]);
        $this->security = new Security();
        $this->users = new Users();
        $this->messages = new Messages();

    }

    public function chatAction()
    { 
        $userId = $this->security->DecryptJWT(['secret_key' => 'tchat', 'secret_iv' => 'user', 'text' => $_POST['id']]);
        $user = $this->users->getUserbyId($userId);

        $my_id = $this->getSession()->iduser;  
        $conversation = $this->messages->getConversation($userId,$my_id);
        foreach($conversation as $msg){ 
            if($msg->receiver == $this->getSession()->iduser)
                $this->messages->setSeenMsg($msg->id);
        } 
        View::renderView('messages/chat.html', [
            'user' => $user, 
            'conversation' => $conversation
            ]
        );
    }
    
    public function singleMessageAction()
    {
        $userId = $this->security->DecryptJWT(['secret_key' => 'tchat', 'secret_iv' => 'user', 'text' => $_POST['id']]);
        $my_id = $this->getSession()->iduser; 
        $conversation = $this->messages->getConversation($userId,$my_id);

        View::renderView('messages/message.html', ['conversation' => $conversation]);
    }
    
    public function sendAction()
    {
        $sender = $this->getSession()->iduser;
        $receiver =  $this->security->DecryptJWT(['secret_key' => 'tchat', 'secret_iv' => 'user', 'text' => $_POST['id']]);
        $content = trim($_POST['message']);

        if(!empty($receiver) && !empty($content)){
            $this->messages->setSender($sender);
            $this->messages->setReceiver($receiver);
            $this->messages->setContent($content);
            $sendMessage = $this->messages->sendMessage();

            if($sendMessage){
                $message_id = $sendMessage;
                $user = $this->users->getUserbyId($receiver);
                $connected = 0;
                if($user){ 
                    if($user->connected == 1){
                        $setSentMsg = $this->messages->setSentMsg($message_id);
                        
                        if($setSentMsg)
                            $connected = 1;
                    }
                }

                echo json_encode(['connected' => $connected, 'date_m' => date('Y-m-d H:i:s')]);
            }

        }
    }
    
    public function listMessagesAction()
    {
         
        
        $messages = $this->messages->getAllMessages($this->getSession()->iduser); 
        foreach($messages as $message){  
            $messageNumber = $this->messages->getMessageNumberBySender($this->getSession()->iduser ,$message->user_id );
            $message->messageNumber = $messageNumber->messageNumber;
            $message->user = $this->users->getUserbyId($message->user_id);

        }   

        View::renderView('messages/all_messages.html', ['messages' => $messages]);
    } 
}
