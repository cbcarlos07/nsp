<html>
  <head>
      <title>Sistema de Notificações do NSP | @yield('title')</title>
      <link rel="stylesheet" href={{ URL('theme/bower_components/bootstrap/dist/css/bootstrap.min.css') }}>
      <!-- Font Awesome -->
      <link rel="stylesheet" href={{ URL('theme/bower_components/font-awesome/css/font-awesome.min.css') }}>
      <link rel="stylesheet" href="{{ URL('css/jquery.datetimepicker.min.css') }}" type="text/css" >

  </head>
  <body>
  <nav class="navbar navbar-default navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
          <div class="navbar-header">
              <a class="navbar-brand" href="{{ action('NotificacaoController@principal') }}">Notifica&ccedil;&otilde;es<b>NSP</b>v1.0</a>
          </div>
          <ul class="nav navbar-nav">
              <li><a href="{{ action('NotificacaoController@cadastro') }}">Cadastro</a></li>
              <li><a href="{{ action('NotificacaoController@relatorio') }}">Relat&oacute;rio</a></li>
              <li><a href="#" style="text-decoration: none"><span class="saudacao"></span><b>{{ Session::get('usuario') }}</b>!</a></li>

          </ul>
          <ul class="nav navbar-nav navbar-right">
              <li><a href="{{ action('NotificacaoController@beforeExit') }}">Sair</a></li>

          </ul>
      </div>
  </nav>
  <div class="row"></div>
   <div class="container" style="margin-top: 50px;">

       <h1 class="alert alert-warning" style="text-align: center"> @yield('subtitle')</h1>
       @yield( 'content' )
   </div>


  <!-- jQuery 3 -->
  <script src="{{ URL('theme/bower_components/jquery/dist/jquery.min.js') }}"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="{{ URL('theme/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
  <script>

      $(document).ready(function () {

          var selector = '.nav li';
          var url = window.location.href;
          var target = url.split('/');

          $(selector).each(function(){
              var urlAtual = target[target.length-1].split('#');
              var addressBar = $(this).find('a').attr('href');
              var alvo = addressBar.split('/');
              var link = alvo[alvo.length-1];
              //    console.log( url );
             ////  console.log(  $(this).find('a').attr('href')+" - "+ url);
             //  console.log(  link+" - "+ urlAtual[0]);
              if( link == urlAtual[0] ){
             //     console.log( "Igual" );
             // if($(this).find('a').attr('href')===urlAtual[0]){
               //    if($(this).find('a').attr('href')===($(this).find('a').attr('href'))){

                  $(selector).removeClass('active');
                  $(this).addClass('active');
              }
          });

          atualizarSaudacao();

          setTimeout(function () {
              atualizarSaudacao()
          }, 30000);
      });


      function atualizarSaudacao() {
          var data = new Date();
          var hora = data.getHours();

          var msg = "";
          if( hora > 0 && hora < 12 ){
              msg = "Bom dia, ";
          }else
          if( hora >= 12 ){
              msg = "Boa tarde, ";
          }else if( hora > 18 ){
              msg = "Boa noite, ";
          }

          $('span.saudacao').text( msg );

      }



  </script>

  </body>
</html>