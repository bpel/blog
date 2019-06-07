<?php

namespace src\Controller;

use Core\Controller\Controller;
use src\Model\Contact;

class ControllerHome extends Controller
{

    public function index() {
        $posts = $this->postsManager->getPosts();

        $this->viewRender('viewAccueil', [
            'posts' => $posts,
            'user_data' => $this->security->getDatasSession(),
            'page_name' => "Accueil"
        ]);
    }

    public function contact() {

        $token_crsf = '';

        if($this->security->isLogged())
        {
            if($this->request->exist('btn_contact','post'))
            {
                $contact = new Contact(array());
                $contact->setIduser($this->security->getIdUser());
                $contact->setIdstatut(1);
                $contact->setMessage($this->request->post('message'));
                if($contact->validForm("login") && $contact->crsfisValid()) #login =! anonymous
                {
                    $this->contactsManager->addContact($contact,"login");
                    $this->viewRender('Contact/addContact',[
                        'message' => "Message envoyé avec success",
                        'user_data' => $this->security->getDatasSession(),
                        'page_name' => "Contact"
                    ]);
                } else {
                    $this->security->generateTokenCrsf(); // on genere un token
                    $this->security->setTokenCrsfSession(); // on le met en session
                    $token_crsf = $this->security->getTokenCrsf(); // on le met dans le form
                    $errors = $contact->getErrorsForm("login");
                    $this->viewRender('Contact/addContact',[
                        'errors' => $errors,
                        'contact_datas' => $contact,
                        'user_data' => $this->security->getDatasSession(),
                        'page_name' => "Contact",
                        'token_crsf' => $token_crsf
                    ]);
                }
            } else {
                $this->security->generateTokenCrsf(); // on genere un token
                $this->security->setTokenCrsfSession(); // on le met en session
                $token_crsf = $this->security->getTokenCrsf(); // on le met dans le form
                $user_data = $this->security->getDatasSession();
                $this->viewRender('Contact/addContact',[
                    'user_data' => $user_data,
                    'page_name' => "Contact",
                    'token_crsf' => $token_crsf
                ]);
            }
        } else {
            if($this->request->exist('btn_contact','post'))
            {
                $contact = new Contact(array());
                $contact->setFirstname(strtolower($this->request->post('firstname')));
                $contact->setLastname(strtolower($this->request->post('lastname')));
                $contact->setMail(strtolower($this->request->post('mail')));
                $contact->setMessage($this->request->post('message'));
                $contact->setIdstatut(1);
                if($contact->validForm("anonymous") && $contact->crsfisValid()) #login =! anonymous
                {
                    $this->contactsManager->addContact($contact,"anonymous");
                    $this->viewRender('Contact/addContact',[
                        'message' => "Message envoyé avec success",
                        'user_data' => $this->security->getDatasSession()
                    ]);
                } else {
                    $this->security->generateTokenCrsf(); // on genere un token
                    $this->security->setTokenCrsfSession(); // on le met en session
                    $token_crsf = $this->security->getTokenCrsf(); // on le met dans le form
                    $errors = $contact->getErrorsForm("anonymous");
                    $this->viewRender('Contact/addContact',[
                        'page_name' => "Contact",
                        'contact_datas' => $contact,
                        'errors' => $errors,
                        'token_crsf' => $token_crsf
                    ]);
                }
            } else {
                $this->security->generateTokenCrsf(); // on genere un token
                $this->security->setTokenCrsfSession(); // on le met en session
                $token_crsf = $this->security->getTokenCrsf(); // on le met dans le form
                $this->viewRender('Contact/addContact',[
                    'page_name' => "Contact",
                    'token_crsf' => $token_crsf
                ]);
            }
        }
    }

    public function login() {

        $this->viewRender('login',[]);
    }

    public function register() {

        $this->viewRender('register',[]);
    }

    public function showInfos()
    {
        $this->viewRender('User/showInfos',[
            'user_data' => $this->security->getDatasSession(),
            'page_name' => "Informations personnelles"
        ]);
    }

}