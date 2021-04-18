<?php

namespace Core;

/**
 * View
 */
class View
{
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = dirname(__DIR__) . "/templates/$view"; 

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }
    
    public static function renderView($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/templates');
            $twig = new \Twig\Environment($loader);
            $twig->addGlobal('request', $_REQUEST);
            $twig->addGlobal('session', $_SESSION);
        }

        echo $twig->render($template, $args);
    }
}