<?php

namespace Marcorombach\LaravelAafSAML;


use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Marcorombach\LaravelAafSAML\LoginHandler;

class LaravelAafSAML extends Controller
{

    function authenticate(){
        try{
            $auth = new \OneLogin\Saml2\Auth(SAMLSettings::getSettings());
            if (Session::has('AuthNRequestID')) {
                $requestID = Session::get('AuthNRequestID');

                $auth->processResponse($requestID);
                Session::forget('AuthNRequestID');

                $userdata = [
                    'user_name' => $auth->getNameId(),
                    'email' => $auth->getNameId(),
                    'given_name' => $auth->getNameId(),
                    'family_name' => $auth->getNameId()
                ];

                if ($auth->isAuthenticated()) {
                    LoginHandler::handleLogin($userdata);

                    return redirect(url('/'));
                }
            } else {
                $auth->login();
                Session::put('AuthNRequestID', $auth->getLastRequestID());
            }


        } catch (\Exception $e) {
            return response($e->getMessage(), '200');
        }

        $access = "";

        //TODO: Return Authenticable (e.g. User-Object)
        if($access){
            return true;
        }else{
            return false;
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
