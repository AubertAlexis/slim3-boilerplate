<?php
/**
 * Created by PhpStorm.
 * User: Thibaud
 * Date: 15/04/2019
 * Time: 12:20
 */

use Slim\Http\Request;
use Slim\Http\Response;

/*
 * Actions routes
 */
$app->get('/', function(Request $request, Response $response, $args) {
    if (isset($_SESSION['user'])) {
        return $response->withRedirect('/dashboard');
    } else {
        return $response->withRedirect('/login');
    }

})->setName('getIndexRedirect');
