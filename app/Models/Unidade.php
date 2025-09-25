<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidade extends Model
{
    use HasFactory;

    protected $fillable = ['usuario_cadastrante_id','usuario_alterante_id','nome_fantasia', 'razao_social','cnpj','bandeira_id'];

    public function bandeira()
    {
        return $this->belongsTo(Bandeira::class);
    }
}
