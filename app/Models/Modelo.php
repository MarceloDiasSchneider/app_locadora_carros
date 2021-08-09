<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modelo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'marca_id',
        'modelo',
        'imagem',
        'numero_portas',
        'lugares',
        'air_bag',
        'abs',
    ];

    public function rules()
    {
        return [
            'marca_id' => 'required|exists:marcas,id',
            'modelo' => 'required|min:5|max:30|unique:modelos,modelo,' . $this->id,
            'imagem' => 'required|file|mimes:png',
            'numero_portas' => 'required|integer|between:2,5',
            'lugares' => 'required|integer|between:2,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean',
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute não é valido',
            'unique' => 'O campo :attribute já esta cadastrado',
            'file' => 'Imagem deve ser um arquivo',
            'mimes' => 'A extenção do campo :attribute não é valida',
            'integer' => 'O campo :attribute não é valido',
            'boolean' => 'O campo :attribute não é valido',
            'marca_id.exists' => 'O campo :attribute não é valido',
            'modelo.min' => 'O campo :attribute deve ter ao menos 5 caracteres',
            'modelo.max' => 'O campo :attribute deve ter até 30 caracteres',
            'modelo.unique' => 'O campo :attribute já foi cadastrado',
            'numero_portas.between' => 'O campo :attribute deve ser entre 2 e 5',
            'lugares.between' => 'O campo :attribute deve ser entre 2 e 20',
        ];
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function carros()
    {
        return $this->hasMany(Carro::class);
    }
}
