<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Privilegio extends Model
{
    protected $table = 'privilegios';

    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = ['funcionalidad','id_rol','agregar','borrar','modificar','leer','state'];

    public function rol()
    {
        return $this->belongsTo(Role::class, 'id_rol');
    }
}
