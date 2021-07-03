<?php
$configs = include('config.php');
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));
define('APP_NAME', explode('/', ROOT)[4]);
require_once(ROOT . 'app/Model.php');
require_once(ROOT . 'app/Controller.php');
require_once(ROOT . 'app/ComponentController.php');

if (isset($_GET['p'])) :
    $params = explode('/', $_GET['p']);
elseif (isset($_POST['p'])) :
    $params = explode('/', $_POST['p']);
endif;

if (isset($params) && $params[0] != "") {
    $controller = ucfirst($params[0]);
    $action = isset($params[1]) ? $params[1] : 'index';
    require_once(ROOT . 'controllers/' . $controller . '.php');
    $controller = new $controller();
    if (method_exists($controller, $action)) {
        unset($params[0]);
        unset($params[1]);
        call_user_func_array([$controller, $action], $params);
    } else {
        http_response_code(404);
        echo "La page demandée n'existe pas";
    }
} else {
    if (isset($_GET['course_id'])) {
        header('Location: https://'.$configs['server_name'].'/' . APP_NAME . '/index.php?p=home&course_id='.$_GET['course_id']);
    }
    else {
        header('Location: https://'.$configs['server_name'].'/' . APP_NAME . '/index.php?p=home');
    }
}
