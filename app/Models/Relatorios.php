<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relatorios extends Model
{
    use HasFactory;

    protected $fillable = ['titulo','descricao','tipo','data_inicio','data_fim',];

    public $timestamps = true;
}
