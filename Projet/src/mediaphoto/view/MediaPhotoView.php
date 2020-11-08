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
        $home = $router->urlFor('home');
        $login = $router->urlFor('viewLogin');
        $signup = $router->urlFor('viewSignup');
        $logout = $router->urlFor('viewLogout');
        $password = $router->urlFor('viewPassword');

        if(!$auth->logged_in) {
            $result = <<<HTML
            <nav>
                <div class="nav-link">
                    <a href="${home}">Accueil</a>
                </div>
                <a href="${home}">
                    <img class="logo" src="/html/assets/img/logo.png" alt="MediaPhoto" />
                </a>
                <div class="nav-setting">
                    <a href="${login}"><img src="/html/assets/img/login.svg" alt="Connexion">
                        <p>Connexion</p>
                    </a>
                    <a href="${signup}"><img src="/html/assets/img/signup.svg" alt="Inscription">
                        <p>Inscription</p>
                    </a>
                </div>
            </nav>
            HTML;
        } else {
            $result = <<<HTML
            <nav>
                <div class="nav-link">
                    <a href="#">Poster une photo</a>
                    <a href="#">Mes photos</a>
                </div>
                <a href="${home}">
                    <img class="logo" src="/html/assets/img/logo.png" alt="MediaPhoto" />
                </a>
                <div class="nav-setting">
                    <a href="${password}"><img src="/html/assets/img/setting.svg" alt="Paramètres">
                        <p>$_SESSION[user_login]</p>
                    </a>
                    <a href="${logout}"><img src="/html/assets/img/disconnect.svg" alt="Déconnexion">
                        <p>Déconnexion</p>
                    </a>
                </div>
            </nav>
            HTML;
        }

        return $result;
    }

    private function renderFooter() {
        return '<br><br>';
    }

    private function renderHome() {
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        $router = new \mf\router\Router();
        $galleries = $this->data;

        $result = <<<HTML
        <!-- Début bloc de recherche -->
          <article class="block-search">
              <h1>Bienvenue sur <strong>media photo</strong></h1>
              <form class="form-search" action="" method="post">
                  <div class="input-tb-submit">
                      <input type="text" name="search" placeholder="Rechercher..." />
                      <input type="submit" value="OK" />
                  </div>
                  <div class="form-select-filter">
                      <div class="checkbox-group">
                          <input checked type="checkbox" id="filter-image" name="filter" value="image">
                          <label for="filter-image">image</label>
                      </div>
                      <div class="checkbox-group">
                          <input type="checkbox" id="filter-gallerie" name="filter" value="gallerie">
                          <label for="filter-gallerie">gallerie</label>
                      </div>
                      <div class="checkbox-group">
                          <input type="checkbox" id="filter-tag" name="filter" value="tag">
                          <label for="filter-tag">tag</label>
                      </div>
                      <div class="checkbox-group">
                          <input type="checkbox" id="filter-user" name="filter" value="user">
                          <label for="filter-user">utilisateur</label>
                      </div>
                  </div>
              </form>
          </article>
          <!-- Fin bloc de recherche -->
        HTML;
                if($auth->logged_in) {
                    $userId = \mediaphoto\model\User::getLoggedUserId();
                    $userGalleries = \mediaphoto\model\Gallery::getUserGalleries($userId);

                    $result .= <<<HTML
                    <!-- Début liste de vos galeries -->
                    <article id="content-galerie" class="content-block">
                        <h1>Liste de vos galeries</h1>
                        <div class="block-list">
                    HTML;
                    foreach($userGalleries as $ug) {
                        $galleryId = $ug->id;
                        $titre = $ug->titre;
                        $link = $router->urlFor('viewGallery', array('id' => $galleryId));
                        $path = \mediaphoto\model\Photo::select('chemin')->where('id_galerie', '=', $galleryId)->LIMIT(1)->first()->chemin;
                        $nbPhotos = \mediaphoto\model\Photo::where('id_galerie', '=', $galleryId)->count();

                        $result .= <<<HTML
                        <div class="card">
                            <a href="${link}">
                                <div class="card-body">
                                    <img src="${path}" alt="${titre}">
                                    <p>${titre}</p>
                                </div>
                            </a>
                            <div class="card-footer">
                                <p>${nbPhotos} PHOTOS</p>
                            </div>
                        </div>
                        HTML;
                    }
                    $result .= <<<HTML
                    <div class="card-add">
                      <a href="#" title="Créer gallerie">
                          <img src="/html/assets/img/add.svg" alt="Créer gallerie" />
                            </a>
                        </div>
                    </div>
                </article>
                <!-- Fin liste de vos galeries -->
                HTML;
                }
                
        $result .= <<<HTML
        <article id="content-last-post" class="content-block">
            <h1>Dernières publications</h1>
            <div class="block-vignette">
        HTML;

        $limit = 15;
        $photos = \mediaphoto\model\Photo::select()->LIMIT($limit)->orderByDesc('id')->get();

        foreach($photos as $p) {
            $title = $p->titre;
            $path = $p->chemin;
            $link = $router->urlFor('viewPhoto', array('id' => $p->id));

            $result .= <<<HTML
            <a href="${link}">
                <img src="${path}" alt="${title}" />
                <div class="card-footer">
                    <p>${title}</p>
                </div>
            </a>
            HTML;
        }

        $result .= <<<HTML
        </div>
        <button class="btn-show-more">
            VOIR PLUS
            <img src="/html/assets/img/right_arrow.svg" alt="Voir plus" />
        </button>
        </article>
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
        $author = $gallery->author()->first()->nom_complet;
        
        $result = <<<HTML
        <center>
        <h1>Galerie : ${title}</h1>
        <div>
            <p>Description : ${desc}</p>
        </div>
        <hr>
        <p>Créé par : ${author}</p>
        <p>Taille totale de la galerie : ${size}</p>
        HTML;
        
        if($type == 3) {
            $get_share = $gallery->partage()->get();
            $share = [];
            foreach($get_share as $s) {
                $share[] = $gallery->getShareUsername($s->id)->nom_complet;
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

        $author = $photo->author()->first()->nom_complet;
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

    private function renderViewLogin() {
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
        <h1>Connexion</h1>
        <hr style="width: 70%">
        <form action="${checkLogin}" method="POST">
            <div>
                <label for="name">Nom d'utilisateur :</label>
                <input type="text" name="name" id="name">
            </div>
            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password">
            </div>
            <button name="submit" type="submit">Se connecter</button>
        </form>
        HTML;
        return $result;
    }

    private function renderViewSignup() {
        $router = new \mf\router\Router();
        $checkSignup = $router->urlFor('checkSignup');
        $site_name = self::$app_title;
        $result = '';

        if(isset($_SESSION['signup_error'])) {
            $message = $_SESSION['signup_error'][0];
            $color = $_SESSION['signup_error'][1];

            $result .= <<<HTML
            <p style="color:${color}">${message}</p>
            HTML;
        }

        $result .= <<<HTML
        <h1>Inscription à ${site_name}</h1>
        <hr style="width: 70%">
        <form action="${checkSignup}" method="POST">
            <div>
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" name="username" id="username">
            </div>
            <div>
                <label for="name">Nom complet :</label>
                <input type="text" name="name" id="name">
            </div>
            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password">
            </div>
            <div>
                <label for="password_confirmation">Confirmer le mot de passe :</label>
                <input type="password" name="password_confirmation" id="password_confirmation">
            </div>
            <button name="submit" type="submit">S'inscrire</button>
        </form>
        HTML;

        return $result;
    }

    private function renderViewPassword() {
        $router = new \mf\router\Router();
        $checkPassword = $router->urlFor('checkPassword');
        $result = '';

        if(isset($_SESSION['password_info'])) {
            $message = $_SESSION['password_info'][0];
            $color = $_SESSION['password_info'][1];

            $result .= <<<HTML
            <p style="color:${color}">${message}</p>
            HTML;
        }

        $result .= <<<HTML
        <h1>Modification de votre mot de passe</h1>
        <hr style="width: 70%">
        <div>
            <form action="${checkPassword}" method="POST">
                <div>
                    <label for="currentPassword">Mot de passe actuel :</label>
                    <input type="password" name="currentPassword" id="currentPassword">
                </div>
                <div>
                    <label for="newPassword">Nouveau mot de passe :</label>
                    <input type="password" name="newPassword" id="newPassword">
                </div>
                <div>
                    <label for="newPasswordConfirmation">Confirmer le nouveau mot de passe :</label>
                    <input type="password" name="newPasswordConfirmation" id="newPasswordConfirmation">
                </div>
                <button name="submit" type="submit">Changer de mot de passe</button>
            </form>
        </div>
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
        ${selecteur}
        <footer>${footer}</footer>
        HTML;

        return $html;
    }
}