<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'id_categoria',
        'nombre',
        'descripcion',
        'precio',
        'state',
        'fecha_ingreso',
        'id_promocion',
        'stock'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function promocion()
    {
        return $this->belongsTo(Promocion::class, 'id_promocion');
    }

    public function precioConDescuento()
    {
        if ($this->promocion && $this->promocion->descuento > 0) {
            $descuento = $this->promocion->descuento; // porcentaje
            return $this->precio - ($this->precio * $descuento / 100);
        }
        return $this->precio;
    }

}
