<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanPago extends Model
{
    protected $table = 'plan_pago';

    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = ['cantidad_cuotas', 'estado', 'fecha_inicio', 'total_deuda', 'saldo_restante', 'state', 'id_venta', 'monto_cuota'];

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_plan');
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta');
    }

    public function cuotas()
    {
        return $this->hasMany(Cuota::class, 'id_plan_pago')->orderBy('id', 'asc');
    }

}
