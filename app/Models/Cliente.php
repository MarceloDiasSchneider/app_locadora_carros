<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nome',
    ];

    public function rules()
    {
        return [
            'nome' => 'required|max:30'
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute não é valido',
            'max' => 'O campo :attribute deve conter até 30 caracteres'
        ];
    }

    public function locacoes()
    {
        return $this->hasMany(Locacao::class);
    }
}
