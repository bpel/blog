<?php

namespace src\Manager;

use Core\Model\Model;
use src\Model\User;

class UserManager extends Model
{

    public function getUsers()
    {
        $var = [];
        $req = $this->getBdd()->prepare('SELECT * FROM users ORDER BY users.id ASC;;');
        $req->execute();
        while($data = $req->fetch(\PDO::FETCH_ASSOC))
        {
            $var[] = new User($data);
        }
        return $var;
        $req->closeCursor();
    }

    public function userExist($iduser)
    {
        $var = [];
        $req = $this->getBdd()->prepare('SELECT * FROM users WHERE users.id = :iduser;;');
        $req->bindParam(':iduser', $iduser);
        $req->execute();
        $data = $req->fetch();
        $req->closeCursor();
        return $data;
    }


    public function getUser($mail)
    {
        $var = [];
        $req = $this->getBdd()->prepare('SELECT * FROM users WHERE users.mail = :mail;;');
        $req->bindParam(':mail', $mail);
        $req->execute();
        $data = $req->fetch();
        return $data;
        $req->closeCursor();
    }

    public function getUserbyId($userid)
    {
        $req = $this->getBdd()->prepare('SELECT * FROM users WHERE users.id = :userid;;');
        $req->bindParam(':userid', $userid);
        $req->execute();
        $data = $req->fetch();
        return $data;
        $req->closeCursor();
    }

    public function passwordDatabaseAccount($mail)
    {
        $req = $this->getBdd()->prepare('SELECT password FROM users WHERE users.mail = :mail;);'); #sql qui retourne un boolean !!
        $req->bindParam(':mail', $mail);
        $req->execute();
        $data = $req->fetch();
        return $data;
        $req->closeCursor();
    }


    public function mailExistIntoDatabase($mail)
    {
        $req = $this->getBdd()->prepare('SELECT id FROM users WHERE EXISTS (SELECT * FROM users WHERE users.mail = :mail);'); #sql qui retourne un boolean !!
        $req->bindParam(':mail', $mail);
        $req->execute();
        $data = $req->fetch();
        return $data;
        $req->closeCursor();
    }

    public function setGrantUser($id_user,$id_grant)
    {
        $req = $this->getBdd()->prepare('INSERT INTO grants_users (id_user, id_grant) VALUES (:id_user, :id_grant);');
        $req->bindParam(':id_user', $id_user);
        $req->bindParam(':id_grant', $id_grant);
        $req->execute();
        $req->closeCursor();
    }


    public function registerUserIntoDatabase($user)
    {
        $lastname = $user->getLastName();
        $firstname = $user->getFirstName();
        $mail = $user->getMail();
        $password = password_hash($user->getPassword(),PASSWORD_BCRYPT);

        $req = $this->getBdd()->prepare('INSERT INTO users (lastname, firstname, mail, password) VALUES (:lastname, :firstname, :mail, :password);');
        $req->bindParam(':lastname', $lastname);
        $req->bindParam(':firstname', $firstname);
        $req->bindParam(':mail', $mail);
        $req->bindParam(':password', $password);

        $req->execute();
        $req->closeCursor();
        }

    public function getDatasFromDatabase($mail)
    {
        $req = $this->getBdd()->prepare('SELECT users.id, users.lastname, users.firstname, users.mail, grants_names.grant_name FROM users
INNER JOIN grants_users ON grants_users.id_user = users.id 
INNER JOIN grants_names ON grants_names.id = grants_users.id_grant
WHERE users.mail = :mail;'); #sql qui retourne un boolean !!
        $req->bindParam(':mail', $mail);
        $req->execute();
        $data = $req->fetch();
        return $data;
        $req->closeCursor();

    }


}