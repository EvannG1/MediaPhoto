<?php

namespace mediaphoto\model;

class Photo extends \Illuminate\Database\Eloquent\Model {
    protected $table = 'photo';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function photos() {
        return $this->belongsTo('\mediaphoto\model\User', 'author');
    }

    public function galerie() {
        return $this->belongsTo('\mediaphoto\model\Gallery', 'id_galerie');
    }
}