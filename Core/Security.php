<?php

namespace Core;

class Security
{
    private $request;
    private $tokencrsf;

    public function __construct(Request $request) {
    $this->request = $request;
    }

    public function isLogged() {
        $request = new Request();
        return $request->exist("firstname","session");
    }

    public function isAdministrator() {
        if ($this->isLogged() && $this->request->session("grant_name") == 'administrator') {
            return true;
        }
        return false;
    }

    public function getDatasSession()
    {
        if($this->isLogged()) {
            $user_data['iduser'] = $this->request->session("iduser");
            $user_data['mail'] = $this->request->session("mail");
            $user_data['firstname'] = $this->request->session("firstname");
            $user_data['lastname'] = $this->request->session("lastname");
            $user_data['grant_name'] = $this->request->session("grant_name");
            $user_data['token_crsf'] = $this->request->session("token_crsf");
            return $user_data;
        }
        return null;
    }

    public function getIdUser()
    {
        if($this->isLogged())
        {
            return $this->request->session("iduser");
        }
        return null;
    }

    public function tokenCrsf()
    {
        $token = md5(uniqid(rand(), TRUE));
        $this->request->set('token_crsf',$token,'session');
        return $token;
    }

    public function connectUser($datas_user)
    {
        if($datas_user)
        {
            $this->request->set("iduser",$datas_user['id'],"session");
            $this->request->set("mail",$datas_user['mail'],"session");
            $this->request->set("firstname",$datas_user['firstname'],"session");
            $this->request->set("lastname",$datas_user['lastname'],"session");
            $this->request->set("grant_name",$datas_user['grant_name'],"session");
            return 1;

        }
        return null;
    }
}