<?php

require_once 'vendor/autoload.php';
require_once 'src/mf/utils/AbstractClassLoader.php';
require_once 'src/mf/utils/ClassLoader.php';

$loader = new \mf\utils\ClassLoader('src');
$loader->register();

// Titre de la page
\mediaphoto\view\MediaPhotoView::setAppTitle('MediaPhoto');
// Importation de la feuille de style
\mediaphoto\view\MediaPhotoView::addStyleSheet('html/assets/css/style.css');

$config = parse_ini_file('conf/config.ini');
$db = new \Illuminate\Database\Capsule\Manager();
$db->addConnection($config);
$db->setAsGlobal();
$db->bootEloquent();

$router = new \mf\router\Router();
$router->addRoute('home', '/', '\mediaphoto\controller\MediaPhotoController', 'viewHome');
$router->addRoute('viewGallery', '/gallery/', '\mediaphoto\controller\MediaPhotoController', 'viewGallery');
$router->addRoute('viewPhoto', '/photo/', '\mediaphoto\controller\MediaPhotoController', 'viewPhoto');
$router->setDefaultRoute('/');
$router->run();
