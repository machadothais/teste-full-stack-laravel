<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colaborador extends Model
{
    use HasFactory;
    protected $table = 'colaboradores';
    protected $fillable = ['usuario_cadastrante_id','usuario_alterante_id','nome', 'email', 'cpf', 'unidade_id'];

    public function unidades()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id');
    }
}

