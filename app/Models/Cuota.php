<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    use HasFactory;

    protected $table = 'cuotas';

    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = ['id_venta', 'id_plan_pago', 'monto', 'fecha_vencimiento', 'estado', 'state', 'pagofacil_transaction_id'];

    public function planPago()
    {
        return $this->belongsTo(PlanPago::class, 'id_plan_pago');
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta');
    }

    public function saldo()
    {
        return $this->monto - $this->pagos()->sum('monto');
    }

}
