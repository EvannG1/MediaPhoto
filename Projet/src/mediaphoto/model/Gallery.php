<?php

namespace mediaphoto\model;

class Gallery extends \Illuminate\Database\Eloquent\Model {
    protected $table = 'galerie';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function author() {
        return $this->belongsTo('\mediaphoto\model\User', 'id');
    }

    public function photos() {
        return $this->hasMany('\mediaphoto\model\Photo', 'id_galerie');
    }

    public function partage() {
        return $this->hasMany('\mediaphoto\model\Share', 'id_galerie');
    }
}