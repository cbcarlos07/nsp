@extends( 'principal' );
@section( 'title', 'Cadastro')
@section( 'subtitle', 'Deseja realmente sair do sistema?')
@section( 'content' )

    <div class="row col-lg-12">


        <div class="col-lg-6"  >
            <div class="col-lg-6"></div>
            <div class="col-lg-6">
                <a href="{{ action('UsuarioController@sair') }}" class="btn btn-lg btn-primary btn-sim" style="float: right; width: 150px;" title="Confirmar sa&iacute;da do sistema">Sim</a>
            </div>
        </div>
        <div class="col-lg-6"  >

            <div class="col-lg-6">
                <a href="{{ action('NotificacaoController@principal') }}" class="btn btn-lg btn-danger btn-nao" style="float: left; width: 150px;" title="Voltar &agrave; tela inicial">N&atilde;o</a>
            </div>
            <div class="col-lg-6"></div>
        </div>


    </div>



@stop