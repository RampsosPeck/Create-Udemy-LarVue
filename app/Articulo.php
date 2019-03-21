<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $fillable = ['idcategoria','codigo','nombre','precio_venta','stock','descripcion','condicion'];

    public function catedoria()
    {
    	return $this->belongsTo('App0\Categoria');
    }
}
