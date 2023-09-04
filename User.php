<?php

session_start();

require 'Connect.php';

final class User
{
    public $name;
    public $email;
    public $password;

    function setUser($pdo, $name, $email, $password)
    {
        if(!empty($name) || !empty($email) || !empty($passwod)){
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                echo "Email já cadastrado!";
            }else{
                try{    
                    $sql = "INSERT INTO users VALUES (:cd, :name, :email, :password)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':cd', '');
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $password);    
                    if($stmt->execute()){ 
                        echo 'Usuário cadastrado com sucesso!'; 
                    }else{
                        throw new Exception('Erro ao cadastrar usuário');  
                    }
                } catch(PDOException $e){
                    echo 'Error: '.$e->getMessage();
                }                
            }
        }
        else{
            echo "Há campos vazios!";
        }
    }

    function selectUser($pdo, $fields, $where)
    {
        if($fields == ""){
            $fields = "*";
        }
        try{
            $sql = "SELECT $fields FROM users $where";
            $stmt = $pdo->prepare($sql);
            if($stmt->execute()){
                return $stmt;       
            }else{
                throw new Exception('Erro ao executar o select!');
            }
        } catch(PDOException $e){
            echo 'Error: '.$e->getMessage();
        }
    }

    function deleteUser($pdo, $id)
    {
        try{
            $sql = "DELETE FROM users WHERE cd = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            if($stmt->execute()){
                echo "Usuário {$id} deletado com sucesso!";
            }else{
                throw new Exception('Erro ao deletar usuário');
            }
        } catch(PDOException $e){
            echo "Error: ".$e->getMessage();
        }
    }

    function updateUser($pdo, $id, $name, $email)
    {
        if(!empty($id) || !empty($name) || !empty($email) || !empty($passwod)){
            try{    
                $sql = "UPDATE users SET name = :name, email = :email WHERE cd = ".$id;
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':email', $email);   
                if($stmt->execute()){ 
                    echo 'Usuário editado com sucesso!'; 
                }else{
                    throw new Exception('Erro ao editar usuário');  
                }
            } catch(PDOException $e){
                echo 'Error: '.$e->getMessage();
            }                
        }
        else{
            echo "Há campos vazios!";
        }
    }
}

// $user = new User();

// $user->setUser($pdo, "muckz", "muckz@gmail.com", "@senhaforte");