<?php

namespace mediaphoto\controller;

class MediaPhotoController extends \mf\control\AbstractController {
    public function __construct()
    {
        parent::__construct();
    }

    public function viewHome() {
        $galleries = \mediaphoto\model\Gallery::all();
        $vue = new \mediaphoto\view\MediaPhotoView($galleries);
        $vue->render('renderHome');
    }

    public function viewGallery() {
        if(!isset($this->request->get['id'])) {
            $router = new \mf\router\Router();
            header('Location:' . $router->urlFor('home'));
            exit;
        } else {
            $id = $this->request->get['id'];
            $photos = \mediaphoto\model\Photo::select()->where('id_galerie', '=', $id)->get();
            $vue = new \mediaphoto\view\MediaPhotoView($photos);
            $vue->render('renderViewGallery');
        }
    }
}