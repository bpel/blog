<?php

namespace src\Controller;

use Core\Controller\Controller;

class ControllerComment extends Controller
{

    public function index()
    {
        $posts = $this->pos->getPosts();

        $this->viewRender('viewAccueil', [
            'posts' => $posts
        ]);
    }
}