<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'users';

    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = ['email', 'fecha', 'nombre','password','id_rol','id_empresa','state', 'telefono', 'estilo'];

    public function rol()
    {
        return $this->belongsTo(Role::class, 'id_rol');
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'id_user');
    }
}
