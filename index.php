<?php

use Core\Router\Router;
use src\Controller\ControllerAdmin;
use src\Controller\ControllerHome;
use src\Controller\ControllerPost;
use src\Controller\ControllerUser;

require 'vendor/autoload.php';

session_start();

$page = 'home';

if(isset($_GET['page'])) { $page = $_GET['page']; }

$router = new Router($page);

#HOME ROUTES

$router->post('/contact/', function()
{
    $controller = new ControllerHome();
    $controller->contact();
});

$router->get('/contact/', function()
{
    $controller = new ControllerHome();
    $controller->contact();
});

$router->post('/contact/', function()
{
    $controller = new ControllerHome();
    $controller->addContact();
});

$router->get('/showinfos/', function()
{
    $controller = new ControllerHome();
    $controller->showInfos();
});

$router->get('home', function()
{
    $controller = new ControllerHome();
    $controller->index();
});

#POST ROUTES

$router->get('/showpost/:id', function($id)
{
    $controller = new ControllerPost();
    $controller->showPost($id);
});

$router->post('/showpost/:id', function($id)
{
    $controller = new ControllerPost();
    $controller->editPost($id);
});

$router->get('/listposts/', function()
{
    $controller = new ControllerPost();
    $controller->listPosts();
});


#USER ROUTES

$router->post('/login/', function()
{
    $controller = new ControllerUser();
    $controller->userLogin();
});

$router->get('/login/', function()
{
    $controller = new ControllerUser();
    $controller->userLogin();
});

$router->post('/login/', function()
{
    $controller = new ControllerUser();
    $controller->userLogin();
});

$router->get('/register/', function()
{
    $controller = new ControllerUser();
    $controller->userRegister();
});

$router->post('/register/', function()
{
    $controller = new ControllerUser();
    $controller->userRegister();
});

$router->get('/logout/', function()
{
    $controller = new ControllerUser();
    $controller->logout();
});






#ADMIN ROUTES

$router->get('/adminshowpost/:id', function($id)
{
    $controller = new ControllerAdmin();
    $controller->showPost($id);
});

$router->get('/removepost/:id', function($id)
{
    $controller = new ControllerAdmin();
    $controller->removePost($id);
});

$router->get('/addpost/', function()
{
    $controller = new ControllerAdmin();
    $controller->addPost();
});

$router->get('/dashboard/', function()
{
    $controller = new ControllerAdmin();
    $controller->index();
});

$router->get('/adminlistcontacts/', function()
{
    $controller = new ControllerAdmin();
    $controller->adminlistcontacts();
});

$router->post('/admineditpost/', function()
{
    $controller = new ControllerAdmin();
    $controller->admineditpost();
});

$router->get('/adminlistusers/', function()
{
    $controller = new ControllerAdmin();
    $controller->adminlistusers();
});

$router->get('/adminlistposts/', function()
{
    $controller = new ControllerAdmin();
    $controller->adminlistposts();
});

$router->get('/showcomment/:id', function($id)
{
    $controller = new ControllerAdmin();
    $controller->showComment($id);
});

$router->get('/validcomment/:id', function($id)
{
    $controller = new ControllerAdmin();
    $controller->validComment($id);
});

$router->get('/blockcomment/:id', function($id)
{
    $controller = new ControllerAdmin();
    $controller->blockComment($id);
});

$router->get('/adminlistcomments/', function()
{
    $controller = new ControllerAdmin();
    $controller->adminlistcomments();
});

$router->post('/addpost/', function()
{
    $controller = new ControllerAdmin();
    $controller->addPostDB();
});

$router->run();