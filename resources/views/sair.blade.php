@extends( 'principal' );
@section( 'title', 'Cadastro')
@section( 'subtitle', 'Deseja realmente sair do sistema?')
@section( 'content' )
    <div class="row"></div>
    <div class="col-lg-12"></div>
    <div class="row col-lg-12">


        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"  style=" text-align: center">


                <a href="{{ action('UsuarioController@sair') }}" class="btn btn-lg btn-primary btn-sim" style="width: 150px; " title="Confirmar sa&iacute;da do sistema">Sim</a>

        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="text-align: center" >


                <a href="{{ action('NotificacaoController@principal') }}" class="btn btn-lg btn-danger btn-nao" style=" width: 150px;" title="Voltar &agrave; tela inicial">N&atilde;o</a>


        </div>


    </div>



@stop