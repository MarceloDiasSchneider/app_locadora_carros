<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Marca extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'marca',
        'imagem'
    ];

    public function rules()
    {
        return [
            'marca' => 'required|unique:marcas,marca,' . $this->id . '|max:30',
            'imagem' => 'required|file|mimes:png'
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute não é valido',
            'unique' => 'O campo :attribute já esta cadastrado',
            'file' => 'Imagem deve ser um arquivo',
            'mimes' => 'A extenção do campo :attribute não é valida',
            'marca.max' => 'O campo marca deve conter até 30 caracteres'
        ];
    }

    public function modelos()
    {
        return $this->hasMany(Modelo::class);
    }
}
