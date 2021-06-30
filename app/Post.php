<?php

namespace level1_project;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // Table name
    // protected $table = 'posts';
    // public $timestamps = false;
    // protected $primaryKey = 'id';
    
    // Creating Relationships

    public function user()
    {
        return $this->belongsTo('level1_project\User');
    }
}
