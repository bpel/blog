<?php

namespace src\Controller;

use Core\Controller\Controller;
use src\Model\Post;

class ControllerAdmin extends Controller
{

    public function index()
    {
        if($this->security->isAdministrator())
        {
            $this->viewRender('Admin/dashboard', [
                'user_data' => $this->security->getDatasSession(),
                'page_name' => 'Tableau de bord'
            ]);
        } else {
            $this->viewRender('viewErrorGrantAccess', [
                'message' => "Vous n'avez pas la permission pour accedez à cette page"
            ]);
        }
    }

    public function adminlistposts()
    {
        if($this->security->isAdministrator())
        {
            $posts = $this->postsManager->getPosts();

            $this->viewRender('Admin/adminListPosts', [
                'posts' => $posts,
                'user_data' => $this->security->getDatasSession(),
                'page_name' => 'Liste articles'
            ]);

        } else {
            $this->viewRender('viewErrorGrantAccess', [
                'message' => "Vous n'avez pas la permission pour accedez à cette page"
            ]);
        }
    }

    public function adminlistcomments()
    {
        if($this->security->isAdministrator())
        {
            $comments = $this->commentsManager->getComments();

            $this->viewRender('Admin/adminListComments', [
                'comments' => $comments,
                'user_data' => $this->security->getDatasSession(),
                'page_name' => 'Liste commentaire'
            ]);
        } else {
            $this->viewRender('viewErrorGrantAccess', [
                'message' => "Vous n'avez pas la permission pour accedez à cette page"
            ]);
        }

    }

    public function adminlistcontacts()
    {

        if($this->security->isAdministrator())
        {
            $contacts = $this->contactsManager->getContacts();

            $this->viewRender('Admin/adminListContact', [
                'contacts' => $contacts,
                'user_data' => $this->security->getDatasSession(),
                'page_name' => 'Liste message'
            ]);
        } else {
            $this->viewRender('viewErrorGrantAccess', [
                'message' => "Vous n'avez pas la permission pour accedez à cette page"
            ]);
        }

    }

    public function adminlistusers()
    {
        if($this->security->isAdministrator())
        {
            $users = $this->userManager->getUsers();

            $this->viewRender('Admin/adminListUsers', [
                'users' => $users,
                'user_data' => $this->security->getDatasSession(),
                'page_name' => 'Liste utilisateurs'
            ]);
        } else {
            $this->viewRender('viewErrorGrantAccess', [
                'message' => "Vous n'avez pas la permission pour accedez à cette page"
            ]);
        }

    }

    public function addPost()
    {
        if($this->security->isAdministrator()){
            $this->viewRender('Admin/addPost', [
                'user_data' => $this->security->getDatasSession(),
                'page_name' => 'Création article'
            ]);
        } else {
            $this->viewRender('viewErrorGrantAccess', [
                'message' => "Vous n'avez pas la permission pour accedez à cette page"
            ]);
        }
    }

    public function showPost($id)
    {
        if($this->security->isAdministrator())
        {
            $post = $this->postsManager->showPost($id);

            $this->viewRender('Admin/showPost', [
                'list_users' => $this->userManager->getUsers(),
                'user_data' => $this->security->getDatasSession(),
                'post' => $post,
                'page_name' => 'Affichage article'
            ]);
        } else {
            $this->viewRender('viewErrorGrantAccess', [
                'message' => "Vous n'avez pas la permission pour accedez à cette page"
            ]);
        }
    }

    public function showComment()
    {
        if($this->security->isAdministrator())
        {
            $this->viewRender('Admin/adminListComments', [
                'user_data' => $this->security->getDatasSession(),
                'page_name' => 'Affichage commentaire'
            ]);
        } else {
            $this->viewRender('viewErrorGrantAccess', [
                'message' => "Vous n'avez pas la permission pour accedez à cette page"
            ]);
        }
    }

    public function validComment($id)
    {
        if($this->security->isAdministrator())
        {
            $this->commentsManager->changeStatutComment($id,2);
            $this->adminlistcomments();
        } else {
            $this->viewRender('viewErrorGrantAccess', [
                'message' => "Vous n'avez pas la permission pour accedez à cette page"
            ]);
        }
    }

    public function blockComment($id)
    {
        if($this->security->isAdministrator())
        {
            $this->commentsManager->changeStatutComment($id,3);
            $this->adminlistcomments();
        } else {
            $this->viewRender('viewErrorGrantAccess', [
                'message' => "Vous n'avez pas la permission pour accedez à cette page"
            ]);
        }
    }

    public function removePost($id)
    {
        if($this->security->isAdministrator())
        {
            $this->postsManager->removePost($id);
            $this->adminlistposts();
        } else {
            $this->viewRender('viewErrorGrantAccess', [
                'message' => "Vous n'avez pas la permission pour accedez à cette page"
            ]);
        }
    }

    public function addPostDB() {

        if($this->security->isAdministrator()){
            if($this->request->exist('btn_create_post','post'))
            {
                $post = new Post(array());
                $post->setTitle($this->request->post('title'));
                $post->setSubhead($this->request->post('subhead'));
                $post->setText($this->request->post('text'));
                $post->setIduser($this->request->session('iduser'));
                if($post->validform("add"))
                {
                    $this->postsManager->createPost($post);
                    $this->viewRender('Admin/addPost',[
                        'message' => "Article ajouté avec success",
                        'user_data' => $this->security->getDatasSession()
                    ]);


                } else {
                    $errors = $post->getErrorsForm();
                    $this->viewRender('Admin/addPost',[
                        'errors' => $errors,
                        'user_data' => $this->security->getDatasSession()
                    ]);
                }

            } else {
                $this->viewRender('Admin/addPost', [
                    'user_data' => $this->security->getDatasSession()
                ]);
            }
        } else {
            $this->viewRender('viewErrorGrantAccess', [
                'message' => "Vous n'avez pas la permission pour accedez à cette page"
            ]);
        }
    }

    public function admineditpost()
    {
        if($this->security->isAdministrator())
        {
            if($this->request->exist('btn_edit_post','post'))
            {
                $post = new Post(array());
                $post->setText($this->request->post('text'));
                $post->setSubhead($this->request->post('subhead'));
                $post->setTitle($this->request->post('title'));
                $post->setId($this->request->post('post_id'));
                $post->setIduser($this->request->post('id_user'));
                if($post->validForm("edit"))
                {
                    $auteur = $this->userManager->getUserbyId($post->getIduser());
                    $this->postsManager->editPost($post);
                    $this->viewRender('Admin/showpost',[
                        'list_users' => $this->userManager->getUsers(),
                        'message' => "L'article à été modifié avec success",
                        'post' => $post,
                        'auteur' => $auteur,
                        'user_data' => $this->security->getDatasSession()
                    ]);
                } else {
                    $errors = $post->getErrorsForm();
                    $post = $this->postsManager->showPost($this->request->post('post_id'));
                    $this->viewRender('Admin/showpost',[
                        'list_users' => $this->userManager->getUsers(),
                        'errors' => $errors,
                        'post' => $post,
                        'user_data' => $this->security->getDatasSession()
                    ]);
                }
            } else {
                $this->showPost($this->request->post('post_id'));
            }
        } else {
            $this->viewRender('viewErrorGrantAccess', [
                'message' => "Vous n'avez pas la permission pour accedez à cette page"
            ]);
        }
    }

}