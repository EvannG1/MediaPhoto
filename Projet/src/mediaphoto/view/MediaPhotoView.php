<?php

namespace mediaphoto\view;

class MediaPhotoView extends \mf\view\AbstractView {
    public function __construct($data)
    {
        parent::__construct($data);
    }

    private function renderHeader() {
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        $router = new \mf\router\Router();
        $login = $router->urlFor('viewLogin');
        $signup = $router->urlFor('viewSignup');
        $logout = $router->urlFor('viewLogout');

        $result = <<<HTML
        <div>
            Super Logo
        </div>
        HTML;

        if(!$auth->logged_in) {
            $result .= <<<HTML
            <div>
                <a href="${login}">Connexion</a>
                <a href="${signup}">Inscription</a>
            </div>
            HTML;
        } else {
            $name = $_SESSION['user_login'];
            $result .= <<<HTML
            <div>
                <a href="#">Poster une photo</a>
                <a href="#">Mes photos</a>
                <a href="#">${name}</a>
                <a href="${logout}">Déconnexion</a>
            </div>
            HTML;
        }

        return $result;
    }

    private function renderFooter() {
        return '';
    }

    private function renderHome() {
        $router = new \mf\router\Router();
        $galleries = $this->data;
        $result = <<<HTML
        <center>
            <form action="" method="post">
                <input type="text" name="searchBar" placeholder="RECHERCHER">
                <div>
                    <input type="radio" name="byTitle" id="byTitle">
                    <label for="byTitle">Par titre</label>
                    <input type="radio" name="byUsername" id="byUsername">
                    <label for="byTitle">Par utilisateur</label>
                    <input type="radio" name="byGroup" id="byGroup">
                    <label for="byTitle">Par groupe</label>
                </div>
                <button type="submit" name="search">OK</button>
            </form>
            <h2>Dernières publications</h2>
        </center>
        HTML;
        foreach($galleries as $g) {
            $title = $g->titre;
            $desc = $g->description;
            $date = $g->date;
            $link = $router->urlFor('viewGallery', array('id' => $g->id));

            $result .= <<<HTML
                <hr>
                <a href="${link}">Titre de la galerie : ${title}</a>
                <br>
                Description : ${desc}
                <br>
                Date de publication : ${date}
                <hr>
            HTML;
        }

        $result .= <<<HTML
        <a href="#">Voir plus</a>
        HTML;

        return $result;
    }

    private function renderViewGallery() {
        $router = new \mf\router\Router();

        $gallery = $this->data;
        $title = $gallery->titre;
        $desc = $gallery->description;
        $type = $gallery->type;
        $size = $gallery->taille;
        $author = $gallery->author()->first()->nom;
        
        $result = <<<HTML
        <center>
        <h1>Galerie : ${title}</h1>
        <div>
            <p>Description :</p>
            <p>${desc}</p>
        </div>
        <hr>
        <p>Créé par : ${author}</p>
        <p>Taille totale de la galerie : ${size}</p>
        HTML;
        
        if($type == 3) {
            $get_share = $gallery->partage()->get();
            $share = [];
            foreach($get_share as $s) {
                $share[] = $gallery->getShareUsername($s->id)->nom;
            }
            $share = implode(', ', $share);

            $result .= <<<HTML
            <p>Partagé avec : ${share}</p>
            HTML;
        }

        $result .= <<<HTML
        <hr>
        <h1>Dernière publications</h1>
        </center>
        HTML;

        $photos = $gallery->photos()->get();

        foreach($photos as $p) {
            $title = $p->titre;
            $path = $p->chemin;
            $link = $router->urlFor('viewPhoto', array('id' => $p->id));

            $result .= <<<HTML
            <p>${title}</p>
            <a href="${link}"><img src="${path}" alt="${title}"></a>
            HTML;
        }

        return $result;
    }

    private function renderViewPhoto() {
        $router = new \mf\router\Router();
        $photo = $this->data;

        $id = $photo->id;
        $title = $photo->titre;
        $path = $photo->chemin;
        $size = $photo->taille;
        $quality = $photo->qualite;
        $type = $photo->type;

        $author = $photo->author()->first()->nom;
        $gallery = $photo->gallery()->first()->titre;
        $gallery_id = $photo->id_galerie;
        $get_photos = $photo->getGalleryPhoto($gallery_id, $id, 4);

        $result = <<<HTML
        <center>
            <h1>${title}</h1>
            <img src="${path}" alt="${title}">
            <p>Cette photo fait partie de la galerie "${gallery}", voir les autres photos de cette galerie ci-dessous :</p>
        HTML;
        
        foreach($get_photos as $p) {
            $img_title = $p->titre;
            $img_path = $p->chemin;
            $img_link = $router->urlFor('viewPhoto', array('id' => $p->id));

            $result .= <<<HTML
            <p>${img_title}</p>
            <a href="${img_link}"><img src="${img_path}" alt="${img_title}" width="10%"></a>
            HTML;
        }

        $result .= <<<HTML
        <hr>
        <div>
            <p>Publiée par : ${author}</p>
            <p>Appartenant à la galerie : ${gallery}</p>
            <p>Taille de l'image : ${size}</p>
            <p>Qualité : ${quality}</p>
            <p>Type : ${type}</p>
        </div>
        </center>
        HTML;

        return $result;
    }

    protected function renderViewLogin() {
        $router = new \mf\router\Router();
        $checkLogin = $router->urlFor('checkLogin');
        $result = '';

        if(isset($_SESSION['login_error'])) {
            $message = $_SESSION['login_error'][0];
            $color = $_SESSION['login_error'][1];

            $result .= <<<HTML
            <p style="color:${color}">${message}</p>
            HTML;
        }

        $result .= <<<HTML
        <br>
        <form action="${checkLogin}" method="POST">
            <div>
                <label for="name">Nom d'utilisateur :</label>
                <input type="text" name="name" id="name">
            </div>
            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password">
            </div>
            <button type="submit">Se connecter</button>
        </form>
        HTML;
        return $result;
    }

    protected function renderBody($selector)
    {
        $header = $this->renderHeader();
        $selecteur = $this->$selector();
        $footer = $this->renderFooter();

        $html = <<<HTML
        <header>${header}</header>
        <section>${selecteur}</section>
        <footer>${footer}</footer>
        HTML;

        return $html;
    }
}