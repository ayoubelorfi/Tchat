<?php

namespace App\Entity;

use PDO;
use \Core\Security;
use \Core\Db; 

class Users extends Db
{
    private $security;
    private $tableName = "users";

    private $id;
    private $username;
    private $email;
    private $password;
    private $connected = false;

    public function __construct()
    {
        parent::__construct();
        $this->security = new Security();
    }

    function setId(int $id): void
    { 
        $this->id = $id; 
    }
    
    function getId(): int
    {
        return $this->id; 
    }

    function setUsername(string $username): void 
    { 
        $this->username = $username; 
    }

    function getUsername(): string
    { 
        return $this->username; 
    }

    function setEmail(string $email): void
    { 
        $this->email = $email; 
    }

    function getEmail(): string
    { 
        return $this->email; 
    }

    function setPassword(string $password): void
    { 
        $this->password = $password; 
    }

    function getPassword(): string
    { 
        return $this->password; 
    }

    public function createAccount(): bool
    { 
        try{
            $sql = "INSERT INTO ".$this->tableName." (username, email, password) VALUES (:username, :email, :password)";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $this->username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $this->password, PDO::PARAM_STR);
            $execute = $stmt->execute();

            if(!empty($execute))
                return true;
            else
                return false;
                
        } catch (\Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
        }
    }

    
    public function getUser(string $username, string $password): ?object
    {   
        try{
            $sql = "SELECT * FROM ".$this->tableName." where (username = :username and password = :password)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            if($result)
                return $result;
                
            return null;

        } catch (\Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
        }
    }

    public function getUserbyId(int $id): ?object
    { 
        try{
            $sql = "SELECT * FROM ".$this->tableName." where (id = :id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            if($result){
                $result->id_crypt = $this->security->CryptJWT(['secret_key' => 'tchat', 'secret_iv' => 'user', 'text' => $result->id]);
                return $result;
            }
            return null;
        } catch (\Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
        }
    }

    public function getAllContacts(array $args): ?array
    { 
        try{
            
            $sql = "SELECT * FROM ".$this->tableName." where id is not null ";
            if(isset($args['id'])){
                $sql .= " AND id <> :id";
            }
            if(isset($args['search'])){
                $sql .= " AND username like :search";
            }  
            $stmt = $this->conn->prepare($sql);

            if(isset($args['id'])){
                $stmt->bindParam(':id', $args['id'], PDO::PARAM_STR);
            }
            if(isset($args['search'])){
                $search = '%'.$args['search'].'%';
                $stmt->bindParam(':search',$search, PDO::PARAM_STR);
            } 
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ); 
            if($result){
                foreach($result as $r){
                    $r->id_crypt = $this->security->CryptJWT(['secret_key' => 'tchat', 'secret_iv' => 'user', 'text' => $r->id]);
                }
                return $result;
            }
            return null;
        } catch (\Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
        }
    }

    public function setConnected(int $id): bool
    { 
        try{
            $sql = "UPDATE ".$this->tableName." SET connected=1 WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);

            $execute = $stmt->execute();

            if(!empty($execute))
                return true;
            else
                return false;

        } catch (\Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
        }
    }
        
    public function setDisconnected(int $id): bool
    { 
        try{
            $sql = "UPDATE ".$this->tableName." SET connected=0 WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);

            $execute = $stmt->execute();

            if(!empty($execute))
                return true;
            else
                return false;

        } catch (\Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
        }
    }
}
