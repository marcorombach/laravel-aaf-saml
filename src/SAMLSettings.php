<?php

namespace Marcorombach\LaravelAafSAML;


use Illuminate\Support\Facades\Cache;

class SAMLSettings
{
    static function getSettings()
    {
        if(Cache::has('idpmetadata')){
            return(Cache::get('idpmetadata'));
        }else{
            $parser = new \OneLogin\Saml2\IdPMetadataParser();
            $metadata = $parser->parseRemoteXML(config('aaf-saml.idpmetadataurl'));

            $metadata['sp']['entityId'] = url('/saml-sp');
            $metadata['sp']['assertionConsumerService']['url'] = url('/saml-acs');
            $metadata['sp']['attributeConsumingService']['serviceName'] = config('aaf-saml.service_name');
            $metadata['sp']['attributeConsumingService']['serviceDescription'] = config('aaf-saml.service_description');
            $metadata['sp']['NameIDFormat'] = 'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified';
            $metadata['security'] = array(
                'requestedAuthnContext' => false,
            );

            Cache::put('idpmetadata', $metadata, $seconds = 172800);
            return($metadata);
        }
    }

}
