<?php

namespace src\Model;

use Core\Model\Model;
use Core\Request;
use Core\Security;

class Contact extends Model
{

    private $_id;
    private $_iduser;
    private $_message;
    private $_idstatut;
    private $_lastname;
    private $_firstname;
    private $_mail;
    private $_createdate;
    private $_user;

    private $request;
    private $security;

    public function __construct(array $data)
    {
        $this->hydrate($data);
        $this->request = new Request();
        $this->security = new Security($this->request);
    }

    public function hydrate(array $data)
    {
        foreach($data as $key => $value)
        {
            $method = 'set'.ucfirst($key);

            if(method_exists($this, $method))
                $this->$method($value);
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->_id = $id;
    }

    /**
     * @return mixed
     */
    public function getIduser()
    {
        return $this->_iduser;
    }

    /**
     * @param mixed $iduser
     */
    public function setIduser($iduser): void
    {
        $this->_iduser = $iduser;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->_message = $message;
    }

    /**
     * @return mixed
     */
    public function getIdstatut()
    {
        return $this->_idstatut;
    }

    /**
     * @param mixed $idstatut
     */
    public function setIdstatut($idstatut): void
    {
        $this->_idstatut = $idstatut;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->_lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname): void
    {
        $this->_lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->_firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
        $this->_firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getMail()
    {
        return $this->_mail;
    }

    /**
     * @param mixed $mail
     */
    public function setMail($mail): void
    {
        $this->_mail = $mail;
    }

    /**
     * @return mixed
     */
    public function getCreatedate()
    {
        return $this->_createdate;
    }

    /**
     * @param mixed $createdate
     */
    public function setCreatedate($createdate): void
    {
        $this->_createdate = $createdate;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->_user = $user;
    }

    public function validForm($typeLogin)
    {
        switch ($typeLogin)
        {
            case "login":
                if($this->isDefine("login") && $this->crsfisValid()) { return 1; } else { return 0; }
                break;
            case "anonymous":
                if($this->isDefine("anonymous") && $this->mailValid() && $this->lastnameValid() && $this->fistnameValid() && $this->crsfisValid())
                { return 1; } else { return 0; }
                break;
        }
    }

    public function crsfisValid()
    {
        if($this->request->post('token_crsf') == $this->request->session('token_crsf'))
        {
            return 1;
        } else {
            return 0;
        }
    }


    public function isDefine($typeLogin)
    {
        switch ($typeLogin)
        {
            case "login":
                if(!empty($this->_iduser) && !empty($this->_message)) { return 1; } else { return 0; }
                break;
            case "anonymous":
                if(!empty($this->_lastname) && !empty($this->_firstname) && !empty($this->_mail) && !empty($this->_message)) { return 1; } else { return 0; }
                break;
        }
    }

    public function mailValid()
    {
        return(filter_var($this->_mail, FILTER_VALIDATE_EMAIL));
    }

    public function fistnameValid()
    {
        if(strlen($this->_lastname) > 0 && strlen($this->_lastname) <= 100)
        {
            return 1;
        } else {
            return 0;
        }
    }

    public function lastnameValid()
    {
        if(strlen($this->_lastname) > 0 && strlen($this->_lastname) <= 100)
        {
            return 1;
        } else {
            return 0;
        }
    }

    public function getErrorsForm($typeLogin)
    {
        $error = array();
        switch ($typeLogin)
        {
            case "login":
                if(!$this->isDefine("login")) { $error[] = "Tout les champs ne sont pas remplit";}
                if(!$this->crsfisValid()) { $error[] = "Le token CRSF n'est pas valide"; }
                return $error;
                break;

            case "anonymous":
                if(!$this->isDefine("anonymous")) { $error[] = "Tout les champs ne sont pas remplit";}
                if(!$this->mailValid()) { $error[] = "L'adresse email n'est pas valide"; }
                if(!$this->fistnameValid()) { $error[] = "Taille du prenom de 1 a 100 caractere"; }
                if(!$this->lastnameValid()) { $error[] = "Taille du nom de 1 a 100 caractere"; }
                if(!$this->crsfisValid()) { $error[] = "Le token CRSF n'est pas valide"; }
                return $error;
                break;
        }
    }

}