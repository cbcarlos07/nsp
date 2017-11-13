<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/', function () {
    return view('login');
});

Route::get('/', 'UsuarioController@login');
Route::post('/logar', ['as'=> 'logar', 'uses' => 'UsuarioController@logar']);
Route::group(['middleware' => 'nsp'], function (){


    Route::get('/cadastro',[ 'as' => 'cadastro', 'uses' => 'NotificacaoController@cadastro' ] );
    Route::post('/busca', ['as' => 'busca', 'uses' => 'NotificacaoController@busca']);
    Route::get('/principal',['as' => 'principal' ,  'uses' => 'NotificacaoController@principal'] );
    Route::post('/insert', ['as' => 'cadNotivisa', 'uses', 'NotificacaoController@cadNotivisa']);
    Route::post('/update/', ['as' =>'altNotivisa', 'uses' => 'NotificacaoController@altNotivisa']);
    Route::get('/relatorio', ['as' => 'relatorio', 'uses' => 'NotificacaoController@relatorio']);
    Route::post('/consulta', ['as' => 'consulta','uses' => 'NotificacaoController@consulta']);
    Route::get('/sair', ['as' => 'beforeExit', 'uses' => 'NotificacaoController@beforeExit']);
    Route::get('/logout', ['as' => 'sair', 'uses' => 'UsuarioController@sair']);

});
