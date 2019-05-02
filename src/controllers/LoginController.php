<?php
/**
 * Created by PhpStorm.
 * User: Thibaud
 * Date: 15/04/2019
 * Time: 12:41
 */

namespace Controllers;

use Models\User;
use Slim\Http\Request;
use Slim\Http\Response;

class LoginController extends BaseController
{
    public function getLogin(Request $request, Response $response, $args) {
        $tplData = [];

        $flashes = $this->container->flash->getMessages();

        if (isset($flashes['errors'])) {
            $tplData['errors'] = $flashes['errors'][0];
        }

        if ($request->getParam('action')) {
            $tplData['action'] = $request->getParam('action');
        }

        if ($request->getParam('login')) {
            $tplData['login'] = $request->getParam('login');
        }

        return $this->twig->render($response, 'login.twig', $tplData);
    }

    private function validateSignupRequest(Request $request) {
        $errors = [];
        $errors['title'] = 'Il semblerait que le formulaire ait été mal remplit';
        $errors['msgs'] = [];

        if (!$request->getParam('s_firstname')) {
            $errors['msgs'][] = 'Le prénom est obligatoire';
        }
        if (!$request->getParam('s_lastname')) {
            $errors['msgs'][] = 'Le nom est obligatoire';
        }
        if (!$request->getParam('s_email')) {
            $errors['msgs'][] = 'L\'email est obligatoire';
        }
        if (!$request->getParam('s_email_confirmation')) {
            $errors['msgs'][] = 'La confirmation d\'email est obligatoire';
        }
        if ($request->getParam('s_email_confirmation') != $request->getParam('s_email')) {
            $errors['msgs'][] = 'L\'email et la confirmation ne sont pas identiques';
        }
        if (!$request->getParam('s_password')) {
            $errors['msgs'][] = 'Le mot de passe est obligatoire';
        }
        if (!$request->getParam('s_password_confirmation')) {
            $errors['msgs'][] = 'La confirmation de mot de passe est obligatoire';
        }
        if ($request->getParam('s_password') != $request->getParam('s_password_confirmation')) {
            $errors['msgs'][] = 'Le mot de passe et la confirmation ne sont pas identiques';
        }

        $errors['form_data'] = $request->getParams();

        return $errors;
    }

    public function postSignup(Request $request, Response $response, $args) {
        // Check variables
        $errors = $this->validateSignupRequest($request);

        if (count($errors['msgs'])) {
            $this->container->flash->addMessage('errors', $errors);
            return $response->withRedirect($this->container->get('router')->pathFor('getLogin', [
                'action' => 'signup',
            ]));
        }

        try {
            // First check if User email already registered
            User::where([
                ['email', '=', $request->getParam('s_email')],
            ])->firstOrFail();

            $this->container->flash('errors', [
                'title' => 'Oups !',
                'msg' => ['Il semblerait que vous soyez déjà inscrit !'],
                'form_data' => $request->getParams()
            ]);
            return $response->withRedirect($this->container->get('router')->pathFor('getLogin', [
                'action' => 'signup',
            ]));

        } catch (\Exception $e) {
            try {
                // If failed to find one match means we can go further in registration
                $user = new User();

                $user->firstname = $request->getParam('s_firstname');
                $user->lastname = $request->getParam('s_lastname');
                $user->email = $request->getParam('s_email');
                $user->password = sha1($request->getParam('s_password').$this->container->get('settings')['secret']);

                $user->saveOrFail();

                // Save user in session, then redirect to dashboard
                $this->setLoggedUser($user);

                return $response->withRedirect('/dashboard');
            } catch (\Exception $e) {
                $this->container->flash->addMessage('errors', [
                    'title' => 'Oups !',
                    'msgs' => ['Il semblerait que nous rencontrions des difficultés pour procéder à votre inscription, merci de réessayer'],
                    'form_data' => $request->getParams()
                ]);
                return $response->withRedirect($this->container->get('router')->pathFor('getLogin', [
                    'action' => 'signup',
                ]));
            }
        }
    }

    public function postLogin(Request $request, Response $response, $args) {
        try {
            $user = User::where([
                ['email', '=', $request->getParam('l_email')],
                ['password', '=', sha1($request->getParam('l_password').$this->container->get('settings')['secret'])],
            ])->firstOrFail();

            // Save user in session, then redirect to dashboard
            $this->setLoggedUser($user);

            return $response->withRedirect('/dashboard');
        } catch (\Exception $e) {
            return $response->withRedirect($this->container->get('router')->pathFor('getLogin', [
                'login' => 'false',
            ]));
        }
    }

    public function getLogout(Request $request, Response $response, $args) {
        $this->unsetLoggedUser();
        return $response->withRedirect($this->container->get('router')->pathFor('getLogin'));
    }
}
