<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;



class Sector extends Model
{
    protected $table = 'sectores';

    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $primaryKey = 'id_preparacion';
    //public $incrementing = false;

}