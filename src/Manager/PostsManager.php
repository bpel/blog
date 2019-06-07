<?php

namespace src\Manager;

use Core\Model\Model;
use PDO;
use src\Model\Post;

class PostsManager extends Model
{

    private $commentsmanager;
    private $usermanager;

    public function __construct()
    {
        $this->commentsmanager = new CommentsManager();
        $this->usermanager = new UserManager();
    }


    public function getPosts()
    {
        $post = null;
        $req = $this->getBdd()->prepare('SELECT * FROM posts ORDER BY posts.id DESC;;');
        $req->execute();
        while($data = $req->fetch(\PDO::FETCH_ASSOC))
        {
            $data['user'] = $this->usermanager->getUserbyId($data['iduser']);
            $post[] = new Post($data);
        }
        $req->closeCursor();

        return $post;
    }

    public function showPost($id)
    {
        $var = [];
        $req = $this->getBdd()->prepare('SELECT * FROM posts WHERE id = :id');
        $req->bindParam(':id', $id);
        $req->execute();
        $data = $req->fetch(\PDO::FETCH_ASSOC);
        $data['user'] = $this->usermanager->getUserbyId($data['iduser']);
        $var = new Post($data);
        $req->closeCursor();

        return $var;
    }

    public function removePost($id)
    {
        $req = $this->getBdd()->prepare('DELETE FROM posts WHERE id = :id');
        $req->bindParam(':id', $id);
        $req->execute();
        $req->closeCursor();
    }

    public function getPost($id)
    {
        return $this->getElement('Posts','src\Model\\Post',$id);
    }

    public function editPost($post)
    {
        $title = $post->getTitle();
        $subhead = $post->getSubhead();
        $text = $post->getText();
        $iduser = $post->getIduser();
        $id = $post->getId();

        $req = $this->getBdd()->prepare('UPDATE posts SET title = :title, subhead = :subhead, text = :text, iduser = :iduser, updatedate = :updatedate WHERE id = :id;');
        $date_creation = $this->getDateNow();

        $req->bindParam(':title', $title,PDO::PARAM_STR);
        $req->bindParam(':subhead', $subhead,PDO::PARAM_STR);
        $req->bindParam(':text', $text,PDO::PARAM_STR);
        $req->bindParam(':iduser', $iduser,PDO::PARAM_INT);
        $req->bindParam(':updatedate', $date_creation,PDO::PARAM_STR);
        $req->bindParam(':id',$id, PDO::PARAM_STR);
        $req->execute();
        $req->closeCursor();
    }

    public function createPost($post)
    {

        $titre = $post->getTitle();
        $subhead = $post->getSubhead();
        $text = $post->getText();
        $iduser = $post->getIduser();

        $req = $this->getBdd()->prepare('INSERT INTO posts (title, subhead, text, iduser, creationdate, updatedate)VALUES (:title, :subhead, :text, :iduser, :creationdate, NULL);');

        $date_creation = $this->getDateNow();

        $req->bindParam(':title', $titre);
        $req->bindParam(':subhead', $subhead,PDO::PARAM_STR);
        $req->bindParam(':text', $text,PDO::PARAM_STR);
        $req->bindParam(':iduser', $iduser,PDO::PARAM_INT);
        $req->bindParam(':creationdate',$date_creation, PDO::PARAM_STR);
        $req->execute();
        $req->closeCursor();
        }


}