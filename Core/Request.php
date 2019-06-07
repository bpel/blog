<?php

namespace Core;

class Request {
    const GET = "get";
    private $datas = [
    self::GET => [],
    "post" => [],
    "session" => []
    ];
    private $data;

    public function __construct() {
    $this->data[self::GET] = $_GET;
    $this->data['post'] = $_POST;
    $this->data['session'] = $_SESSION;
    }

    public function exist($key, $method = "get") {
        switch ($method){
            case "get":
                if(isset($_GET[$key])) {
                    return true;
                }
                break;
            case "post":
                if(isset($_POST[$key])) {
                    return true;
                }
                break;
            case "session":
                if(isset($_SESSION[$key])) {
                    return true;
                }
                break;
        }
        return false;
    }

    public function get($key) {
    if (!$this->exist($key)) {
    return null;
    }

    return $this->data['get'][$key];
    }

    public function post($key) {
        if (!$this->exist($key,"post")) {
            return null;
        }

        return $this->data['post'][$key];
    }

    public function set($key, $value, $method = "get"): self {

        switch ($method){
            case "get":
                    $_GET[$key] = $value;
                break;
            case "post":
                $_POST[$key] = $value;
                break;
            case "session":
                $_SESSION[$key] = $value;
                break;
        }

        return $this;

    }

    public function session($key) {
        if ($this->exist($key,"session")) {
            return $this->data['session'][$key];
        } else {
            return false;
        }
    }

}