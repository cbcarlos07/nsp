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

Route::post('/principal', 'UsuarioController@logar');
Route::get('/cadastro', 'NotificacaoController@cadastro');
Route::post('/busca', 'NotificacaoController@busca');
Route::get('/inicio', 'NotificacaoController@principal');
Route::post('/insert', 'NotificacaoController@cadNotivisa');
Route::post('/update/', 'NotificacaoController@altNotivisa');
Route::get('/relatorio', 'NotificacaoController@relatorio');
Route::post('/consulta', 'NotificacaoController@consulta');