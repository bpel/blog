<?php

namespace src\Model;

use src\Manager\UserManager;

class Post
{

    private $_id;
    private $_title;
    private $_subhead;
    private $_text;
    private $_iduser;
    private $_creationdate;
    private $_updatedate;
    private $_user;

    private $usermanager;

    public function __construct(array $data)
    {
        $this->hydrate($data);
        $this->usermanager = new UserManager();
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
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->_title = $title;
    }

    /**
     * @return mixed
     */
    public function getSubhead()
    {
        return $this->_subhead;
    }

    /**
     * @param mixed $subhead
     */
    public function setSubhead($subhead): void
    {
        $this->_subhead = $subhead;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->_text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->_text = $text;
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
    public function getCreationdate()
    {
        return $this->_creationdate;
    }

    /**
     * @param mixed $creationdate
     */
    public function setCreationdate($creationdate): void
    {
        $this->_creationdate = $creationdate;
    }

    /**
     * @return mixed
     */
    public function getUpdatedate()
    {
        return $this->_updatedate;
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

    /**
     * @param mixed $updatedate
     */

    public function setUpdatedate($updatedate): void
    {
        $this->_updatedate = $updatedate;
    }

    public function isDefine() : bool
    {
        return(!empty($this->_title) && !empty($this->_subhead) && !empty($this->_text) && !empty($this->_iduser));
    }

    public function isDefineEdit() : bool
    {
        return(!empty($this->_title) && !empty($this->_subhead) && !empty($this->_text) && !empty($this->_iduser) && !empty($this->_id));
    }

    public function validIdUser() : bool
    {
        if ($this->usermanager->userExist($this->_iduser)){
            return 1;
        } else { return 0; }
    }

    public function validForm($nameForm)
    {
        switch ($nameForm){
            case "edit":
                if($this->isDefineEdit() && $this->validIdUser()) { return 1; } else { return 0; }
                break;
            case "add":
                if($this->isDefine()) { return 1; } else { return 0; }
                break;
        }


        if($this->isDefine()) { return 1; } else { return 0; }
    }

    public function getErrorsForm()
    {
        $error = array();
        if(!$this->isDefine()) { $error[] = "Tout les champs ne sont pas remplit";}
        if(!$this->validIdUser()) { $error[] = "Auteur invalide."; }
        return $error;
    }

}