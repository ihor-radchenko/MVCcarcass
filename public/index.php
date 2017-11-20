<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:06
 */

//require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/function.php';

$logger = new IhorRadchenko\App\Components\Logger();
$errorHandler = new IhorRadchenko\App\Components\ErrorHandler($logger);

$route = IhorRadchenko\App\Components\Router::getInstance();

try {
    $route->run();
} catch (IhorRadchenko\App\Exceptions\DbException $e) {
    $logger->emergency($e->getMessage(), ['Exception' => get_class($e), 'File' => $e->getFile(), 'Line' => $e->getLine()]);
    require_once $_SERVER['DOCUMENT_ROOT'] . '/App/templates/layouts/errors/errorDB.phtml';
    die();
} catch (IhorRadchenko\App\Exceptions\Error404 $e) {
    $controller = new IhorRadchenko\App\Controllers\Error;
    $controller->action('page404');
}