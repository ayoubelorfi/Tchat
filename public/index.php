<?php

use Core\Db;

session_start();
/**
 * Front controller
 *
 * PHP version 5.6
 */

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Routing
 */
$router = new Core\Router();





// Add the routes
$router->add('', ['controller' => 'HomeController', 'action' => 'index']);
$router->add('search', ['controller' => 'HomeController', 'action' => 'search']);

// Signin
$router->add('signin', ['controller' => 'UserController', 'action' => 'signin']);
$router->add('signin/up', ['controller' => 'UserController', 'action' => 'login']);
$router->add('signout', ['controller' => 'UserController', 'action' => 'disconnect']);

// Signup
$router->add('signup', ['controller' => 'UserController', 'action' => 'signup']);
$router->add('signup/create-account', ['controller' => 'UserController', 'action' => 'createAccount']);

// Message
$router->add('chat', ['controller' => 'MessagesController', 'action' => 'chat']);
$router->add('chat/send', ['controller' => 'MessagesController', 'action' => 'send']);
$router->add('messages/list', ['controller' => 'MessagesController', 'action' => 'listMessages']);
$router->add('messages/single', ['controller' => 'MessagesController', 'action' => 'singleMessage']);


    
$router->dispatch($_SERVER['QUERY_STRING']);
