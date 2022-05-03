<?php

namespace Marcorombach\LaravelAafSAML;


class LaravelAafSAML
{

    function authenticate($username, $password){

        $auth = new \OneLogin\Saml2\Auth(config('aaf-saml-static.settings'));

        $access = "";

        //TODO: Return Authenticable (e.g. User-Object)
        if($access){
            return true;
        }else{
            return false;
        }
    }

    function metadata(){
        try {
            $auth = new \OneLogin\Saml2\Auth();
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
