<?php

namespace Core\Model;

use DateTime;
use DateTimeZone;
use Dotenv\Dotenv;
use PDOException;

abstract class Model
{
    private $bdd;
    public $datetime;
    public $datenow;
    public $dotenv;

    public function __construct()
    {
        $this->datetime = new DateTime();
        $this->dotenv = Dotenv::create(__DIR__.'/../../');
        $this->dotenv->load();
    }

    public function getDateNow()
    {
        $dateNow = new DateTime();
        $timezone = new DateTimeZone($this->getEnvVar('time_zone'));
        $dateNow->setTimezone($timezone);
        return $date = $dateNow->format("Y-m-d H:i");
    }

    public function getEnvVar($token)
    {
        if (isset($_ENV[$token])) { return $_ENV['$token']; }
        elseif ($_ENV[strtoupper($token)]) { return $_ENV[strtoupper($token)]; }
        else { return null; }
    }

    private function setBdd()
    {
        try {
            $this->bdd = new \PDO('mysql:host='.$this->getEnvVar('database_host').';dbname='.$this->getEnvVar('database_dbname').';charset=utf8',$this->getEnvVar('database_username'),$this->getEnvVar('database_password'));
            $this->bdd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
            return $this->bdd;
        } catch (PDOException $exception)
        {
            echo($exception->getMessage());
            die();
        }
    }

    protected function getBdd()
    {
        if($this->bdd == null) {
            $this->setBdd();
        }
        return $this->bdd;
    }

    protected function getAll($table, $obj){
        $var = [];
        $req = $this->getBdd()->prepare('SELECT * FROM ' .$table. ' ORDER BY numblog desc');
        $req->execute();
        while($data = $req->fetch(\PDO::FETCH_ASSOC))
        {
            $var[] = new $obj($data);
        }
        $req->closeCursor();
        return $var;
    }

    protected function showElement($table, $obj, $id)
    {
        $var = [];
        $req = $this->getBdd()->prepare('SELECT * FROM ' .$table. ' WHERE numblog = '.$id);
        $req->execute();
        while($data = $req->fetch(\PDO::FETCH_ASSOC))
        {
            $var[] = new $obj($data);
        }
        $req->closeCursor();
        return $var;
    }

    protected function removeElement($table, $obj, $id)
    {
        $req = $this->getBdd()->prepare('DELETE FROM ' .$table. ' WHERE numblog = '.$id);
        $req->execute();
        $req->closeCursor();
    }

    protected function getElement($table, $obj, $id)
    {
        $var = [];
        $req = $this->getBdd()->prepare('SELECT * FROM ' .$table. ' WHERE numblog = '.$id);
        $req->execute();
        while($data = $req->fetch(\PDO::FETCH_ASSOC))
        {
            $var[] = new $obj($data);
        }
        $req->closeCursor();
        return $var;
    }

}