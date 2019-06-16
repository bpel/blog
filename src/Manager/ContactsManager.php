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

    public function addContact($contact, $type_login)
    {
        switch ($type_login)
        {
            case "login":
                $iduser = $contact->getIduser();
                $message = $contact->getMessage();
                $dateNow = $this->getDateNow();

                $user = $this->userManager->getUserbyId($iduser);

                $contact->setLastname($user['lastname']);
                $contact->setFirstName($user['firstname']);
                $contact->setMail($user['mail']);
                $contact->setCreateDate($this->getDateNow());

                $this->sendMail($contact);

                $req = $this->getBdd()->prepare('INSERT INTO contacts (iduser, message, createdate)VALUES (:iduser, :message, :createdate);');

                $req->bindParam(':iduser', $iduser);
                $req->bindParam(':message', $message);
                $req->bindParam(':createdate', $dateNow);
                $req->execute();
                $req->closeCursor();
                break;
            case "anonymous":
                $lastname = $contact->getLastname();
                $firstname = $contact->getFirstname();
                $mail = $contact->getMail();
                $message = $contact->getMessage();
                $dateNow = $this->getDateNow();

                $this->sendMail($contact);

                $req = $this->getBdd()->prepare('INSERT INTO contacts (lastname, firstname, mail, message, createdate)VALUES (:lastname, :firstname, :mail, :message, :createdate);');

                $req->bindParam(':lastname', $lastname);
                $req->bindParam(':firstname', $firstname);
                $req->bindParam(':mail', $mail);
                $req->bindParam(':message', $message);
                $req->bindParam(':createdate', $dateNow);
                $req->execute();
                $req->closeCursor();
                break;
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