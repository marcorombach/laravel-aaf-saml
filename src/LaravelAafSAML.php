<?php

namespace Marcorombach\LaravelAafSAML;


use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Marcorombach\LaravelAafOIDC\UserData;

class LaravelAafSAML extends Controller
{
    function initiate(){
        $auth = new \OneLogin\Saml2\Auth(SAMLSettings::getSettings());
        $auth->login();
        Session::put('AuthNRequestID', $auth->getLastRequestID());
    }

    function authenticate(){

        $auth = new \OneLogin\Saml2\Auth(SAMLSettings::getSettings());

        $requestID = Session::get('AuthNRequestID');

        $auth->processResponse($requestID);
        Session::forget('AuthNRequestID');

        $attributes = $auth->getAttributes();

        $errors = $auth->getErrors();

        if(!empty($errors)){
            Log::error(json_encode($errors));
            if(config('aaf-saml.error-route') != '') {
                return redirect()->route(config('aaf-saml.error-route'))->with(['error' => json_encode($errors)]);
            }
            return redirect(url('/'))->with(['error' => json_encode($errors)]);
        }

        $userdata = new UserData();
        $userdata->setUsername($attributes['samaccountname'][0]);
        $userdata->setEmail($attributes['mail'][0]);
        $userdata->setGivenname($attributes['userFirstName'][0]);
        $userdata->setFamilyname($attributes['userLastName'][0]);

        if ($auth->isAuthenticated()) {
            try{
                LoginHandler::handleLogin($userdata);
            }catch(\ErrorException $e){
                if(config('aaf-saml.error-route') != '') {
                    return redirect()->route(config('aaf-oidc.error-route'))->with(['error' => $e]);
                }
                return redirect(url('/'))->with(['error' => $e]);
            }
            if(config('aaf-saml.post-login-route') != ''){
                return redirect()->route(config('aaf-saml.post-login-route'));
            }
            return redirect(url('/'));
        }
        if(config('aaf-saml.error-route') != ''){
            return redirect()->route(config('aaf-saml.error-route'))->with(['error' => 'Authentication failed']);
        }
        return redirect(url('/'))->with(['error' => 'Authentication failed']);

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
            Log::error($e->getMessage());
            return response($e->getMessage(), '500')->header('Content-Type', 'text/xml');
        }
    }
}
