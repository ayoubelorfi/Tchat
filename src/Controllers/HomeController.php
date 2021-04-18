<?php

namespace App\Controllers;

use \Core\View;
use \Core\AbstractController;
use \Core\Security;
use App\Entity\Users;
use App\Entity\Messages;

class HomeController extends AbstractController
{
    private $users;
    private $messages;

    public function __construct() 
    {
        parent::__construct([]);
        $this->users = new Users();
        $this->messages = new Messages();
    }

    public function indexAction()
    {
        $contacts = $this->users->getAllContacts(["id" => $this->getSession()->iduser]);
        $messages = $this->messages->getAllMessages($this->getSession()->iduser); 
        foreach($messages as $message){  
            $messageNumber = $this->messages->getMessageNumberBySender($this->getSession()->iduser ,$message->user_id );
            $message->messageNumber = $messageNumber->messageNumber;
            $message->user = $this->users->getUserbyId($message->user_id);

        }   
        View::renderView('index.html', [
                'contacts' => $contacts,
                'messages' => $messages
            ]
        );
    }

    public function searchAction()
    {
        if(isset($_REQUEST['search']))
            $contacts = $this->users->getAllContacts(["search" => trim($_REQUEST['search']), "id" => $this->getSession()->iduser]);
        else
            $contacts = $this->users->getAllContacts(["id" => $this->getSession()->iduser]);

        View::renderView('contacts/amis.html', [
                'contacts' => $contacts
            ]
        );
    }
     
}
