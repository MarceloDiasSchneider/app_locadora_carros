<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marca extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = [
        'marca',
        'imagem'
    ];

    public function rules()
    {
        return [
            'marca' => 'required|unique:marcas,marca,' . $this->id . '|max:30',
            'imagem' => 'required|max:100'
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute não é valido',
            'unique' => 'O campo :attribute já esta cadastrado',
            'marca.max' => 'O campo marca deve conter até 30 caracteres',
            'imagem.max' => 'O campo imagem deve conter até 100 caracteres'
        ];
    }
}
