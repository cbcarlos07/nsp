<?php

namespace App\Http\Controllers;



use App\Http\Requests;
use Request;
use Illuminate\Support\Facades\DB;
use App\HamOcorrenciasAnvisa;
use Illuminate\Support\Facades\Session;
use Charts;
class NotificacaoController extends Controller
{
    public function cadastro(){
       // if( Session::has('usuario') )
            return view('cadastro');
        /*else
            return redirect(  )->action( 'UsuarioController@login' );*/
    }

    public function busca(  ){
        $codigo = Request::input('id');
        $sql = "SELECT REGISTRO_OCORRENCIA.CD_REGISTRO_OCORRENCIA   COD_REGISTRO
                      ,DBAPORTAL.TIPO_OCORRENCIA.NM_TIPO_OCORRENCIA TIPO_DE_OCORRENCIA
                      ,DBAPORTAL.OCORRENCIA.NM_OCORRENCIA           OCORRENCIA
                      ,DBAPORTAL.REGISTRO_OCORRENCIA.DS_RESUMO      RESUMO             
                      ,TO_CHAR(DBAPORTAL.REGISTRO_OCORRENCIA.DH_OCORRIDO, 'DD/MM/YYYY')    DATA_OCORRENCIA        
                      ,(SELECT CENTRO_CUSTO.DS_CENTRO_CUSTO FROM DBAPORTAL.CENTRO_CUSTO WHERE DBAPORTAL.CENTRO_CUSTO.CD_CENTRO_CUSTO = DBAPORTAL.REGISTRO_OCORRENCIA.CD_CENTRO_CUSTO_REGISTRANTE) SETOR_REGISTRANTE
                      ,(SELECT CENTRO_CUSTO.DS_CENTRO_CUSTO FROM DBAPORTAL.CENTRO_CUSTO WHERE DBAPORTAL.CENTRO_CUSTO.CD_CENTRO_CUSTO = DBAPORTAL.REGISTRO_OCORRENCIA.CD_CENTRO_CUSTO_RELATOR)     SETOR_OCORRENCIA
                      ,(SELECT DS_PESSOA FROM DBAPORTAL.PESSOA WHERE DBAPORTAL.PESSOA.CD_PESSOA = DBAPORTAL.REGISTRO_OCORRENCIA.ID_USUARIO_REGISTRANTE) REGISTRANTE    
                      ,DBAPORTAL.FLUXO_OCORRENCIA.NM_FLUXO_OCORRENCIA STATUS      
                      ,DBAPORTAL.HAM_OCORRENCIAS_ANVISA.CD_NOTIVISA
                      ,DBAPORTAL.HAM_OCORRENCIAS_ANVISA.CD_OCORRENCIA_ANVISA                
                  FROM DBAPORTAL.REGISTRO_OCORRENCIA
                      ,DBAPORTAL.OCORRENCIA
                      ,DBAPORTAL.TIPO_OCORRENCIA      
                      ,DBAPORTAL.HAM_OCORRENCIAS_ANVISA  
                      ,DBAPORTAL.FLUXO_OCORRENCIA
                      ,DBAPORTAL.FLUXO_QUADRO_OCORRENCIA
                 WHERE DBAPORTAL.REGISTRO_OCORRENCIA.CD_OCORRENCIA               = DBAPORTAL.OCORRENCIA.CD_OCORRENCIA
                   AND DBAPORTAL.TIPO_OCORRENCIA.CD_TIPO_OCORRENCIA              = DBAPORTAL.OCORRENCIA.CD_TIPO_OCORRENCIA
                   AND DBAPORTAL.HAM_OCORRENCIAS_ANVISA.CD_REGISTRO_OCORRENCIA(+)=DBAPORTAL.REGISTRO_OCORRENCIA.CD_REGISTRO_OCORRENCIA
                   AND DBAPORTAL.FLUXO_QUADRO_OCORRENCIA.CD_REGISTRO_OCORRENCIA  = DBAPORTAL.REGISTRO_OCORRENCIA.CD_REGISTRO_OCORRENCIA
                   AND DBAPORTAL.FLUXO_QUADRO_OCORRENCIA.CD_FLUXO_OCORRENCIA     = DBAPORTAL.FLUXO_OCORRENCIA.CD_FLUXO_OCORRENCIA
                   AND (DBAPORTAL.FLUXO_QUADRO_OCORRENCIA.TP_SITUACAO            = 'D' 
                     OR DBAPORTAL.FLUXO_QUADRO_OCORRENCIA.TP_SITUACAO = 'C'    
                    AND DBAPORTAL.FLUXO_QUADRO_OCORRENCIA.CD_FLUXO_OCORRENCIA = 8)
                   AND REGISTRO_OCORRENCIA.CD_REGISTRO_OCORRENCIA                = ?";

     //   $ocorrencias = DB::select( 'SELECT * FROM HAM_OCORRENCIAS_ANVISA WHERE CD_REGISTRO_OCORRENCIA = ?', array( $codigo ) );
       // $ocorrencias = HamOcorrenciasAnvisa::where( 'CD_REGISTRO_OCORRENCIA',$codigo )->get();
      //  var_dump( $ocorrencias );
        $data = DB::select($sql, array( $codigo ));

       // echo json_encode( $data );
        return response()->json( array( $data ) );

    }

