<?php

namespace App\Entity;

use PDO;
use \Core\Security;
use \Core\Db;

class Messages extends Db
{
    private $tableName = "messages";
    private $id;
    private $content;
    private $sender;
    private $receiver;
    private $seen = false;
    private $sent = false;

    public function __construct()
    {
        parent::__construct();
    }

    function setMessageId(int $id): void
    { 
        $this->id = $id; 
    }

    function getMessageId(): int 
    {
        return $this->id; 
    }

    function setContent(string $content): void
    { 
        $this->content = $content; 
    }

    function getContent(): string 
    { 
        return $this->content; 
    }

    function setSender(int $sender): void 
    { 
        $this->sender = $sender; 
    }

    function getSender(): int
    { 
        return $this->sender; 
    }

    function setReceiver(int $receiver): void 
    { 
        $this->receiver = $receiver; 
    }

    function getReceiver(): int 
    { 
        return $this->receiver; 
    }

    function setSeen(int $seen): void 
    { 
        $this->seen = $seen;  
    }
    
    function getSeen(): int 
    { 
        return $this->seen; 
    }

    function setSent(int $sent): void
    { 
        $this->sent = $sent;  
    }
    
    function getSent(): int 
    { 
        return $this->sent; 
    }

    public function sendMessage(): ?int 
    { 
        try{
            $sql = "INSERT INTO ".$this->tableName." (content, sender, receiver, date, seen, sent) VALUES (:content, :sender, :receiver, now(), 0, 0 )";

            $prep = $this->conn->prepare($sql);
            $prep->bindParam(':content', $this->content, PDO::PARAM_STR);
            $prep->bindParam(':sender',  $this->sender, PDO::PARAM_STR);
            $prep->bindParam(':receiver', $this->receiver, PDO::PARAM_STR);
            $execute = $prep->execute();
            $last_id = $this->conn->lastInsertId();
            if(!empty($execute))
                return $last_id;
            else
                return null;

        } catch (\Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
        }
    }

    public function getConversation(int $sender, int $receiver): array
    {
        try{
            $sql = "SELECT * FROM ".$this->tableName." where (sender = :user_id and receiver = :my_id) or (sender = :my_id and receiver = :user_id) 
            ORDER BY id ASC";
            $prep = $this->conn->prepare($sql);
            $prep->bindParam(':user_id', $sender, PDO::PARAM_STR);
            $prep->bindParam(':my_id', $receiver, PDO::PARAM_STR);
            $prep->execute();
            $result = $prep->fetchAll(PDO::FETCH_OBJ);

            return $result;
        } catch (\Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
        }
    }
    
    public function getAllMessages(int $id): array
    {
        try{
            $sql = "SELECT CASE
                WHEN m.sender = :my_id OR m.receiver <> :my_id THEN m.receiver
                WHEN m.receiver = :my_id OR m.sender <> :my_id THEN m.sender
                ELSE :my_id
            END AS user_id
            FROM messages m
            INNER JOIN  (SELECT DISTINCT receiver, sender, max(id) AS maxid 
                        FROM messages 
                        WHERE (messages.receiver = :my_id OR messages.sender = :my_id) 
                        GROUP BY receiver, sender
                        ) AS b ON  m.id = b.maxid
            GROUP BY user_id
            ORDER BY m.id DESC";

            $prep = $this->conn->prepare($sql);
            $prep->bindParam(':my_id', $id, PDO::PARAM_STR);
            $prep->execute();
            $result = $prep->fetchAll(PDO::FETCH_OBJ);

            if($result){
                foreach($result as $r){
                    $r->message = $this->getAllMessagesbyIdUser($r->user_id);
                }
            }

            return $result;
        } catch (\Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
        }
    }

    public function getAllMessagesbyIdUser(int $id): object
    {
        try{
            $sql = "SELECT *  FROM  messages m
                    WHERE (m.sender = :my_id or m.receiver = :my_id)
                    ORDER BY m.id DESC";

            $prep = $this->conn->prepare($sql);
            $prep->bindParam(':my_id', $id, PDO::PARAM_STR);
            $prep->execute();

            return $prep->fetch(PDO::FETCH_OBJ);
        } catch (\Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
        }
    }

    public function setSeenMsg(int $id): bool
    { 
        try{
            $sql = "UPDATE ".$this->tableName." SET seen = 1 WHERE id = :id";
            $prep = $this->conn->prepare($sql);
            $prep->bindParam(':id', $id, PDO::PARAM_STR);

            $execute = $prep->execute();

            if(!empty($execute))
                return true;
            else
                return false;

        } catch (\Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
        }
    }
        
    public function setSentMsg(int $id): bool
    { 
        try{
            $sql = "UPDATE ".$this->tableName." SET sent = 1 WHERE id = :id";

            $prep = $this->conn->prepare($sql);
            $prep->bindParam(':id', $id, PDO::PARAM_STR);

            $execute = $prep->execute();

            if(!empty($execute))
                return true;
            else
                return false;

        } catch (\Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
        }
    }

    public function setSentAllMsg(int $receiver): bool
    { 
        try{
            $sql = "UPDATE ".$this->tableName." SET seen = 1 WHERE receiver = :id";

            $prep = $this->conn->prepare($sql);
            $prep->bindParam(':id', $receiver, PDO::PARAM_STR);

            $execute = $prep->execute();

            if(!empty($execute))
                return true;
            else
                return false;

        } catch (\Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
        }
    }
    
    public function getMessageNumber(int $receiver): object 
    { 
        try{
            $sql = "SELECT COUNT(id) as messageNumber
                    FROM ".$this->tableName." 
                    WHERE (receiver = :id) AND (seen = 0)";

            $prep = $this->conn->prepare($sql);
            $prep->bindParam(':id', $receiver, PDO::PARAM_STR);
            $prep->execute();
            $result = $prep->fetch(PDO::FETCH_OBJ);

            return $result;
        } catch (\Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
        }
    }

    public function getMessageNumberBySender(int $receiver,int $sender): object 
    { 
        try{
            $sql = "SELECT COUNT(id) as messageNumber
                    FROM ".$this->tableName." 
                    WHERE (receiver = :receiver) AND (sender = :sender) AND (seen = 0)";

            $prep = $this->conn->prepare($sql);
            $prep->bindParam(':receiver', $receiver, PDO::PARAM_STR);
            $prep->bindParam(':sender', $sender, PDO::PARAM_STR);
            $prep->execute();
            $result = $prep->fetch(PDO::FETCH_OBJ);

            return $result;
        } catch (\Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
        }
    }
}
