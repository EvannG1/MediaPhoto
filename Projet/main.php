<?php

require_once 'vendor/autoload.php';
require_once 'src/mf/utils/AbstractClassLoader.php';
require_once 'src/mf/utils/ClassLoader.php';

$loader = new \mf\utils\ClassLoader('src');
$loader->register();

// Titre de la page
\mediaphoto\view\MediaPhotoView::setAppTitle('MediaPhoto');

$config = parse_ini_file('conf/config.ini');
$db = new \Illuminate\Database\Capsule\Manager();
$db->addConnection($config);
$db->setAsGlobal();
$db->bootEloquent();

$router = new \mf\router\Router();
$router->addRoute('home', '/', '\mediaphoto\controller\MediaPhotoController', 'viewHome');
$router->addRoute('galleryView', '/gallery/', '\mediaphoto\controller\MediaPhotoController', 'viewGallery');
$router->setDefaultRoute('/');
$router->run();
