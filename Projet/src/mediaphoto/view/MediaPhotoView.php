<?php

namespace mediaphoto\view;

class MediaPhotoView extends \mf\view\AbstractView {
    public function __construct($data)
    {
        parent::__construct($data);
    }

    private function renderHeader() {
        $result = <<<HTML
        <div>
            Super Logo
        </div>
        <div>
            <a href="#">Connexion</a>
            <a href="#">Inscription</a>
        </div>
        HTML;
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
            $link = $router->urlFor('galleryView', array('id' => $g->id));

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
        $gallery = $this->data;
        $title = $gallery->titre;
        $desc = $gallery->description;

        $get_author = $gallery->author()->first();
        $author = $get_author->nom;

        $result = <<<HTML
        <center>
        <h1>Galerie : ${title}</h1>
        <div>
            <p>Description :</p>
            <p>${desc}</p>
        </div>
        <hr>
        <p>Créé par : ${author}</p>
        <hr>
        <h1>Dernière publications</h1>
        </center>
        HTML;

        $photos = $gallery->photos()->get();
        
        foreach($photos as $p) {
            $titre = $p->titre;
            $chemin = $p->chemin;

            $result .= <<<HTML
            <p>${titre}</p>
            <img src="${chemin}" alt="${titre}">
            HTML;
        }

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