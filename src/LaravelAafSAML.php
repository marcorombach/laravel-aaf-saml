<?php

namespace Marcorombach\LaravelAafSAML;


use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Marcorombach\LaravelAafSAML\LoginHandler;

class LaravelAafSAML extends Controller
{
    function initiate(){
        $auth = new \OneLogin\Saml2\Auth(SAMLSettings::getSettings());
        $auth->login();
        Session::put('AuthNRequestID', $auth->getLastRequestID());
    }

    function authenticate(){
        try{
            $auth = new \OneLogin\Saml2\Auth(SAMLSettings::getSettings());

            $requestID = Session::get('AuthNRequestID');

            $auth->processResponse($requestID);
            Session::forget('AuthNRequestID');

            $attributes = $auth->getAttributes();

            $errors = $auth->getErrors();

            if(!empty($errors)){
                return response()->view('aaf-saml::saml-error',['errors' => $errors]);
            }

            //TODO: retrieve attributes from $attributes array and map to userdata
            //dd($attributes);

            $userdata = [
                'user_name' => $attributes['samaccountname'],
                'email' => $attributes['mail'],
                'given_name' => $attributes['userFirstName'],
                'family_name' => $attributes['userLastName']
            ];

            if ($auth->isAuthenticated()) {
                LoginHandler::handleLogin($userdata);

                return redirect(url('/'));
            }

        } catch (\Exception $e) {
            return response($e->getMessage(), '200');
        }

    }

    function logout(){

    }

    function metadata(){
        try {
            $auth = new \OneLogin\Saml2\Auth(SAMLSettings::getSettings());
            $settings = $auth->getSettings();
            $metadata = $settings->getSPMetadata();
            $errors = $settings->validateMetadata($metadata);
            if (empty($errors)) {
                return response($metadata, '200')->header('Content-Type', 'text/xml');
            } else {
                throw new \OneLogin\Saml2\Error(
                    'Invalid SP metadata: '.implode(', ', $errors),
                    \OneLogin\Saml2\Error::METADATA_SP_INVALID
                );
            }
        } catch (\Exception $e) {
            return response($e->getMessage(), '200')->header('Content-Type', 'text/xml');
        }
    }
}
