<?php

namespace src\Model;

class Comment
{

    private $_id;
    private $_iduser;
    private $_idpost;
    private $_comment;
    private $_createdate;
    private $_idstatut;
    private $_firstname;
    private $_lastname;
    private $_user;

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
    public function getIdpost()
    {
        return $this->_idpost;
    }

    /**
     * @param mixed $idpost
     */
    public function setIdpost($idpost): void
    {
        $this->_idpost = $idpost;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->_comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment): void
    {
        $this->_comment = $comment;
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

}