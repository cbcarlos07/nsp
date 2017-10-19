<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HamOcorrenciasAnvisa extends Model
{
    protected $table = "HAM_OCORRENCIAS_ANVISA";
    public $timestamps = false;
    protected $primaryKey = 'CD_OCORRENCIA_ANVISA';
    public $incrementing = false;
    /*protected $fillable = ['CD_REGISTRO_OCORRENCIA', 'CD_NOTIVISA'];*/
    public $sequence = 'SEQ_OCORRENCIA_ANVISA';


}
