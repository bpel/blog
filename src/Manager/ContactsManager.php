<?php

namespace src\Manager;

use Core\Model\Model;
use src\Model\Contact;
use src\Model\Mail;

class ContactsManager extends Model
{
    private $userManager;
    private $mail;

    public function __construct()
    {
        $this->mail = new Mail();
        $this->userManager = new UserManager();
    }

    public function addContact($contact)
    {
        if($contact->getIduser())
        {
            $req = $this->getBdd()->prepare('INSERT INTO contacts (iduser, message, createdate)VALUES (:iduser, :message, :createdate);');
            $req->bindValue(':iduser', $contact->getIduser());
            $req->bindValue(':message', $contact->getMessage());
            $req->bindValue(':createdate', $this->getDateNow());
            $req->execute();
            $req->closeCursor();
        } else {
            $req = $this->getBdd()->prepare('INSERT INTO contacts (lastname, firstname, mail, message, createdate)VALUES (:lastname, :firstname, :mail, :message, :createdate);');
            $req->bindValue(':lastname', $contact->getLastname());
            $req->bindValue(':firstname', $contact->getFirstname());
            $req->bindValue(':mail', $contact->getMail());
            $req->bindValue(':message', $contact->getMessage());
            $req->bindValue(':createdate', $this->getDateNow());
            $req->execute();
            $req->closeCursor();
        }
    }

    public function getContacts()
    {
        $contact = null;
        $req = $this->getBdd()->prepare('SELECT * FROM contacts ORDER BY contacts.id DESC;');
        $req->execute();
        while($data = $req->fetch(\PDO::FETCH_ASSOC))
        {
            if(empty($data['firstname']) || empty($data['lastname'] || empty($data['mail'])))
            {
                $req_get_user = $this->getBdd()->prepare('SELECT * FROM users WHERE id = :iduser;');
                $req_get_user->bindParam(':iduser', $data['iduser']);
                $req_get_user->execute();
                $data2 = $req_get_user->fetch(\PDO::FETCH_ASSOC);

                $data['user'] = $this->userManager->getUserbyId($data['iduser']);


                $data['firstname'] = $data2['firstname'];
                $data['lastname'] = $data2['lastname'];
                $data['mail'] = $data2['mail'];
            }

            $contact[] = new Contact($data);
        }
        $req->closeCursor();
        return $contact;
    }

}