    public function principal(){
        //if( Session::has('usuario' ) )
            return view('opcao');
        /*else
            return redirect(  )->action( 'UsuarioController@login' );*/
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

    public function altNotivisa(  ){
        $id = Request::input('id');
        $notivisa = Request::input('notivisa');
        $codigo = Request::input('codigo');
        $ocorrencia =  HamOcorrenciasAnvisa::find( $id );
        $ocorrencia->CD_OCORRENCIA_ANVISA = $id;
        $ocorrencia->CD_REGISTRO_OCORRENCIA = $codigo;
        $ocorrencia->CD_NOTIVISA = $notivisa;
        $ocorrencia->save();

        return response()->json( array( "success" => true, "id" => $id ),200 );
    }

    public function relatorio(){
        if( Session::has('usuario' ) )
            return view('relatorio');
        else
            return redirect(  )->action( 'UsuarioController@login' );
    }

    public function consulta(  ){
        $inicio = Request::input('inicio');
        $fim = Request::input('fim');
        $sql = "SELECT REGISTRO_OCORRENCIA.CD_REGISTRO_OCORRENCIA   COD_REGISTRO
                      ,DBAPORTAL.TIPO_OCORRENCIA.NM_TIPO_OCORRENCIA TIPO_DE_OCORRENCIA
                      ,DBAPORTAL.OCORRENCIA.NM_OCORRENCIA           OCORRENCIA
                      ,Decode(DBAPORTAL.OCORRENCIA.NM_OCORRENCIA,'Infecção de foco abdominal','Infecção'
                                               ,'IPCS Clínica','Infecção'
                                               ,'IPCS Laboratorial','Infecção'
                                               ,'Bacteremia','Infecção'
                                               ,'Falha na prevenção da ITU ','Infecção'
                                               ,'Falha na prevenção da PAV','Infecção'
                                               ,'Pneumonia hospitalar','Infecção'
                                               ,'Traqueobronquite','Infecção'
                                               ,'Infecção de sítio cirúrgico','Infecção'
                                               ,'Traqueíte','Infecção'
                                               ,DBAPORTAL.OCORRENCIA.NM_OCORRENCIA) PROTOCOLO
                      ,DBAPORTAL.REGISTRO_OCORRENCIA.DS_RESUMO      RESUMO             
                      ,TO_CHAR(DBAPORTAL.REGISTRO_OCORRENCIA.DH_OCORRIDO, 'DD/MM/YYYY' )    DATA_OCORRENCIA        
                      ,(SELECT CENTRO_CUSTO.DS_CENTRO_CUSTO FROM DBAPORTAL.CENTRO_CUSTO WHERE DBAPORTAL.CENTRO_CUSTO.CD_CENTRO_CUSTO = DBAPORTAL.REGISTRO_OCORRENCIA.CD_CENTRO_CUSTO_REGISTRANTE) SETOR_REGISTRANTE
                      ,(SELECT CENTRO_CUSTO.DS_CENTRO_CUSTO FROM DBAPORTAL.CENTRO_CUSTO WHERE DBAPORTAL.CENTRO_CUSTO.CD_CENTRO_CUSTO = DBAPORTAL.REGISTRO_OCORRENCIA.CD_CENTRO_CUSTO_RELATOR)     SETOR_OCORRENCIA
                      ,(SELECT DS_PESSOA FROM DBAPORTAL.PESSOA WHERE DBAPORTAL.PESSOA.CD_PESSOA = DBAPORTAL.REGISTRO_OCORRENCIA.ID_USUARIO_REGISTRANTE) REGISTRANTE    
                      ,DBAPORTAL.FLUXO_OCORRENCIA.NM_FLUXO_OCORRENCIA STATUS      
                      ,DBAPORTAL.HAM_OCORRENCIAS_ANVISA.CD_NOTIVISA
                      ,CASE
                         WHEN DBAPORTAL.TIPO_OCORRENCIA.CD_TIPO_OCORRENCIA = 71
                           THEN 'Evento'
                         WHEN DBAPORTAL.TIPO_OCORRENCIA.CD_TIPO_OCORRENCIA = 76
                           THEN 'Near Miss'
                         WHEN DBAPORTAL.TIPO_OCORRENCIA.CD_TIPO_OCORRENCIA = 69
                           THEN 'Circunstância de Risco'
                         WHEN DBAPORTAL.TIPO_OCORRENCIA.CD_TIPO_OCORRENCIA = 75
                           THEN 'Normas relacionadas à Excelência em Servir'
                       END TIPO_DE_OCORRENCIA                
                  FROM DBAPORTAL.REGISTRO_OCORRENCIA
                      ,DBAPORTAL.OCORRENCIA
                      ,DBAPORTAL.TIPO_OCORRENCIA      
                      ,DBAPORTAL.HAM_OCORRENCIAS_ANVISA  
                      ,DBAPORTAL.FLUXO_OCORRENCIA
                      ,DBAPORTAL.FLUXO_QUADRO_OCORRENCIA
                 WHERE DBAPORTAL.REGISTRO_OCORRENCIA.CD_OCORRENCIA               = DBAPORTAL.OCORRENCIA.CD_OCORRENCIA
                   AND DBAPORTAL.TIPO_OCORRENCIA.CD_TIPO_OCORRENCIA              = DBAPORTAL.OCORRENCIA.CD_TIPO_OCORRENCIA
                   AND DBAPORTAL.HAM_OCORRENCIAS_ANVISA.CD_REGISTRO_OCORRENCIA   = DBAPORTAL.REGISTRO_OCORRENCIA.CD_REGISTRO_OCORRENCIA
                   AND DBAPORTAL.FLUXO_QUADRO_OCORRENCIA.CD_REGISTRO_OCORRENCIA  = DBAPORTAL.REGISTRO_OCORRENCIA.CD_REGISTRO_OCORRENCIA
                   AND DBAPORTAL.FLUXO_QUADRO_OCORRENCIA.CD_FLUXO_OCORRENCIA     = DBAPORTAL.FLUXO_OCORRENCIA.CD_FLUXO_OCORRENCIA
                   AND (DBAPORTAL.FLUXO_QUADRO_OCORRENCIA.TP_SITUACAO            = 'D' 
                     OR DBAPORTAL.FLUXO_QUADRO_OCORRENCIA.TP_SITUACAO = 'C'    
                    AND DBAPORTAL.FLUXO_QUADRO_OCORRENCIA.CD_FLUXO_OCORRENCIA = 8)
                   AND TRUNC(DBAPORTAL.REGISTRO_OCORRENCIA.DH_OCORRIDO) BETWEEN TO_DATE(?, 'DD/MM/YYYY') AND TO_DATE(?, 'DD/MM/YYYY')
                   ORDER BY DBAPORTAL.REGISTRO_OCORRENCIA.DH_OCORRIDO DESC";
        $dados = DB::select( $sql, array( $inicio, $fim ) );

        return response()->json( $dados );
    }

    public function beforeExit(){
        //if( Session::has('usuario' ) )
            return view('sair');
        /*else
            return redirect(  )->action( 'UsuarioController@login' );*/

    }

    public function getValores(){
        $inicio = Request::input('inicio');
        $fim = Request::input('fim');
       $sql = "SELECT DECODE(PROTOCOLO,'2 - Medicamento / Erro na Administração',SUBSTR(PROTOCOLO,5,50),PROTOCOLO) PROTOCOLO
                      ,COUNT(*) TOTAL
                      ,TOTAL_GERAL
                      ,TO_CHAR((COUNT(*)/TOTAL_GERAL)*100,'990D99')||' %' TAXA
                      ,OCORRENCIA.DATA_OCORRENCIA
                  FROM (
                
                                SELECT Decode(DBAPORTAL.OCORRENCIA.NM_OCORRENCIA,'Infecção de foco abdominal','Infecção'
                                                               ,'IPCS Clínica','Infecção'
                                                               ,'IPCS Laboratorial','Infecção'
                                                               ,'Bacteremia','Infecção'
                                                               ,'Falha na prevenção da ITU ','Infecção'
                                                               ,'Falha na prevenção da PAV','Infecção'
                                                               ,'Pneumonia hospitalar','Infecção'
                                                               ,'Traqueobronquite','Infecção'
                                                                ,'Infecção de sítio cirúrgico','Infecção'
                                                               ,'Traqueíte','Infecção'
                                                               ,DBAPORTAL.OCORRENCIA.NM_OCORRENCIA) PROTOCOLO
                                                               ,TO_CHAR(DBAPORTAL.REGISTRO_OCORRENCIA.DH_OCORRIDO,'MM/YYYY') DATA_OCORRENCIA
                                  FROM DBAPORTAL.REGISTRO_OCORRENCIA
                                      ,DBAPORTAL.OCORRENCIA
                                      ,DBAPORTAL.TIPO_OCORRENCIA      
                                      ,DBAPORTAL.HAM_OCORRENCIAS_ANVISA  
                                      ,DBAPORTAL.FLUXO_OCORRENCIA
                                      ,DBAPORTAL.FLUXO_QUADRO_OCORRENCIA
                                 WHERE DBAPORTAL.REGISTRO_OCORRENCIA.CD_OCORRENCIA               = DBAPORTAL.OCORRENCIA.CD_OCORRENCIA
                                   AND DBAPORTAL.TIPO_OCORRENCIA.CD_TIPO_OCORRENCIA              = DBAPORTAL.OCORRENCIA.CD_TIPO_OCORRENCIA
                                   AND DBAPORTAL.HAM_OCORRENCIAS_ANVISA.CD_REGISTRO_OCORRENCIA   = DBAPORTAL.REGISTRO_OCORRENCIA.CD_REGISTRO_OCORRENCIA
                                   AND DBAPORTAL.FLUXO_QUADRO_OCORRENCIA.CD_REGISTRO_OCORRENCIA  = DBAPORTAL.REGISTRO_OCORRENCIA.CD_REGISTRO_OCORRENCIA
                                   AND DBAPORTAL.FLUXO_QUADRO_OCORRENCIA.CD_FLUXO_OCORRENCIA     = DBAPORTAL.FLUXO_OCORRENCIA.CD_FLUXO_OCORRENCIA
                                   AND (DBAPORTAL.FLUXO_QUADRO_OCORRENCIA.TP_SITUACAO            = 'D' 
                                     OR DBAPORTAL.FLUXO_QUADRO_OCORRENCIA.TP_SITUACAO = 'C'    
                                    AND DBAPORTAL.FLUXO_QUADRO_OCORRENCIA.CD_FLUXO_OCORRENCIA = 8)
                                   AND TRUNC(DBAPORTAL.REGISTRO_OCORRENCIA.DH_OCORRIDO) BETWEEN TO_DATE(?, 'DD/MM/YYYY') AND TO_DATE(?, 'DD/MM/YYYY')
                                   )OCORRENCIA
                                   
                               ,(
                
                                SELECT COUNT(*) TOTAL_GERAL
                                     ,TO_CHAR(DBAPORTAL.REGISTRO_OCORRENCIA.DH_OCORRIDO,'MM/YYYY') DATA_OCORRENCIA
                                  FROM DBAPORTAL.REGISTRO_OCORRENCIA
                                      ,DBAPORTAL.OCORRENCIA
                                      ,DBAPORTAL.TIPO_OCORRENCIA      
                                      ,DBAPORTAL.HAM_OCORRENCIAS_ANVISA  
                                      ,DBAPORTAL.FLUXO_OCORRENCIA
                                      ,DBAPORTAL.FLUXO_QUADRO_OCORRENCIA
                                 WHERE DBAPORTAL.REGISTRO_OCORRENCIA.CD_OCORRENCIA               = DBAPORTAL.OCORRENCIA.CD_OCORRENCIA
                                   AND DBAPORTAL.TIPO_OCORRENCIA.CD_TIPO_OCORRENCIA              = DBAPORTAL.OCORRENCIA.CD_TIPO_OCORRENCIA
                                   AND DBAPORTAL.HAM_OCORRENCIAS_ANVISA.CD_REGISTRO_OCORRENCIA   = DBAPORTAL.REGISTRO_OCORRENCIA.CD_REGISTRO_OCORRENCIA
                                   AND DBAPORTAL.FLUXO_QUADRO_OCORRENCIA.CD_REGISTRO_OCORRENCIA  = DBAPORTAL.REGISTRO_OCORRENCIA.CD_REGISTRO_OCORRENCIA
                                   AND DBAPORTAL.FLUXO_QUADRO_OCORRENCIA.CD_FLUXO_OCORRENCIA     = DBAPORTAL.FLUXO_OCORRENCIA.CD_FLUXO_OCORRENCIA
                                   AND (DBAPORTAL.FLUXO_QUADRO_OCORRENCIA.TP_SITUACAO            = 'D' 
                                     OR DBAPORTAL.FLUXO_QUADRO_OCORRENCIA.TP_SITUACAO = 'C'    
                                    AND DBAPORTAL.FLUXO_QUADRO_OCORRENCIA.CD_FLUXO_OCORRENCIA = 8)
                                   AND TRUNC(DBAPORTAL.REGISTRO_OCORRENCIA.DH_OCORRIDO) BETWEEN TO_DATE(?, 'DD/MM/YYYY') AND TO_DATE(?, 'DD/MM/YYYY')
                                   GROUP BY TO_CHAR(DBAPORTAL.REGISTRO_OCORRENCIA.DH_OCORRIDO,'MM/YYYY')                 
                
                                   )TOTAL
                
                    WHERE OCORRENCIA.DATA_OCORRENCIA = TOTAL.DATA_OCORRENCIA
                GROUP BY PROTOCOLO
                        ,TOTAL_GERAL  
                        ,OCORRENCIA.DATA_OCORRENCIA              
                 ORDER BY DATA_OCORRENCIA
                ";

        $dados = DB::select( $sql, array( $inicio, $fim ) );
        $periodo = array();
        foreach ( $dados as $dado ){
            $mes = $dado->data_ocorrencia;
            $protocolo[] = array();
            $total[] = array();
            while ( $mes == $dado->data_ocorrencia ){

                $protocolo[] = array(
                    $dado->protocolo
                );

                $total[] = array(
                   $dado->total
                );

                $mes = $dado->data_ocorrencia;
            }
            $chart = Charts::create('pie', 'highcharts')
                ->title($mes) // Título do gráfico
                ->labels($protocolo) // Propriedades que vão ser adicionadas
                ->values($total) // Valores das propriedades
                ->dimensions(500, 300) // Dimensão = 500 largura x 300 altura
                ->responsive(false) // É utilizado para se adaptar ao tamanho do box que se encontra
                ->elementLabel("Total de vendas"); // Legenda para o gráfico caso seja gráfico do tipo barra

            $periodo[] = array(
                'chart' => $chart
            );

        }

        return view('reports.index', ['chart' => $chart]);

       // return response()->json( $dados );
    }


}
