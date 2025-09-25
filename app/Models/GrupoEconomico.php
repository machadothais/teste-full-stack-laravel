<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoEconomico extends Model
{
    use HasFactory;
    protected $table = 'grupos_economicos';
    protected $fillable = ['usuario_cadastrante_id','usuario_alterante_id','nome'];
}
