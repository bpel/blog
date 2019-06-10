<?php

namespace Core\Controller;

use Core\Request;
use Core\Security;
use Core\View\View;
use src\Manager\CommentsManager;
use src\Manager\ContactsManager;
use src\Manager\PostsManager;
use src\Manager\UserManager;
use src\Model\Mail;

class Controller
{
    protected $twig;
    protected $request;
    protected $security;
    protected $userManager;
    protected $postsManager;
    protected $commentsManager;
    protected $contactsManager;

    protected $sessionProvider;
    protected $easyCSRF;

    protected $mail;

    public function __construct()
    {
        $this->request = new Request();
        $this->security = new Security($this->request);
        $this->userManager = new UserManager();
        $this->postsManager = new PostsManager;
        $this->commentsManager = new CommentsManager();
        $this->contactsManager = new ContactsManager();
        $this->mail = new Mail();
    }

    public function viewRender($viewName, $data) {

        $view = new View($viewName, $data);

        echo $view;
    }

    public function redirectToPage($url)
    {
        header("Location : ?page".$url);
        return 1;
    }

}