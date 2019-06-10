<?php

namespace Core;

class Request {
    const GET = "get";
    private $data;

    public function __construct() {
    $this->data['get'] = filter_input_array(INPUT_GET);
    $this->data['post'] = filter_input_array(INPUT_POST);
    $this->data['session'] = filter_input_array(INPUT_SESSION);
    }

    public function exist($key, $method = "get") {
        switch ($method){
            case "get":
                if(isset($this->data['get'])) {
                    return true;
                }
                break;
            case "post":
                if(isset($this->data['post'])) {
                    return true;
                }
                break;
            case "session":
                if(isset($this->data['session'])) {
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