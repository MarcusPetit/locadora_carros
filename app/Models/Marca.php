<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'imagem'];

    public function regras()
    {
        return [
            'nome' => 'required|string|unique:marcas,nome,'.$this->id,
            'imagem' => 'required|file|mines:png,jpg',
        ];

    }

    public function feedback()
    {
        return [
            'required' => 'O campo esta sem atributos',
            'imagem.mines' => 'imagem precisa ser png ou jpg',
            'nome.unique' => 'O nome da marca ja existe',
        ];

    }
}
