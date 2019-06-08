<?php

namespace src\Manager;

use Core\Model\Model;
use Core\Request;
use Core\Security;
use PDO;
use src\Model\Comment;

class CommentsManager extends Model
{

    private $security;
    private $request;
    private $usermanager;


    public function __construct()
    {
        $this->request = new Request();
        $this->security = new Security($this->request);
        $this->usermanager = new UserManager();
    }

    public function getComments()
    {
        $var = [];
        $req = $this->getBdd()->prepare('SELECT * FROM comments ORDER BY comments.id DESC;;');
        $req->execute();
        while($data = $req->fetch(\PDO::FETCH_ASSOC))
        {
            $req_get_user = $this->getBdd()->prepare('SELECT * FROM users WHERE id = :iduser;;');
            $req_get_user->bindParam(':iduser', $data['iduser']);
            $req_get_user->execute();
            $data2 = $req_get_user->fetch(\PDO::FETCH_ASSOC);
            $data['firstname'] = $data2['firstname'];
            $data['lastname'] = $data2['lastname'];

            $data['user'] = $this->usermanager->getUserbyId($data['iduser']);

            $var[] = new Comment($data);
        }
        $req->closeCursor();
        return $var;
    }

    public function changeStatutComment($id,$statut)
    {
        $req = $this->getBdd()->prepare('UPDATE comments SET idstatut = :statut WHERE id = :id;');
        $req->bindParam(':id', $id,PDO::PARAM_STR);
        $req->bindParam(':statut', $statut,PDO::PARAM_STR);
        $req->execute();
        $req->closeCursor();
    }

    public function getCommentsPost($idpost)
    {
        $var = [];
        $req = $this->getBdd()->prepare('SELECT * FROM comments WHERE idpost = :idpost AND idstatut = 2;');
        $req->bindParam(':idpost', $idpost,PDO::PARAM_STR);
        $req->execute();
        while($data = $req->fetch(\PDO::FETCH_ASSOC))
        {
            $data['user'] = $this->usermanager->getUserbyId($data['iduser']);
            $var[] = new Comment($data);
        }
        $req->closeCursor();
        return $var;
    }

    public function addComment($idpost)
    {
        $iduser = $this->security->getIdUser();

        $comment = $this->request->post('text_comment');
        $dateNow = $this->getDateNow();

        $req = $this->getBdd()->prepare('INSERT INTO comments (iduser, idpost, comment, createdate, idstatut) VALUES (:iduser, :idpost, :comment, :createdate , 1);');
        $req->bindParam(':iduser', $iduser);
        $req->bindParam(':idpost', $idpost);
        $req->bindParam(':comment', $comment,PDO::PARAM_STR);
        $req->bindParam(':createdate', $dateNow,PDO::PARAM_STR);
        $req->execute();
        $req->closeCursor();
    }

}