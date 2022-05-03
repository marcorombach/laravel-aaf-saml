<?php
// config for Marcorombach/LaravelAafSAML
return [
    'settings' => [
        // If 'strict' is True, then the PHP Toolkit will reject unsigned
        // or unencrypted messages if it expects them to be signed or encrypted.
        // Also it will reject the messages if the SAML standard is not strictly
        // followed: Destination, NameId, Conditions ... are validated too.
        'strict' => true,

        // Enable debug mode (to print errors).
        'debug' => false,

        // Set a BaseURL to be used instead of try to guess
        // the BaseURL of the view that process the SAML Message.
        // Ex http://sp.example.com/
        //    http://example.com/sp/
        'baseurl' => null,

        // Service Provider Data that we are deploying.
        'sp' => array(
            // Identifier of the SP entity  (must be a URI)
            'entityId' => url('/saml-sp'),
            // Specifies info about where and how the <AuthnResponse> message MUST be
            // returned to the requester, in this case our SP.
            'assertionConsumerService' => array(
                // URL Location where the <Response> from the IdP will be returned
                'url' => url('/saml-acs'),
                // SAML protocol binding to be used when returning the <Response>
                // message. OneLogin Toolkit supports this endpoint for the
                // HTTP-POST binding only.
                'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
            ),
            // If you need to specify requested attributes, set a
            // attributeConsumingService. nameFormat, attributeValue and
            // friendlyName can be omitted
            "attributeConsumingService" => array(
                "serviceName" => config('aaf-saml.service_name'),
                "serviceDescription" => config('aaf-saml.service_description'),
                "requestedAttributes" => array(
                    array(
                        "name" => "",
                        "isRequired" => false,
                        "nameFormat" => "",
                        "friendlyName" => "",
                        "attributeValue" => array()
                    )
                )
            ),
            // Specifies info about where and how the <Logout Response> message MUST be
            // returned to the requester, in this case our SP.
            'singleLogoutService' => array(
                // URL Location where the <Response> from the IdP will be returned
                'url' => url('/saml-slo'),
                // SAML protocol binding to be used when returning the <Response>
                // message. OneLogin Toolkit supports the HTTP-Redirect binding
                // only for this endpoint.
                'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            ),
            // Specifies the constraints on the name identifier to be used to
            // represent the requested subject.
            // Take a look on lib/Saml2/Constants.php to see the NameIdFormat supported.
            'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
            // Usually x509cert and privateKey of the SP are provided by files placed at
            // the certs folder. But we can also provide them with the following parameters
            'x509cert' => config('aaf-saml.certificate'),
            'privateKey' => config('aaf-saml.private_key'),

            /*
             * Key rollover
             * If you plan to update the SP x509cert and privateKey
             * you can define here the new x509cert and it will be
             * published on the SP metadata so Identity Providers can
             * read them and get ready for rollover.
             */
            // 'x509certNew' => '',
        ),

        // Identity Provider Data that we want connected with our SP.
        'idp' => array(
            // Identifier of the IdP entity  (must be a URI)
            'entityId' => config('aaf-saml.idp'),
            // SSO endpoint info of the IdP. (Authentication Request protocol)
            'singleSignOnService' => array(
                // URL Target of the IdP where the Authentication Request Message
                // will be sent.
                'url' => config('aaf-saml.idp_sso'),
                // SAML protocol binding to be used when returning the <Response>
                // message. OneLogin Toolkit supports the HTTP-Redirect binding
                // only for this endpoint.
                'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            ),
            // SLO endpoint info of the IdP.
            'singleLogoutService' => array(
                // URL Location of the IdP where SLO Request will be sent.
                'url' => config('aaf-saml.idp_slo'),
                // URL location of the IdP where SLO Response will be sent (ResponseLocation)
                // if not set, url for the SLO Request will be used
                'responseUrl' => config('aaf-saml.idp_slo_response'),
                // SAML protocol binding to be used when returning the <Response>
                // message. OneLogin Toolkit supports the HTTP-Redirect binding
                // only for this endpoint.
                'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            ),
            // Public x509 certificate of the IdP
            'x509cert' => config('aaf-saml.idp_pub_cert'),
            /*
             *  Instead of use the whole x509cert you can use a fingerprint in order to
             *  validate a SAMLResponse, but we don't recommend to use that
             *  method on production since is exploitable by a collision attack.
             *  (openssl x509 -noout -fingerprint -in "idp.crt" to generate it,
             *   or add for example the -sha256 , -sha384 or -sha512 parameter)
             *
             *  If a fingerprint is provided, then the certFingerprintAlgorithm is required in order to
             *  let the toolkit know which algorithm was used. Possible values: sha1, sha256, sha384 or sha512
             *  'sha1' is the default value.
             *
             *  Notice that if you want to validate any SAML Message sent by the HTTP-Redirect binding, you
             *  will need to provide the whole x509cert.
             */
            // 'certFingerprint' => '',
            // 'certFingerprintAlgorithm' => 'sha1',

            /* In some scenarios the IdP uses different certificates for
             * signing/encryption, or is under key rollover phase and
             * more than one certificate is published on IdP metadata.
             * In order to handle that the toolkit offers that parameter.
             * (when used, 'x509cert' and 'certFingerprint' values are
             * ignored).
             */
            // 'x509certMulti' => array(
            //      'signing' => array(
            //          0 => '<cert1-string>',
            //      ),
            //      'encryption' => array(
            //          0 => '<cert2-string>',
            //      )
            // ),
        )]
];
