<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;

    protected $fillable = ['pessoa_id','lagradouro', 'CEP'];

    public function rules(){
        return [
            'pessoa_id' => 'exists:pessoas,id',
            'lagradouro' => 'required',
            'CEP' => 'required'
        ];

    }
    public function pessoa() {
        //Uma pessoa pertence a um endereço
        return $this->belongsTo('App\Models\Pessoa');
    }
}
