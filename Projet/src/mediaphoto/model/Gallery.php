<?php

namespace mediaphoto\model;

class Gallery extends \Illuminate\Database\Eloquent\Model {
    protected $table = 'galerie';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function galeries() {
        return $this->belongsTo('\mediaphoto\model\Gallery', 'author');
    }
}