<?php

namespace src\Model;

use src\Manager\UserManager;

class User
{

    private $_id;
    private $_lastname;
    private $_firstname;
    private $_mail;
    private $_password;
    private $_confirm_password;
    private $_last_login;
    private $_grant;

    public function __construct(array $data)
    {
        $this->hydrate($data);
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
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->_lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastName($lastname)
    {
        $this->_lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->_firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstName($firstname)
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
    public function setMail($mail)
    {
        $this->_mail = $mail;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
    }

    /**
     * @return mixed
     */
    public function getConfirmPassword()
    {
        return $this->_confirm_password;
    }


    /**
     * @param mixed $confirm_password
     */
    public function setConfirmPassword($confirm_password): void
    {
        $this->_confirm_password = $confirm_password;
    }

    /**
     * @return mixed
     */
    public function getLastLogin()
    {
        return $this->_derniereconnexion;
    }

    /**
     * @param mixed $last_login
     */
    public function setLastLogin($last_login)
    {
        $this->_last_login = $last_login;
    }

    /**
     * @return mixed
     */
    public function getGrant()
    {
        return $this->_grant;
    }

    /**
     * @param mixed $grant
     */
    public function setGrant($grant): void
    {
        $this->_grant = $grant;
    }

    public function isDefine() : bool
    {
        return(!empty($this->_lastname) && !empty($this->_firstname) && !empty($this->_mail) && !empty($this->_password) && !empty($this->_confirm_password));
    }

    public function isDefineLogin() : bool
    {
        return(!empty($this->_mail) && !empty($this->_password));
    }

    public function validMail() : bool
    {
        return(filter_var($this->_mail, FILTER_VALIDATE_EMAIL));
    }

    public function validLastName() : bool
    {
        if(strlen($this->_lastname) > 0 && strlen($this->_lastname) <= 100)
        {
            return 1;
        } else {
            return 0;
        }
    }

    public function validFirstName() : bool
    {
        if(strlen($this->_firstname) > 0 && strlen($this->_firstname) <= 100)
        {
            return 1;
        } else {
            return 0;
        }
    }

    public function equalsPasswords() : bool
    {
        return(!strcasecmp($this->_password,$this->_confirm_password));
    }

    public function validPassword() : bool
    {
        if(strlen($this->_password) > 8 && strlen($this->_password) <= 100)
        {
            return 1;
        } else { return 0; }
    }

    public function getErrorsForm()
    {
        $error = array();
        if(!$this->isDefine()) { $error[] = "Tout les champs ne sont pas remplit";}
        if(!$this->validMail()) { $error[] = "Adresse email invalide"; $this->_mail = "";}
        if(!$this->equalsPasswords()) { $error[] = "Les mot de passes ne sont pas identiques"; }
        if(!$this->validPassword()) { $error[] = "La taille du mot de passe est comprise entre 9 et 100 caracteres"; }
        if(($this->checkMailNotExistDatabase())) { $error[] = "L'adresse email est déjà utilisé."; }
        if(!$this->validFirstName()) { $error[] = "La taille du prenom doit être comprit entre 1 et 100 caracteres"; }
        if(!$this->validLastName()) { $error[] = "La taille du nom doit être comprit entre 1 et 100 caracteres"; }
        return $error;
    }

    public function getErrorsFormLogin()
    {
        $error = array();
        if(!$this->isDefineLogin()) { $error[] = "Tout les champs ne sont pas remplit";}
        if(!$this->validMail()) { $error[] = "Adresse email invalide"; $this->_mail = "";}
        if(!$this->checkMailNotExistDatabase()) { $error[] = "L'adresse email ne correspond a aucun compte"; }
        if(!$this->checkAccount()) { $error[] = "Le mot de passe n'est pas correcte";}
        return $error;
    }

    public function getDatasLogin()
    {
        $userManager = new UserManager();
        $datas_user = $userManager->getDatasFromDatabase($this->_mail);
        return $datas_user;

    }

    public function validForm()
    {
        if($this->isDefine() && $this->validMail() && $this->equalsPasswords() && $this->validPassword() && !$this->checkMailNotExistDatabase() && $this->validFirstName() && $this->validLastName())
        {
            return 1;
        }
        else { return 0; }
    }

    public function validFormLogin()
    {
        if($this->isDefineLogin() && $this->validMail() && $this->checkMailNotExistDatabase() && $this->checkAccount())
        {
            return 1;
        }
        else { return 0; }
    }

    public function checkAccount()
    {
        $userManager = new UserManager();
        $password_hash = $userManager->passwordDatabaseAccount($this->_mail);
        $password_hash = $password_hash['password'];
        $password_user = $this->_password;
        if(password_verify($password_user,$password_hash)){
            return 1;
        }
        else { return 0; }


    }

    public function checkMailNotExistDatabase()
    {
        $userManager = new UserManager();
        $mailExist = $userManager->mailExistIntoDatabase($this->_mail);
        return $mailExist;
    }


    public function flush()
    {
        #call le model qui cree l'entite avec les params
        return 1;
    }


    public function persist()
    {
        #call le model qui cree l'entite avec les params
        return 1;
    }

}