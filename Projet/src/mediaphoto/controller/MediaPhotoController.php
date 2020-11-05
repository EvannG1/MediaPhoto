<?php

namespace mediaphoto\controller;

class MediaPhotoController extends \mf\control\AbstractController {
    public function __construct()
    {
        parent::__construct();
    }

    public function viewHome() {
        $galleries = \mediaphoto\model\Gallery::select()->orderBy('id', 'desc')->get();
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
            $gallery = \mediaphoto\model\Gallery::select()->where('id', '=', $id)->first();
            $vue = new \mediaphoto\view\MediaPhotoView($gallery);
            $vue->render('renderViewGallery');
        }
    }

    public function viewPhoto() {
        if(!isset($this->request->get['id'])) {
            $router = new \mf\router\Router();
            header('Location:' . $router->urlFor('home'));
            exit;
        } else {
            $id = $this->request->get['id'];
            $photo = \mediaphoto\model\Photo::select()->where('id', '=', $id)->first();
            $vue = new \mediaphoto\view\MediaPhotoView($photo);
            $vue->render('renderViewPhoto');
        }
    }
}