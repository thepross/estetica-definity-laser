<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contador extends Model
{
    public function contarModel($id){
        $contar = Contador::find($id);
       $num = $contar->visitas+1;
       $contar->visitas=$num;
       $contar->save();
       return $num;
    }
}
