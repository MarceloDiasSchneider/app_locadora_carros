<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locacao extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'locacoes';
    protected $fillable = [
        'cliente_id',
        'carro_id',
        'data_inicio',
        'data_final_previsto',
        'data_final_realizado',
        'valor_diaria',
        'km_inicial',
        'km_final',
    ];

    public function rules()
    {
        return [
            'cliente_id' => 'required|exists:clientes,id',
            'carro_id' => 'required|exists:carros,id',
            'data_inicio' => 'required|date|after:today',
            'data_final_previsto' => 'required|date|after:data_inicio',
            'data_final_realizado' => 'date|after:data_inicio',
            'valor_diaria' => 'required|numeric',
            'km_inicial' => 'integer',
            'km_final' => 'integer|gt:km_inicial',
        ];
    }

    public function feedback()
    {
        return [
            'exists' => 'O campo :attribute não é valido',
            'required' => 'O campo :attribute é obrigatorio',
            'date' => 'O campo :attribute é valido',
            'date' => 'O campo :attribute é valido',
            'after' => 'O campo :attribute é valido',
            'digits' => 'O campo :attribute é valido',
            'integer' => 'O campo :attribute deve ser um numero',
            'km_final.gt' => 'O campo :attribute deve ser um numero maior que km inicial',
        ];
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function carro()
    {
        return $this->belongsTo(Carro::class);
    }
}
