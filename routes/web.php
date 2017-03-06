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

// Home Page
$app->get('/', function () use ($app){
    return $app->version();
});
// Posts
$app->get('/user_statements','UserStatementController@index');
$app->get('/admin/user_statements','UserStatementController@adminIndex');
$app->post('/user_statements','UserStatementController@store');
$app->get('/user_statements/{user_statement_id}','UserStatementController@show');
$app->put('/user_statements/{user_statement_id}', 'UserStatementController@update');
$app->patch('/user_statements/{user_statement_id}', 'UserStatementController@update');
$app->delete('/user_statements/{user_statement_id}', 'UserStatementController@destroy');

// Users
$app->get('/users/', 'UserController@index');
$app->post('/users/', 'UserController@store');
$app->get('/users/{user_id}', 'UserController@show');
$app->put('/users/{user_id}', 'UserController@update');
$app->patch('/users/{user_id}', 'UserController@update');
$app->delete('/users/{user_id}', 'UserController@destroy');

// Comments
$app->get('/transactions', 'TransactionController@index');
$app->get('/transactions/{transaction_id}', 'TransactionController@show');

// Comments of a Post
$app->get('/user_statements/{user_statement_id}/transactions', 'UserStatementTransactionController@index');
$app->post('/user_statements/{user_statement_id}/transactions', 'UserStatementTransactionController@store');
$app->put('/user_statements/{user_statement_id}/transactions/{transaction_id}', 'UserStatementTransactionController@update');
$app->patch('/user_statements/{user_statement_id}/transactions/{transaction_id}', 'UserStatementTransactionController@update');
$app->delete('/user_statements/{user_statement_id}/transactions/{transaction_id}', 'UserStatementTransactionController@destroy');


$app->get('/report/general', 'ReportsController@generalReport');

// Request Access Tokens
$app->post('/oauth/access_token', function() use ($app){
    return response()->json($app->make('oauth2-server.authorizer')->issueAccessToken());
});

