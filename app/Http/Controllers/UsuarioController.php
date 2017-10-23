<?php

namespace App\Http\Controllers;



use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Request;
use Validator;

class UsuarioController extends Controller
{
    public function logar(){
        $login = strtoupper( Request::input('login') );
        $senha = strtoupper( Request::input('senha') );

        $validator = Validator::make(
            [
                'login' => $login,
                'senha' => $senha
            ],
            [
                'login' => 'required',
                'senha' => 'required'
            ],
            [
                'required'  => ':attribute é obrigatório.'
            ]
        );
        if( $validator->fails() ){
            return redirect(  )->action( 'UsuarioController@login' )->withErrors( $validator )->withInput();
        }


        $pwd = DB::connection('oracle1')->select("SELECT senhausuariomv2(?) SENHA FROM DUAL", array( $login ));



        $pass = "";
        foreach( $pwd as $item ){
            $pass = $item->senha;
        }

        if( $senha == $pass ){
            Session::put('usuario', $login );
            return redirect(  )->action( 'NotificacaoController@principal' );

        }else{
            return view('/login')->with('pwd','0');
        }


    }

    public function login(){
        return view('login');
    }

    public function sair(){
        Session::flush();
        return redirect(  )->action( 'UsuarioController@login' );

    }



}
