<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carro extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'modelo_id',
        'placa',
        'disponivel',
        'km',
    ];

    public function rules()
    {
        return [
            'modelo_id' => 'required|exists:modelos,id',
            'placa' => 'required|min:3|max:12|unique:carros,placa,' . $this->id,
            'disponive' => 'boolean',
            'km' => 'integer',
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatorio',
            'unique' => 'O campo :attribute já esta cadastrado',
            'boolean' => 'O campo :attribute não é valido',
            'integer' => 'O campo :attribute não é valido',
            'modelo_id.exists' => 'O campo :attribute não é valido',
            'placa.min' => 'O campo :attribute deve ter ao menos 3 caracteres',
            'placa.max' => 'O campo :attribute deve ter até 10 caracteres',
        ];
    }

    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }
}
