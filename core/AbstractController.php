<?php

namespace Core;

/**
 * Base controller
 */
abstract class AbstractController
{
    protected $route_params = [];
    protected $session = [];

    public function __construct($route_params)
    {
        $this->route_params = $route_params; 
        if(!isset($this->getSession()->iduser)){
            header('Location: /tchat/signin');
            exit;
        }

    }

    public function __call($name, $args)
    {
        $method = $name . 'Action';

        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new \Exception("Method $method not found in controller " . get_class($this));
        }
    }
    
    protected function before()
    {
    }
    
    protected function after()
    {
    }

    function redirect($url){
        header("location: {$url}");
    }

    protected function getSession(){
        return (object) $_SESSION;
    }

}
