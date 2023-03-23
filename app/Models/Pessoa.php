<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'CPF', 'email', 'perfil'];

    public function rules(){
        return [
            'nome' => 'required',
            'CPF' => 'required|unique:pessoas,CPF,'.$this->id,
            'email' => 'required',
            'perfil' => 'required'
        ];

    }


    public function feedback(){
        return [
            'required' => 'O campo atribuido é obrigatorio',
            'CPF.unique' => 'O CPF cadastrado já está vinculado a uma pessoa'
            ];

    }

    public function Enderecos() {
        //uma pessoa possui muitos endereços
        return $this->hasMany('App\Models\Endereco');
    }

}