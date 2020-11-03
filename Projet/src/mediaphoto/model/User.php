<?php

namespace mediaphoto\model;

class User extends \Illuminate\Database\Eloquent\Model {
    protected $table = 'utilisateur';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function galeries() {
        return $this->belongsTo('\mediaphoto\model\Gallery', 'author');
    }
}