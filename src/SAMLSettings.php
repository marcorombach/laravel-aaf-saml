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
            $metadata['sp']['singleLogoutService']['url'] = url('/saml-slo');
            $metadata['sp']['NameIDFormat'] = 'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified';
            // Müssen hier nicht mehr Attribute angefragt werden?
            $metadata['sp']['attributeConsumingService']['requestedAttributes'] = array(
                array(
                    "name" => "urn:oid:0.9.2342.19200300.100.1.3",
                    "isRequired" => false,
                    "nameFormat" => "urn:oasis:names:tc:SAML:2.0:attrname-format:uri",
                    "friendlyName" => "mail",
                    "attributeValue" => array()
                ),
                array(
                    "name" => "urn:oid:2.16.840.1.113730.3.1.241",
                    "isRequired" => false,
                    "nameFormat" => "urn:oasis:names:tc:SAML:2.0:attrname-format:uri",
                    "friendlyName" => "displayName",
                    "attributeValue" => array()
                )
            );
            $metadata['security'] = array(
                'requestedAuthnContext' => false,
            );
            Cache::put('idpmetadata', $metadata, $seconds = 172800);
            return($metadata);
        }
    }

}
