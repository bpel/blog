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

    public function contact()
    {
        $contact = new Contact(array());
        if ($this->request->exist('btn_contact', 'post')) {

            $contact->setValueFromPost();

            if ($contact->validForm())
            {
                $this->mail->setReceiverMail($contact->getMail());
                $this->mail->setSubjectMail('[NEW] CONTACT FORM');
                $this->mail->setMessageMail($contact->getMessageMailContact());

                try {
                    $this->mail->sendMail();
                    $this->viewRender('Contact/addContact', [
                        'message' => 'Votre message à été envoyé avec success!',
                        'user_data' => $this->security->getDatasSession(),
                        'token_crsf' => $this->security->tokenCrsf(),
                        'page_name' => "Contact"
                    ]);
                } catch (\Exception $e) {
                    $this->viewRender('Contact/addContact', [
                        'error' => "Le message n’a pu être envoyé, veuillez ressayer ultérieurement.",
                        #'error' => $e->getMessage(),
                        'contact_datas' => $contact,
                        'user_data' => $this->security->getDatasSession(),
                        'token_crsf' => $this->security->tokenCrsf(),
                        'page_name' => "Contact"
                    ]);
                }
                return true;
            }

            $this->viewRender('Contact/addContact', [
                'errors' => $contact->getErrorsForm(),
                'contact_datas' => $contact,
                'user_data' => $this->security->getDatasSession(),
                'token_crsf' => $this->security->tokenCrsf(),
                'page_name' => "Contact"
            ]);
            return false;
        }
        $this->viewRender('Contact/addContact', [
            'contact_datas' => $contact,
            'user_data' => $this->security->getDatasSession(),
            'token_crsf' => $this->security->tokenCrsf(),
            'page_name' => "Contact"
        ]);
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