<?php

namespace src\Controller;

use Core\Controller\Controller;
use Core\Request;
use Core\Security;

class ControllerPost extends Controller
{
    public function index()
    {
        $posts = $this->postsManager->getPosts();

        $this->viewRender('viewAccueil', [
            'posts' => $posts
        ]);
    }

    public function listPosts() {

        $request = new Request();

        $user_data = null;

        $security = new Security($request);
        if($security->isLogged())
        {
            $user_data = $security->getDatasSession();
        }
        $posts = $this->postsManager->getPosts();

        $this->viewRender('Post/listPosts', [
            'posts' => $posts,
            'user_data' => $this->security->getDatasSession(),
            'page_name' => "Liste des articles"
        ]);
    }

    public function showPost($id,$errors = null,$message=null) {
        $post = $this->postsManager->showPost($id);
        $comments = $this->commentsManager->getCommentsPost($id);

        $this->viewRender('Post/showPost', [
            'post' => $post,
            'comments' => $comments,
            'errors' => $errors,
            'message' => $message,
            'user_data' => $this->security->getDatasSession()
        ]);
    }

    public function editPost($id) {

        $errors = null;
        $message = null;

        if ($this->request->exist('btn-comment','post') && !empty($this->request->post('text_comment')))
        {
            $message = "Votre commentaire à été enregistré, il sera controlé par nos équipes dans les plus bref délais";
            $this->commentsManager->addComment($id);
        }
        else
        {
            $errors[] = "Le champ commentaire ne peux être vide";
        }

        $this->showPost($id,$errors,$message);
    }

    public function removePost($numpost) {
        $this->postsManager->removePost($numpost);
        $this->index();
    }



}