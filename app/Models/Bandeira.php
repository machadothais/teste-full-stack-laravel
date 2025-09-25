<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bandeira extends Model
{
    use HasFactory;


    protected $fillable = ['usuario_cadastrante_id','usuario_alterante_id','nome','grupo_economico_id'];

    public function grupo()
    {
        return $this->belongsTo(GrupoEconomico::class, 'grupo_economico_id');
    }
}
