<?php

namespace App\Http\Controllers;



use App\Http\Requests;
use Request;
use Illuminate\Support\Facades\DB;
use App\HamOcorrenciasAnvisa;
class NotificacaoController extends Controller
{
    public function cadastro(){
        return view('cadastro');
    }

    public function busca(  ){
        $codigo = Request::input('id');
        $sql = "SELECT DBAPORTAL.TIPO_OCORRENCIA.NM_TIPO_OCORRENCIA TIPO_DE_OCORRENCIA
                      ,DBAPORTAL.OCORRENCIA.NM_OCORRENCIA           OCORRENCIA
                      ,DBAPORTAL.REGISTRO_OCORRENCIA.DS_RESUMO      RESUMO             
                      ,DBAPORTAL.REGISTRO_OCORRENCIA.DH_OCORRIDO    DATA_OCORRENCIA        
                      ,(SELECT CENTRO_CUSTO.DS_CENTRO_CUSTO FROM DBAPORTAL.CENTRO_CUSTO WHERE DBAPORTAL.CENTRO_CUSTO.CD_CENTRO_CUSTO = DBAPORTAL.REGISTRO_OCORRENCIA.CD_CENTRO_CUSTO_REGISTRANTE) SETOR_REGISTRANTE
                      ,(SELECT CENTRO_CUSTO.DS_CENTRO_CUSTO FROM DBAPORTAL.CENTRO_CUSTO WHERE DBAPORTAL.CENTRO_CUSTO.CD_CENTRO_CUSTO = DBAPORTAL.REGISTRO_OCORRENCIA.CD_CENTRO_CUSTO_RELATOR)     SETOR_OCORRENCIA
                      ,(SELECT DS_PESSOA FROM DBAPORTAL.PESSOA WHERE DBAPORTAL.PESSOA.CD_PESSOA = DBAPORTAL.REGISTRO_OCORRENCIA.ID_USUARIO_REGISTRANTE) REGISTRANTE
                      ,DBAPORTAL.HAM_OCORRENCIAS_ANVISA.CD_NOTIVISA   
                      ,DBAPORTAL.HAM_OCORRENCIAS_ANVISA.CD_OCORRENCIA_ANVISA                         
                  FROM DBAPORTAL.REGISTRO_OCORRENCIA
                      ,DBAPORTAL.OCORRENCIA
                      ,DBAPORTAL.TIPO_OCORRENCIA  
                      ,DBAPORTAL.HAM_OCORRENCIAS_ANVISA      
                 WHERE DBAPORTAL.REGISTRO_OCORRENCIA.CD_OCORRENCIA =  DBAPORTAL.OCORRENCIA.CD_OCORRENCIA
                   AND DBAPORTAL.TIPO_OCORRENCIA.CD_TIPO_OCORRENCIA = DBAPORTAL.OCORRENCIA.CD_TIPO_OCORRENCIA
                   AND DBAPORTAL.HAM_OCORRENCIAS_ANVISA.CD_REGISTRO_OCORRENCIA(+) = DBAPORTAL.REGISTRO_OCORRENCIA.CD_OCORRENCIA
                   AND REGISTRO_OCORRENCIA.CD_REGISTRO_OCORRENCIA = ?";
        $data = DB::select($sql, array( $codigo ));
       // echo json_encode( $data );
        return response()->json( $data );

    }

    public function principal(){
        return view('opcao');
    }

    public function cadNotivisa(){
        $notivisa = Request::input('notivisa');
        $codigo = Request::input('codigo');

        $code = DB::selectOne("SELECT SEQ_OCORRENCIA_ANVISA.NEXTVAL as code FROM DUAL");

        $ocorrencia = new HamOcorrenciasAnvisa();
        $ocorrencia->CD_OCORRENCIA_ANVISA   = $code->code;
        $ocorrencia->CD_REGISTRO_OCORRENCIA = $codigo;
        $ocorrencia->CD_NOTIVISA = $notivisa;
        $ocorrencia->save();


/*
         $data = DB::table('HAM_OCORRENCIAS_ANVISA')->insertGetId(
             [

                 'CD_NOTIVISA'            => $notivisa,
                 'CD_REGISTRO_OCORRENCIA' => $codigo
             ], 'CD_OCORRENCIA_ANVISA'
         );*/
        //$id = DB::lastInsertId('SEQ_OCORRENCIA_ANVISA');
        //echo "Retorno: ".$ocorrencia->CD_OCORRENCIA_ANVISA;
        //var_dump($ocorrencia->toArray());
     //   echo "Retorno: ".$data;
        return response()->json( array( "success" => true, "id" => $ocorrencia->CD_OCORRENCIA_ANVISA ),200 );


    }
}
