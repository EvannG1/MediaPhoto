<?php

namespace mediaphoto\model;

class Photo extends \Illuminate\Database\Eloquent\Model {
    protected $table = 'photo';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function author() {
        return $this->belongsTo('\mediaphoto\model\User', 'id_utilisateur');
    }

    public function gallery() {
        return $this->belongsTo('\mediaphoto\model\Gallery', 'id_galerie');
    }

    public function getGalleryPhoto($id_galerie, $id, $nb) {
        return $this::select()->where([
            ['id_galerie', '=', $id_galerie],
            ['id', '!=', $id]
        ])->orderByDesc('id')->limit($nb)->get();
    }
}