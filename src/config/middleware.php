<?php
// Application middleware

use Slim\Http\Request;
use Slim\Http\Response;

// Add Auth middleware
$app->add(function(Request $request, Response $response, $next) use ($app) {
    $route = $request->getAttribute('route');

    if (is_null($route)) {
        $app->getContainer()->monolog->info("[404] Tried to access '".$_SERVER['REQUEST_URI']."' route");
        return $app->getContainer()->twig->render($response, '404.twig', []);
    }
    
    $routeName = $route->getName();

    # Define routes that user does not have to be logged in with. All other routes, the user
    # needs to be logged in with.
    $publicRoutesArray = array(
        'postLogin',
        'postSignup',
        'getLogin',
        'getIndexRedirect',
    );

    if (!isset($_SESSION['user']) && !in_array($routeName, $publicRoutesArray)) {
        // redirect the user to the login page and do not proceed.
        $response = $response->withRedirect(
            $app->getContainer()->get('router')->pathFor('getLogin')
        );
    } elseif (isset($_SESSION['user']) && in_array($routeName, $publicRoutesArray)) {
        // prevent user to access useless routes when already loggued
        $user = $_SESSION['user'];
        $this->twig->offsetSet('current_user', $user);
        $response = $response->withRedirect(
            $app->getContainer()->get('router')->pathFor('getAdmin')
        );
    } else {
        // Proceed as normal...
        $user = $_SESSION['user'];
        $this->twig->offsetSet('current_user', $user);
        $response = $next($request, $response);
    }

    return $response;
});
