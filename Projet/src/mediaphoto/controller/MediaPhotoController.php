<?php

namespace mediaphoto\controller;

use mediaphoto\view\MediaPhotoView;

class MediaPhotoController extends \mf\control\AbstractController {
    public function __construct()
    {
        parent::__construct();
    }

    public function viewHome() {
        MediaPhotoView::addStyleSheet('/html/assets/css/accueil.css');
        $galleries = \mediaphoto\model\Gallery::select()->orderBy('id', 'desc')->get();
        $vue = new \mediaphoto\view\MediaPhotoView($galleries);
        $vue->render('renderHome');
    }

    public function viewGallery() {
        MediaPhotoView::addStyleSheet('/html/assets/css/galerie.css');
        MediaPhotoView::addScript('/html/assets/js/jquery-3.2.1.js');
        MediaPhotoView::addScript('/html/assets/js/vignette.js');
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
        MediaPhotoView::addStyleSheet('/html/assets/css/detail.css');
        MediaPhotoView::addScript('/html/assets/js/jquery-3.2.1.js');
        MediaPhotoView::addScript('/html/assets/js/lightbox.js');
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

    public function viewSearch() {
        MediaPhotoView::addStyleSheet('/html/assets/css/accueil.css');
        $render = [];
        if(isset($this->request->get['galerie'])) {
            $galleries = \mediaphoto\model\Gallery::select()->where('titre', 'LIKE', '%'.$this->request->get['galerie'].'%')->get();
            $render['galerie'] = $galleries;
        }
        if(isset($this->request->get['photo'])) {
            $photos = \mediaphoto\model\Photo::select()->where('titre', 'LIKE', '%'.$this->request->get['photo'].'%')->get();
            $render['photo'] = $photos;
        }
        $vue = new \mediaphoto\view\MediaPhotoView($render);
        $vue->render('renderViewSearch');
    }

    public function checkSearch() {
        $router = new \mf\router\Router();
        if(isset($this->request->post['submit'])) {
            $filter = [];
            $link = '';
            $i = 0;
            $search = filter_var($this->request->post['search'], FILTER_SANITIZE_SPECIAL_CHARS);

            if(isset($this->request->post['filter-photo'])) {
                $filter[] = $this->request->post['filter-photo'];
            }
            if(isset($this->request->post['filter-galerie'])) {
                $filter[] = $this->request->post['filter-galerie'];
            }

            foreach($filter as $f) {
                // Si premier tour de boucle
                if($i == 0) {
                    $link .= '?' . $f . '=' . $search;
                // Sinon
                } else {
                    $link .= '&' . $f . '=' . $search;
                }
                $i++;
            }

            header('Location:' . $router->urlFor('viewSearch') . $link);
            exit;
        } else {
            $router->executeRoute('home');
        }
    }

    public function viewCreateGallery() {
        MediaPhotoView::addStyleSheet('/html/assets/css/styleLogin.css');
        MediaPhotoView::addScript('/html/assets/js/jquery-3.2.1.js');
        MediaPhotoView::addScript('/html/assets/js/autosearch.js');
        MediaPhotoView::addScript('/html/assets/js/conf-user.js');
        MediaPhotoView::addScript('/html/assets/js/block-add-user.js');
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        if(!$auth->logged_in) {
            $router = new \mf\router\Router();
            header('Location:' . $router->urlFor('home'));
            exit;
        } else {
            $vue = new \mediaphoto\view\MediaPhotoView([]);
            $vue->render('renderViewCreateGallery');
        } 
    }

    public function checkCreateGallery() {
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        $router = new \mf\router\Router();
        if(!$auth->logged_in) {
            $router = new \mf\router\Router();
            header('Location:' . $router->urlFor('home'));
            exit;
        } else {
            $post = $this->request->post;
            if($post['submit']) {
                $title = filter_var($post['galerie-name'], FILTER_SANITIZE_SPECIAL_CHARS);
                $desc = filter_var($post['galerie-desc'], FILTER_SANITIZE_SPECIAL_CHARS);
                $tags = filter_var($post['list-tag'], FILTER_SANITIZE_SPECIAL_CHARS);
                $type = filter_var($post['galerie-conf'], FILTER_SANITIZE_SPECIAL_CHARS);
                $users = filter_var($post['list-user'], FILTER_SANITIZE_SPECIAL_CHARS);
    
                if(empty($title) || empty($tags) || empty($desc) || empty($type)) {
                    $auth->generateMessage('create_gallery_error', array('Veuillez renseigner tous les champs.', 'red'), 'viewCreateGallery');
                } else {
                    $user_id = \mediaphoto\model\User::getLoggedUserId();
                    $tags = explode(',', $tags);
                    $id_tags = [];

                    if(empty($users) && $type == '3') {
                        $auth->generateMessage('create_gallery_error', array('Veuillez partager votre galerie à au moins un utilisateur.', 'red'), 'viewCreateGallery');
                    } else {
                        if(!empty($users)) {
                            $users = explode(',', $users);
                            $id_users = [];
        
                            foreach($users as $u) {
                                $exist = \mediaphoto\model\User::where('nom', '=', $u)->count();
                                if(!$exist) {
                                    $auth->generateMessage('create_gallery_error', array('L\'utilisateur que vous avez spécifié n\'existe pas.', 'red'), 'viewCreateGallery');
                                    exit;
                                } else {
                                    $id_users[] = \mediaphoto\model\User::select('id')->where('nom', '=', $u)->first()->id;
                                }
                            }
        
                            foreach($id_users as $u) {
                                \mediaphoto\model\Share::insert(
                                    ['id_utilisateur' => $u, 'id_galerie' => 0]
                                );
                            }

                            foreach($tags as $t) {
                                $exist = \mediaphoto\model\Tag::where('nom', '=', $t)->count();
                                if(!$exist) {
                                    $lastInsertTagsId = \mediaphoto\model\Tag::insertGetId(
                                        ['nom' => $t]
                                    );
                                    $id_tags[] = $lastInsertTagsId;
                                } else {
                                    $id_tags[] = \mediaphoto\model\Tag::select('id')->where('nom', '=', $t)->first()->id;
                                }
                            }
        
                            foreach($id_tags as $t) {
                                \mediaphoto\model\TagGallery::insert(
                                    ['id_tag' => $t, 'id_galerie' => 0]
                                );
                            }
    
                            $lastInsertId = \mediaphoto\model\Gallery::insertGetId(
                                ['titre' => $title, 'description' => $desc, 'type' => $type, 'auteur' => $user_id]
                            );
                            \mediaphoto\model\TagGallery::where('id_galerie', '=', 0)->update(['id_galerie' => $lastInsertId]);
                            \mediaphoto\model\Share::where('id_galerie', '=', 0)->update(['id_galerie' => $lastInsertId]);
        
                            header('Location:' . $router->urlFor('viewGallery', array('id' => $lastInsertId)));
                            exit;
                        }
                    }
                }
            } else {
                header('Location:' . $router->urlFor('viewCreateGallery'));
                exit;
            }  
        }
    }
}