@extends( 'principal' );
@section( 'title', 'Cadastro')
@section( 'subtitle', 'Cadastro de Notificações | NSP')
@section( 'content' )

    <div class="row col-lg-12">

        <input type="hidden" id="token" value="{{ csrf_token() }}">
        <input type="hidden" id="anvisa" value="0">
        <div class="col-lg-6" >
            <div class="form-group col-lg-6">
                <label for="codigo">C&oacute;digo da notifica&ccedil;&atilde;o</label>
                <input id="codigo" class="form-control">
            </div>
            <div class="col-lg-2">
                <button class="btn btn-primary btn-pesq" style="margin-top: 25px;">Pesquisar</button>

            </div>
            <div class="col-lg-3" style="font-size: 12px; margin-left: 10px; margin-top: 35px;">
                <a href="#reset" id="reset" ></a>
            </div>



            {{--<a href="{{ action('NotificacaoController@busca', 7275) }}" class="btn btn-primary btn-pesq" style="margin-top: 20px;">Pesquisar</a>--}}

        </div>
        <div class="col-lg-6">
            <div class="form-group col-lg-6">
                <label for="notivisa">C&oacute;digo NOTIVISA</label>
                <input id="notivisa" class="form-control" disabled="">
            </div>
            <button class="btn btn-success btn-save" style="margin-top: 20px;" disabled="">Salvar</button>
        </div>
        <div class="row"></div>
        <div class="col-lg-12 tabela" style="display: none">

        </div>

    </div>
    {{--style="bottom: 30px; position: fixed; right: 280px;"--}}
    <p class="alert alert-success alerta" style="bottom: 30px; position: fixed; right: -280px;" >

        <b>Well done!</b> Save successfully!
        <button type="button" class="close"  aria-hidden="true" style="right: 0; top: -20px;"> &times;</button>
    </p>
    <script src="{{ URL('theme/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script>
        $(document).ready( function () {
            $('#codigo').focus();
        } );
        $('.btn-pesq').on('click', function () {
            var id = $('#codigo').val();
            var token = $('#token').val();
            var div = $('.tabela');
            var lnk = $('#reset');
            var campoNotivisa = $('#notivisa');
            campoNotivisa.val('');
            div.empty();
            $.ajax({
                url  : './busca',
                type : 'post',
                dataType: 'json',
                data : {
                    id     : id,
                    _token : token
                },
                success: function ( data ) {
                    console.log( data );
                    lnk.text('Reiniciar pesquisa');

                    lnk.fadeIn();
                    if( data.length > 0 ){
                        //console.log('Maior que zero');
                        $('input[id="notivisa"]').removeAttr( 'disabled' );

                        $('#notivisa').focus();
                        var linha = '<div class="panel panel-default">' +
                                    ' <div class="panel-heading">Dados encontrados</div> '+
                                    ' <div class="panel-body">'+
                                    '   <div class="form-group ">'+
                                    '      <label for="cdos" class="col-md-2 control-label">Tipo de Ocorr&ecirc;ncia</label>'+
                                    '      <div class="col-md-9"> '+
                                    '         <input type="text" class="form-control" id="cdos" placeholder="C&oacute;d" disabled value="'+ data[0]['tipo_de_ocorrencia'] +'">'+
                                    '      </div>'+
                                    '   </div>'+
                                    '   <div class="form-group ">'+
                                    '      <label for="cdos" class="col-md-2 control-label">Ocorr&ecirc;ncia </label>'+
                                    '      <div class="col-md-9"> '+
                                    '         <input type="text" class="form-control" id="cdos" placeholder="C&oacute;d" disabled value="'+ data[0]['ocorrencia'] +'">'+
                                    '      </div>'+
                                    '   </div>'+
                                    '   <div class="form-group ">'+
                                    '      <label for="cdos" class="col-md-2 control-label">Resumo</label>'+
                                    '      <div class="col-md-9"> '+
                                    '        <input type="text" class="form-control" id="cdos" placeholder="C&oacute;d" disabled value="'+ data[0]['resumo'] +'">'+
                                    '      </div>'+
                                    '   </div>'+
                                    '   <div class="form-group ">'+
                                    '      <label for="cdos" class="col-md-2 control-label">Data da Ocorr&ecirc;ncia </label>'+
                                    '      <div class="col-md-9"> '+
                                    '         <input type="text" class="form-control" id="cdos" placeholder="C&oacute;d" disabled value="'+ data[0]['data_ocorrencia'] +'">'+
                                    '      </div>'+
                                    '   </div>'+
                                    '   <div class="form-group ">'+
                                    '       <label for="cdos" class="col-md-2 control-label">Setor registrante</label>'+
                                    '       <div class="col-md-9"> '+
                                    '          <input type="text" class="form-control" id="cdos" placeholder="C&oacute;d" disabled value="'+ data[0]['setor_registrante'] +'">'+
                                    '       </div>'+
                                    '    </div>'+
                                    '    <div class="form-group ">'+
                                    '    <label for="cdos" class="col-md-2 control-label">Setor de Ocorr&ecirc;ncia </label>'+
                                    '      <div class="col-md-9"> '+
                                    '        <input type="text" class="form-control" id="cdos" placeholder="C&oacute;d" disabled value="'+ data[0]['setor_ocorrencia'] +'">'+
                                    '      </div>'+
                                    '    </div>'+
                                    '    <div class="form-group ">'+
                                    '       <label for="cdos" class="col-md-2 control-label">Registrante</label>'+
                                    '       <div class="col-md-9"> '+
                                    '         <input type="text" class="form-control" id="cdos" placeholder="C&oacute;d" disabled value="'+ data[0]['registrante'] +'">'+
                                    '       </div>'+
                                    '    </div>'+
                                    ' </div>'+
                                    '</div>';
                                    $('#notivisa').val( data[0]['cd_notivisa'] );
                                    $('#anvisa').val( data[0]['cd_ocorrencia_anvisa'] );

                        div.append( linha );
                        div.fadeIn();

                        if( data[0]['cd_notivisa'] != null ){
                            $('#notivisa').removeAttr( 'disabled' );
                            $('.btn-save').removeAttr( 'disabled' );
                        }else{
                            $('.btn-save').attr( 'disabled', true );
                        }

                    }else{
                       // console.log('menor que zero');
                        div.fadeIn();
                        $('input[id="notivisa"]').attr( 'disabled', true );
                        $(".btn-save").attr( 'disabled', true );
                        div.append(
                            '<p class="alert alert-danger">A ocorr&ecirc;ncia n&atilde;o existe!</p>'
                        );

                    }

                }
            })
        });

        $('#reset').on('click', function () {
            var div = $('.tabela');
            var codigo = $("#codigo");
            var notivisa = $("#notivisa");
            var anvisa = $("#anvisa");
            div.fadeOut();
            div.append('');
            var lnk = $('#reset');
            lnk.text('');
            lnk.fadeOut();
            codigo.val('');
            notivisa.val('');
            anvisa.val(0);
            codigo.focus();

        });

        $('#codigo').on('keypress', function (e) {
            if( e.keyCode == 13 ){
                e.preventDefault();
                $('.btn-pesq').click();
            }
        });



        $('#notivisa').on('input', function () {
           // console.log('Notivisa: '+$(this).val() );
            verificarCampos();
            if( $(this).val() != "" ){
               // console.log('Nao esta vazio ');
                $(".btn-save").removeAttr( 'disabled' );
            }else{
               // console.log('Esta vazio');
                $(".btn-save").attr( 'disabled', true );
            }
        });

        $('#codigo').on('input', function () {
            //console.log('Notivisa: '+$(this).val() );
            verificarCampos();
            if( $(this).val() != "" ){
                //console.log('Nao esta vazio ');
                $(this).css('border-color','');
            }
        });

        function validarCampos() {

            var teste = true;
            if( ($('#codigo').val() == "") || $('#notivisa').val() == "" ){
                teste = false;
                if( $('#codigo').val() == "" ) {
                    $('input[id="codigo"]').css('border-color', 'red');
                }

                if( $('#notivisa').val() == "" ){
                    $('input[id="notivisa"]').css('border-color', 'red');
                }
            }else{
                $('input[id="notivisa"]').css('border-color', '');
                $('input[id="codigo"]').css('border-color', '');
            }
            return teste;
        }

        function verificarCampos() {


            if( ($('#codigo').val() == "") || $('#notivisa').val() == "" ){
                teste = false;
                if( $('#codigo').val() == "" ) {
                    $('.btn-save').attr('disabled', true);
                }

                if( $('#notivisa').val() == "" ){
                    $('.btn-save').attr('disabled', true);
                }
            }else{
                $('.btn-save').removeAttr('disabled');
            }

        }

        $('.btn-save').on('click', function () {
            salvarNotivisa();
        });
        
        function salvarNotivisa() {

            var ocorencia = $('#codigo').val();
            var notivisa  = $('#notivisa').val();
            var anvisa    = $('#anvisa').val();
            var token     = $('#token').val();
            var acao      = "./insert";
            if( validarCampos() ){

                if( anvisa > 0 ){
                    acao = "./update";
                }

                $.ajax({
                    url  : acao,
                    type : 'post',
                    dataType : 'json',
                    data : {
                        notivisa : notivisa,
                        codigo   : ocorencia,
                        _token   : token
                    },
                    success : function (data) {
                      //  console.log("Response: "+data.success);
                        if( data.success ){
                            $('#anvisa').va( data.id );
                            successMsg();
                        }else{
                            errorMsg( 'Ocorreu um problema ao realizar operação' );
                        }
                    }

                });


            }else{
                errorMsg('Os campo(s) destacado(s) precisa(m) ser preenchido(s)');
            }
        }

        function errorMsg( msg ) {
            var alerta = $('.alerta');
            alerta.addClass( 'alert-danger' );
            alerta.empty().html('<strong>Opa!</strong> '+msg)
            alerta.animate({"right":"280px"}, "slow");
            setTimeout(function () {
                alerta.animate({"right":"-500px"}, "slow");
            },3000);
        }

        function successMsg() {
            var alerta = $('.alerta');
            alerta.empty().html('<strong>Parab&ecirc;ns!</strong> Opera&ccedil;&atilde;o realizada com sucesso!');
            alerta.animate({"right":"280px"}, "slow");
            setTimeout(function () {
                alerta.animate({"right":"-500px"}, "slow");
            },3000);
        }
    </script>


@stop