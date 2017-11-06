@extends( 'principal' );
@section( 'title', 'Relat&oacute;rios')
@section( 'subtitle', 'Relat&oacute;rio de Ocorr&ecirc;ncias | NSP')
@section( 'content' )

    <div class="row col-lg-12">

        <input type="hidden" id="token" value="{{ csrf_token() }}">
        <input type="hidden" id="anvisa" value="0">
        <div class="col-lg-6" >
            <div class="form-group col-lg-6">
                <label for="inicio">Per&iacute;odo Inicial</label>
                <input id="inicio" class="form-control">
            </div>




            {{--<a href="{{ action('NotificacaoController@busca', 7275) }}" class="btn btn-primary btn-pesq" style="margin-top: 20px;">Pesquisar</a>--}}

        </div>
        <div class="col-lg-6">
            <div class="form-group col-lg-6">
                <label for="final">Per&iacute;odo Final</label>
                <input id="final" class="form-control" >
            </div>
            <div class="col-lg-2">
                <button class="btn btn-success btn-pesq " style="margin-top: 20px;" disabled="">Pesquisar</button>
            </div>


            <div class="col-lg-3" style="font-size: 12px; margin-left: 10px; margin-top: 30px;">
                <a href="#reset" id="reset" ></a>
            </div>
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
    <script src="{{ URL('js//jquery.min.js') }}"></script>
    <script src="{{ URL('js/jquery.datetimepicker.full.js') }}"></script>
    <script>
        $("#inicio").datetimepicker({
            timepicker: false,
            format: 'd/m/Y',
            mask: true
        });

        $("#final").datetimepicker({
            timepicker: false,
            format: 'd/m/Y',
            mask: true
        });
        $.datetimepicker.setLocale('pt-BR')


        $('.btn-pesq').on('click', function () {
            var inicio = $('#inicio').val();
            var fim    = $('#final').val();
            var token  = $('#token').val();
            var div    = $('.tabela');
            var lnk    = $('#reset');
            div.empty();
            $.ajax({
                url  : './consulta',
                type : 'post',
                dataType: 'json',
                data : {
                    inicio : inicio,
                    fim    : fim,
                    _token : token
                },
                success: function ( data ) {
                    console.log( data );
                    lnk.text('Reiniciar pesquisa');

                    lnk.fadeIn();
                  //  console.log("Length: "+data.length);
                    if( data.length > 0 ){


                        var linha = "<table class='table table-hover table-striped'>" +
                                    "   <thead>" +
                                    "     <th title='C&oacute;digo da Ocorr&ecirc;ncia'>Notivisa</th>"+
                                    "     <th>Tipo de Ocorr&ecirc;ncia</th>"+
                                    "     <th>Resumo</th>"+
                                    "     <th>Data da Ocorr&ecirc;ncia</th>"+
                                    "     <th>Setor Registrante</th>"+
                                    "     <th>Setor de Ocorr&ecirc;ncia</th>"+
                                    "     <th>Registrante</th>"+
                                    "     <th>Status</th>"+
                                    "     <th></th>"+
                                    "   </thead>";
                        $.each( data, function (i, j) {
                            linha += "<tr>" +
                                     "   <td>"+ j.cd_notivisa +"</td>"+
                                     "   <td>"+ j.tipo_de_ocorrencia +"</td>"+
                                     "   <td>"+ j.resumo +"</td>"+
                                     "   <td>"+ j.data_ocorrencia +"</td>"+
                                     "   <td>"+ j.setor_registrante +"</td>"+
                                     "   <td>"+ j.setor_ocorrencia +"</td>"+
                                     "   <td>"+ j.registrante +"</td>"+
                                     "   <td>"+ j.status +"</td>"+
                                     "   <td> <a href='#' onclick='abrirLink( "+ j.cod_registro +" )' data-id='"+j.cod_registro+"' title='Visualizar relat&oacute;rio'><i class='glyphicon glyphicon-search' style='text-align: center'></i></a></td>"+
                                     "</tr>";
                        } );






                        div.append( linha );
                        div.fadeIn();


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

        function abrirLink( codigo ) {

            console.log('Clicado codigo: '+codigo);
            var form = $('<form action="http://10.51.19.226/SE/ExibeRelatorioOcorrenciaFases.aspx" method="get" target="_blank">'+
                '<input type="hidden" value="'+ codigo +'" name="cdRegistroOcorrencia">'+
                '<input type="hidden" value="239" name="cdUsuarioLogado">'+
                '</form>'
            );
            $('body').append( form );
            form.submit();
        }

        $('#reset').on('click', function () {
            var div = $('.tabela');
            var inicio = $("#inicio");
            var final = $("#final");
            var anvisa = $("#anvisa");
            div.fadeOut();
            div.append('');
            var lnk = $('#reset');
            lnk.text('');
            lnk.fadeOut();
            inicio.val('');
            final.val('');
            anvisa.val(0);


        });

        $

        $('#inicio').on('change', function () {
        //    console.log('inicio: '+$(this).val() );
            verificarCampos();


        });

        $('#final').on('change', function () {
            //console.log('Notivisa: '+$(this).val() );
            verificarCampos();

        });

        function validaIntevaloTempo() {

            var strDataInicial = $('#inicio').val();
            var strDataFinal   = $('#final').val();

            /*

             Obtendo dados da data inicial

             */
            var teste = false;
            try{
                var arrayDataInicio = strDataInicial.split("/"); //separando onde tiver a barrra
                var idia     = arrayDataInicio[0];  //pegando domente o dia da data inicial 16
                var imes     = arrayDataInicio[1];  //pegando domente o mes da data inicial 41
                var iano      = arrayDataInicio[2];  //pegando domente o ano da data inicial 41

                /*
                 Obtendo dados da data final

                 */
                var arrayDataFinal = strDataFinal.split("/"); //separando onde tiver a barrra
                var fdia     = arrayDataFinal[0];  //pegando domente o dia da data final 16
                var fmes     = arrayDataFinal[1];  //pegando domente o mes da data final 41
                var fano      = arrayDataFinal[2];  //pegando domente o ano da data inicial 41

                var vDataInicial = new Date(iano, imes - 1, idia);
                var vDataFinal   = new Date(fano, fmes - 1, fdia);

                if(vDataFinal < vDataInicial){
                   // console.log( "Data menor" );
                    teste = false;
                }

                else{
                    //   console.log("Data é maior ou igual");
                   // console.log( "Data maior" );
                    teste = true;
                }

            }catch (err){}


                return teste;



        }

        function verificarCampos() {
            var inicio = $('#inicio');
            var final  = $('#final');
            //console.log( "tempo: "+validaIntevaloTempo() );
            if( ( ( inicio.val() == "" ) || ( inicio.val() == "__/__/____" ) )
              ||( ( final.val() == "" )  || ( final.val() == "__/__/____" )
              || !validaIntevaloTempo()  )
            )
                {
              // console.log('Botao salvar inativo');
                if( ( ( inicio.val() == "" ) || ( inicio.val() == "__/__/____" ) ) ) {
                    $('.btn-save').attr('disabled', '' );
                }

                if( $('#notivisa').val() == "" ){
                    $('.btn-save').attr('disabled', '' );
                }

                if( !validaIntevaloTempo() ){
                    $('.btn-save').attr('disabled', '');
                }
            }else{
               // console.log('Botao salvar ativo');
                $('.btn-pesq').removeAttr('disabled');
            }

        }


    </script>


@stop