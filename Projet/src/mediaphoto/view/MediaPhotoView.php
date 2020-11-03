<?php

namespace mediaphoto\view;

class MediaPhotoView extends \mf\view\AbstractView {
    public function __construct($data)
    {
        parent::__construct($data);
    }

    private function renderHeader() {
        return 'Superbe header';
    }

    private function renderFooter() {
        return 'Superbe footer';
    }

    private function renderHome() {
        $galleries = $this->data;
        $result = '';
        foreach($galleries as $g) {
            $title = $g->titre;
            $desc = $g->description;
            $date = $g->date;

            $result .= <<<HTML
                <hr>
                Titre de la galerie : ${title}
                <br>
                Description : ${desc}
                <br>
                Date de publication : ${date}
                <hr>
            HTML;
        }
        return $result;
    }

    private function renderViewGallery() {
        $photos = $this->data;
        $result = '';
        foreach($photos as $p) {
            $title = $p->titre;
            $date = $p->date;
            $path = $p->chemin;

            $result .= <<<HTML
                <hr>
                Titre de la photo : ${title}
                <br>
                <img src="${path}" alt="${title}">
                <br>
                Date de publication : ${date}
                <hr>
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