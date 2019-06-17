<?php

namespace src\Controller;

use Core\Controller\Controller;
use Core\Request;
use src\Model\User;

class ControllerUser extends Controller
{

    public function userRegister()
    {
        $errors = null;
        if ($this->security->isLogged()) {
            $this->redirectToPage('home');
        }
            if ($this->request->exist("btn_register", "post")) {
                $user = new User(array());
                $user->setLastName(strtolower($this->request->post('lastname')));
                $user->setFirstName(strtolower($this->request->post('firstname')));
                $user->setMail(strtolower($this->request->post('mail')));
                $user->setPassword($this->request->post('password'));
                $user->setConfirmPassword($this->request->post('confirm_password'));
                $errors = $user->getErrorsForm();

                if ($user->validForm()) {
                    $this->userManager->registerUserIntoDatabase($user);
                    $user_db = $this->userManager->getUser($user->getMail());
                    $this->userManager->setGrantUser($user_db['id'],3);
                    unset($user);
                    $this->viewRender('User/login', [
                        'message' => "Compte crée avec success, connectez-vous maintenant"
                    ]);
                } else {
                    $this->viewRender('User/register', [
                        'errors' => $errors,
                        'user' => $user
                    ]);
                }

            } else {
                $this->viewRender('User/register', [

                ]);
            }

    }

    public function userLogin()
    {
        if ($this->security->isLogged()) {
            $this->redirectToPage('home');
        }
            if ($this->request->exist("btn_login", "post")) {
                $user = new User(array());
                $user->setMail($this->request->post('mail'));
                $user->setPassword($this->request->post('password'));
                if($user->validFormLogin())
                {
                    $datas_user = $user->getDatasLogin();
                    $this->security->connectUser($datas_user);
                    header("Location: ?page=home");
                } else {
                    $errors = $user->getErrorsFormLogin();
                    $this->viewRender('User/login', [
                        'errors' => $errors,
                        'user' => $user
                    ]);
                }

            } else {
                $this->viewRender('User/login', [

                ]);
            }
    }

    public function logout()
    {
        if(!$this->security->isLogged())
        {
            $this->redirectToPage('login');
        }
            $_SESSION = array();
            $this->redirectToPage('home');
    }
}