<?php

//This is variable is an example - Just make sure that the urls in the 'idp' config are ok.
$idp_host = env('SAML2_IDP_HOST', 'https://idp-dev.cc.binghamton.edu');

return [

    /**
     * If 'useRoutes' is set to true, the package defines five new routes:
     *
     *    Method | URI                      | Name
     *    -------|--------------------------|------------------
     *    POST   | {routesPrefix}/acs       | saml_acs
     *    GET    | {routesPrefix}/login     | saml_login
     *    GET    | {routesPrefix}/logout    | saml_logout
     *    GET    | {routesPrefix}/metadata  | saml_metadata
     *    GET    | {routesPrefix}/sls       | saml_sls
     */
    'useRoutes' => true,

    'routesPrefix' => '/saml2',

    /**
     * which middleware group to use for the saml routes
     * Laravel 5.2 will need a group which includes StartSession
     */
    'routesMiddleware' => ['saml'],

    /**
     * Indicates how the parameters will be
     * retrieved from the sls request for signature validation
     */
    'retrieveParametersFromServer' => false,

    /**
     * Where to redirect after logout
     */
    'logoutRoute' => '/',

    /**
     * Where to redirect after login if no other option was provided
     */
    'loginRoute' => '/',


    /**
     * Where to redirect after login if no other option was provided
     */
    'errorRoute' => '/',




    /*****
     * One Login Settings
     */



    // If 'strict' is True, then the PHP Toolkit will reject unsigned
    // or unencrypted messages if it expects them signed or encrypted
    // Also will reject the messages if not strictly follow the SAML
    // standard: Destination, NameId, Conditions ... are validated too.
    'strict' => false, //@todo: make this depend on laravel config

    // Enable debug mode (to print errors)
    'debug' => env('APP_DEBUG', true),

    // If 'proxyVars' is True, then the Saml lib will trust proxy headers
    // e.g X-Forwarded-Proto / HTTP_X_FORWARDED_PROTO. This is useful if
    // your application is running behind a load balancer which terminates
    // SSL.
    'proxyVars' => true,

    // Service Provider Data that we are deploying
    'sp' => array(

        // Specifies constraints on the name identifier to be used to
        // represent the requested subject.
        // Take a look on lib/Saml2/Constants.php to see the NameIdFormat supported
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',

        // Usually x509cert and privateKey of the SP are provided by files placed at
        // the certs folder. But we can also provide them with the following parameters
        'x509cert' => env('SAML2_SP_x509','
        MIICfDCCAeWgAwIBAgIBADANBgkqhkiG9w0BAQ0FADBaMQswCQYDVQQGEwJ1czEL
        MAkGA1UECAwCTlkxGTAXBgNVBAoMEEVzY2hlciBMYWJzLCBJbmMxIzAhBgNVBAMM
        Gmh0dHBzOi8vd3d3LmVzY2hlcmxhYnMuY29tMCAXDTE5MDIyNTIxNDEyOFoYDzIy
        OTIxMjEwMjE0MTI4WjBaMQswCQYDVQQGEwJ1czELMAkGA1UECAwCTlkxGTAXBgNV
        BAoMEEVzY2hlciBMYWJzLCBJbmMxIzAhBgNVBAMMGmh0dHBzOi8vd3d3LmVzY2hl
        cmxhYnMuY29tMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCj5VSfKKmTcw/F
        /dQQAj/25R/5sg2fGsSWHl4hlBfX41uNpdakwxTdnuY+KWV7q5ze6IHU4lXo53fK
        ogSb5hueC7E5NSQKDLx335b7rGEh9M9GK+9mRO8Z2aPJwJ4lR9FBI2pwKTyTIo4M
        ElfdpCoAYOqyekRW1isE2j/82zDq/wIDAQABo1AwTjAdBgNVHQ4EFgQUYR1E5wEg
        41oVrxpBBLMXvOkxrCYwHwYDVR0jBBgwFoAUYR1E5wEg41oVrxpBBLMXvOkxrCYw
        DAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQ0FAAOBgQAXgD+9rOq/3mHOORI1KWja
        kc2jbtGJIfhElbLuFTRwyYqoWD+xJpH/qcrULdDnFwVUECJTsfGe0jCJAp0cZHJk
        DLpmr/So1/oKuep9VAgDPZODLx3XMy+HuwKLswFK/dNs+XFQQgYMUPJ1a3VyKpt+
        pau2XHxLOFTsMKEoA+nuOg==
        '),
        'privateKey' => env('SAML2_SP_PRIVATEKEY','
        MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAKPlVJ8oqZNzD8X9
        1BACP/blH/myDZ8axJYeXiGUF9fjW42l1qTDFN2e5j4pZXurnN7ogdTiVejnd8qi
        BJvmG54LsTk1JAoMvHfflvusYSH0z0Yr72ZE7xnZo8nAniVH0UEjanApPJMijgwS
        V92kKgBg6rJ6RFbWKwTaP/zbMOr/AgMBAAECgYEAnxRKFY3HQooNBlUAD2XPphnw
        9lB/bi3yH+9r2FXA6tgQFiWgeB2t1AqWWkGd8fK5eZbd5b6mOkDpAfJOXO91X1z/
        /Q9SM08P5P4qajbzq2M5yjz9YZHpZC1jrZdpIneDs+Y7wyGPNXVDrC2j4qXsXPp8
        CuJbgL4IZ/dye4AOfFECQQDUSabT/pSFGgoQUEZWoLeO9n2BffXZIlsTIakdtvWi
        13vrUWlnx/YuqK2AR4BB6tsGJPD0vKeMISfotLMFkAQpAkEAxaTKqhKR3GiwlVKD
        u2TVZ5e8D+OlpsY+z33SRpQ58s7dK0DHTwrxWAEA7JLkmz/EHbzHqESCDVdMN2k0
        mWka5wJBAKIKij5doC6tPqtPKzGqwhJtUkXKySNyFwTWd8mHw54GT7/Cx+uA9giN
        lspJSbyHMaJSBl85tca/9D+r1s7TLGkCQARBVwewXKmVK3AblbB8LEgNsUPaT9+2
        VvXarKNOX60FnSdoPqJKBwYxB1cQlpFtHwjQ3q+VwgMNhRuQTUycQbMCQEr0Fy4a
        /c37BF4D7+QIo6/mig6XIgqirFIlEkiZD8NI7v9zq04gdi1/eKqPiSaBOdBtZjTw
        KoVmnAs4iu3s+cI=
        '),

        // Identifier (URI) of the SP entity.
        // Leave blank to use the 'saml_metadata' route.
        'entityId' => env('SAML2_SP_ENTITYID',''),

        // Specifies info about where and how the <AuthnResponse> message MUST be
        // returned to the requester, in this case our SP.
        'assertionConsumerService' => array(
            // URL Location where the <Response> from the IdP will be returned,
            // using HTTP-POST binding.
            // Leave blank to use the 'saml_acs' route
            'url' => '',
        ),

        "attributeConsumingService"=> [
            "serviceName" => "ePATIENT",
            "serviceDescription" => 'ePATIENT is an educational EMR to aid in asynchronous interprofessional healthcare training. ePATIENT allows multiple student professions, from different campuses, to simultaneously access "virtual patient" records in real-time allowing them to respond to notes, orders, and comments from other members of the team as professionals do in real-world clinical environments.',
            "requestedAttributes" => [
                ["friendlyName" => "givenName", 'name'=> 'urn:oid:2.5.4.42', "isRequired" => true],
                ["friendlyName" => "sn", 'name'=> 'urn:oid:2.5.4.4', "isRequired" => true],
                ["friendlyName" => "mail", 'name'=> 'urn:oid:0.9.2342.19200300.100.1.3', "isRequired" => true],
                ["friendlyName" => "eduPersonTargetedID", 'name'=> 'urn:oid:1.3.6.1.4.1.5923.1.1.1.10', "isRequired" => true],
            ]
        ],

        // Specifies info about where and how the <Logout Response> message MUST be
        // returned to the requester, in this case our SP.
        // Remove this part to not include any URL Location in the metadata.
        'singleLogoutService' => array(
            // URL Location where the <Response> from the IdP will be returned,
            // using HTTP-Redirect binding.
            // Leave blank to use the 'saml_sls' route
            'url' => '',
        ),
    ),




    /***
     *
     *  OneLogin advanced settings
     *
     *
     */
    // Security settings
    'security' => array(

        /** signatures and encryptions offered */

        // Indicates that the nameID of the <samlp:logoutRequest> sent by this SP
        // will be encrypted.
        'nameIdEncrypted' => false,

        // Indicates whether the <samlp:AuthnRequest> messages sent by this SP
        // will be signed.              [The Metadata of the SP will offer this info]
        'authnRequestsSigned' => false,

        // Indicates whether the <samlp:logoutRequest> messages sent by this SP
        // will be signed.
        'logoutRequestSigned' => false,

        // Indicates whether the <samlp:logoutResponse> messages sent by this SP
        // will be signed.
        'logoutResponseSigned' => false,

        /* Sign the Metadata
         False || True (use sp certs) || array (
                                                    keyFileName => 'metadata.key',
                                                    certFileName => 'metadata.crt'
                                                )
        */
        'signMetadata' => false,


        /** signatures and encryptions required **/

        // Indicates a requirement for the <samlp:Response>, <samlp:LogoutRequest> and
        // <samlp:LogoutResponse> elements received by this SP to be signed.
        'wantMessagesSigned' => false,

        // Indicates a requirement for the <saml:Assertion> elements received by
        // this SP to be signed.        [The Metadata of the SP will offer this info]
        'wantAssertionsSigned' => false,

        // Indicates a requirement for the NameID received by
        // this SP to be encrypted.
        'wantNameIdEncrypted' => false,

        // Authentication context.
        // Set to false and no AuthContext will be sent in the AuthNRequest,
        // Set true or don't present thi parameter and you will get an AuthContext 'exact' 'urn:oasis:names:tc:SAML:2.0:ac:classes:PasswordProtectedTransport'
        // Set an array with the possible auth context values: array ('urn:oasis:names:tc:SAML:2.0:ac:classes:Password', 'urn:oasis:names:tc:SAML:2.0:ac:classes:X509'),
        'requestedAuthnContext' => true,
    ),

    // Contact information template, it is recommended to suply a technical and support contacts
    'contactPerson' => array(
        'technical' => array(
            'givenName' => 'Tim Cortesi',
            'emailAddress' => 'tcortesi@binghamton.edu'
        ),
        'administrative' => array(
            'givenName' => 'Sarah Lynch',
            'emailAddress' => 'selynch@binghamton.edu'
        ),
        'other' => array(
            'givenName' => 'Binghamton Security Office',
            'emailAddress' => 'security@binghamton.edu'
        ),
        'support' => array(
            'givenName' => 'Binghamton Helpdesk',
            'emailAddress' => 'helpdesk@binghamton.edu'
        ),
    ),

    // Organization information template, the info in en_US lang is recomended, add more if required
    'organization' => array(
        'en-US' => array(
            'name' => 'Binghamton University',
            'displayname' => 'Binghamton University',
            'url' => 'https://www.binghamton.edu'
        ),
    ),

/* Interoperable SAML 2.0 Web Browser SSO Profile [saml2int]   http://saml2int.org/profile/current

   'authnRequestsSigned' => false,    // SP SHOULD NOT sign the <samlp:AuthnRequest>,
                                      // MUST NOT assume that the IdP validates the sign
   'wantAssertionsSigned' => true,
   'wantAssertionsEncrypted' => true, // MUST be enabled if SSL/HTTPs is disabled
   'wantNameIdEncrypted' => false,
*/
    /* Default IDP -- Overwritten by school IDPs below during processing */
    'idp' => [
        'name' => 'Default',
        'entityId' => 'https://example.com/idp/shibboleth',
        'singleSignOnService' => [
            'url' => 'https://example.com/idp/profile/SAML2/Redirect/SSO',
        ],
        'x509cert' => 'MIIDTzCCAjegAwIBAgIUIxgMuKdj85wizYHJ1HH1eZfn3IowDQYJKoZIhvcNAQEL BQAwJDEiMCAGA1UEAwwZaWRwLWRldi5jYy5iaW5naGFtdG9uLmVkdTAeFw0xOTA2 MTEyMDQ1NDdaFw0zOTA2MTEyMDQ1NDdaMCQxIjAgBgNVBAMMGWlkcC1kZXYuY2Mu YmluZ2hhbXRvbi5lZHUwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCI CsFPx2RqtHWIzT5FV0aNACv+y7K3dZxBlISnRa5a0OHrVL6jr6igvuhjB+4apF5o IJTo/Dr/QoF61MsOxPucY9mhyve/wJ7SHQHgRjyYzxzdFhyq26TodAwLaPBZVEzC NYyPFxwcMwd/ka57tXKy4b2ZeiK6zhLTLkXbvl7pNHjAl6dLSQk+tI80ZW4RSPu7 /UhmtzP+UxK9hFIHsEZpt0HFbsLFcdrQs0EBXHVTyzUFqt2s0RVN2oCIupo7pQ0T Ny6qapwafkGq/3bWzsBZWX/zECnC1jWSFusKGk+MlSkSVYGffOnjcV0JiMqg7UHO XZ0+4bC3jzi44cqJU1eDAgMBAAGjeTB3MB0GA1UdDgQWBBRJmhUbP6ZR1XxOOAc2 zQuoIh4x9jBWBgNVHREETzBNghlpZHAtZGV2LmNjLmJpbmdoYW10b24uZWR1hjBo dHRwczovL2lkcC1kZXYuY2MuYmluZ2hhbXRvbi5lZHUvaWRwL3NoaWJib2xldGgw DQYJKoZIhvcNAQELBQADggEBAEYSU3NDFFTerdVl9fqN9kJWBBp3gyCP38EuVZgK dqqUsq84rRqp/EgI1PrnjDF8TP6CmY2lgMSqdMk5TDmV66MOctjT8W5MLm8dzX38 TSNPD8LMyiYVdMGOxssjsZwwY4udhuLQabGxh2tkhmREdaoi53ToBCZNvbw4l7YW 9ZB4u9sGdpg1hHwizPJd1eLyuJvvtWjDtxp3cGwydIHwgzUQ9yd8CVg39MhaeS12 t5fgGtDYTFnl9lUIc8+Ecu32QWksNmKOJdvs4pzu/NZ131l+TeTLFN/UmgzFWqC9 ad4IZdkOC/S09AD4yeQPFblvQo+tw6Y/drgW8+WxEsC3xR0=',
    ],
    'enabled_idps' => env('ENABLED_IDPS','binghamton_dev'),
    'idps'=>[
        'binghamton' => [
            'name' => 'Binghamton University',
            // Identifier of the IdP entity  (must be a URI)
            'entityId' => env('SAML2_IDP_ENTITYID', 'https://idp.cc.binghamton.edu/idp/shibboleth'),
            // SSO endpoint info of the IdP. (Authentication Request protocol)
            'singleSignOnService' => array(
                // URL Target of the IdP where the SP will send the Authentication Request Message,
                // using HTTP-Redirect binding.
                'url' => 'https://idp.cc.binghamton.edu/idp/profile/SAML2/Redirect/SSO',
            ),
            // SLO endpoint info of the IdP.
            'singleLogoutService' => array(
                // URL Location of the IdP where the SP will send the SLO Request,
                // using HTTP-Redirect binding.
                'url' => 'https://idp.cc.binghamton.edu/idp/profile/SAML2/Redirect/SLO',
            ),
            // Public x509 certificate of the IdP
            'x509cert' => env('SAML2_IDP_x509', '
            MIIDPzCCAiegAwIBAgIUNGqshJMfDRUUamC/0judzWfXRzMwDQYJKoZIhvcNAQEL BQAwIDEeMBwGA1UEAwwVaWRwLmNjLmJpbmdoYW10b24uZWR1MB4XDTE2MDExNzAx NDk1NloXDTM2MDExNzAxNDk1NlowIDEeMBwGA1UEAwwVaWRwLmNjLmJpbmdoYW10 b24uZWR1MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAmHJJsNq34KLb eCFXoLuyWFE8WeCRfagWHcU9cJhA26IoscpS031mgj+KdnZIGKBG8Qvm2ob8giNR C9A6dblsbmMgGbEXgugFXHzh7BL4THE/5vCoVXyT8cEcpneA51Cy1OK3hqv2OKD6 QgIRgbovElJeJB/M2V/2cpD9eq7klxkHgvSdm0/QYhpqx50cFcB0WHXE8Mpr2tne Hkl9tyBjbcXTYU3Bl6IfgJMFJ+DF70gVSu/hQz6eOzV1ZGTKd+k2iqyZqQaCfIL+ c1suMSFtk4wKLkBQDP9JsSayvyKngLcbmc4/V3fV/HquxNpxuAjHUv8LUbDgAN3q 0OWbuy4OIwIDAQABo3EwbzAdBgNVHQ4EFgQURTv5CG3wzpfj/BDoWcL3UncfFWAw TgYDVR0RBEcwRYIVaWRwLmNjLmJpbmdoYW10b24uZWR1hixodHRwczovL2lkcC5j Yy5iaW5naGFtdG9uLmVkdS9pZHAvc2hpYmJvbGV0aDANBgkqhkiG9w0BAQsFAAOC AQEAiCk13WUgaVpymalSbihawWqgkIxiiMclpWaWSY807rZFuIEUWJbnopgedt6H +BSIxX7A7w+GJfF4fS13mRRlEL2h9ONj4EwbznDNprCxcs+EG7FBpW6VKJd/gnj/ VDss4JT6ZOLQodl3wQ5LvwZ1zEeu811FQxSQaEpf69GyaQ/FCs57MDOHqMiAWGux 6mhUpGwo9S0bk2C8D6SapD1ZGWmqcyxudsabKDupSUKU2cwslxj8XVinRTx8zZuc 6VkzTV9Rc4vzes6atMo8AD8L2+As/e7vxBbR8pu40t9LlPe7f2MalA8yCCxPOlEn ljahXnbPNzvZc18kx0CUWRMMJQ==
            '),
            /*
                *  Instead of use the whole x509cert you can use a fingerprint
                *  (openssl x509 -noout -fingerprint -in "idp.crt" to generate it)
                */
            // 'certFingerprint' => '',
            'data_map' => [
                'unique_id' => '{{bnumber}}',
                'first_name' => '{{givenName}}',
                'last_name' => '{{sn}}',
                'email' => '{{mail}}',
            ],
        ],
        'binghamton_dev' => [
            'name' => 'Binghamton University (IDP-DEV)',
            // Identifier of the IdP entity  (must be a URI)
            'entityId' => env('SAML2_IDP_ENTITYID', 'https://idp-dev.cc.binghamton.edu/idp/shibboleth'),
            // SSO endpoint info of the IdP. (Authentication Request protocol)
            'singleSignOnService' => array(
                // URL Target of the IdP where the SP will send the Authentication Request Message,
                // using HTTP-Redirect binding.
                'url' => 'https://idp-dev.cc.binghamton.edu/idp/profile/SAML2/Redirect/SSO',
            ),
            // SLO endpoint info of the IdP.
            'singleLogoutService' => array(
                // URL Location of the IdP where the SP will send the SLO Request,
                // using HTTP-Redirect binding.
                'url' => 'https://idp-dev.cc.binghamton.edu/idp/profile/SAML2/Redirect/SLO',
            ),
            // Public x509 certificate of the IdP
            'x509cert' => env('SAML2_IDP_x509', '
            MIIDTzCCAjegAwIBAgIUIxgMuKdj85wizYHJ1HH1eZfn3IowDQYJKoZIhvcNAQEL BQAwJDEiMCAGA1UEAwwZaWRwLWRldi5jYy5iaW5naGFtdG9uLmVkdTAeFw0xOTA2 MTEyMDQ1NDdaFw0zOTA2MTEyMDQ1NDdaMCQxIjAgBgNVBAMMGWlkcC1kZXYuY2Mu YmluZ2hhbXRvbi5lZHUwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCI CsFPx2RqtHWIzT5FV0aNACv+y7K3dZxBlISnRa5a0OHrVL6jr6igvuhjB+4apF5o IJTo/Dr/QoF61MsOxPucY9mhyve/wJ7SHQHgRjyYzxzdFhyq26TodAwLaPBZVEzC NYyPFxwcMwd/ka57tXKy4b2ZeiK6zhLTLkXbvl7pNHjAl6dLSQk+tI80ZW4RSPu7 /UhmtzP+UxK9hFIHsEZpt0HFbsLFcdrQs0EBXHVTyzUFqt2s0RVN2oCIupo7pQ0T Ny6qapwafkGq/3bWzsBZWX/zECnC1jWSFusKGk+MlSkSVYGffOnjcV0JiMqg7UHO XZ0+4bC3jzi44cqJU1eDAgMBAAGjeTB3MB0GA1UdDgQWBBRJmhUbP6ZR1XxOOAc2 zQuoIh4x9jBWBgNVHREETzBNghlpZHAtZGV2LmNjLmJpbmdoYW10b24uZWR1hjBo dHRwczovL2lkcC1kZXYuY2MuYmluZ2hhbXRvbi5lZHUvaWRwL3NoaWJib2xldGgw DQYJKoZIhvcNAQELBQADggEBAEYSU3NDFFTerdVl9fqN9kJWBBp3gyCP38EuVZgK dqqUsq84rRqp/EgI1PrnjDF8TP6CmY2lgMSqdMk5TDmV66MOctjT8W5MLm8dzX38 TSNPD8LMyiYVdMGOxssjsZwwY4udhuLQabGxh2tkhmREdaoi53ToBCZNvbw4l7YW 9ZB4u9sGdpg1hHwizPJd1eLyuJvvtWjDtxp3cGwydIHwgzUQ9yd8CVg39MhaeS12 t5fgGtDYTFnl9lUIc8+Ecu32QWksNmKOJdvs4pzu/NZ131l+TeTLFN/UmgzFWqC9 ad4IZdkOC/S09AD4yeQPFblvQo+tw6Y/drgW8+WxEsC3xR0=
            '),
            /*
                *  Instead of use the whole x509cert you can use a fingerprint
                *  (openssl x509 -noout -fingerprint -in "idp.crt" to generate it)
                */
            // 'certFingerprint' => '',
            'data_map' => [
                'unique_id' => '{{bnumber}}',
                'first_name' => '{{givenName}}',
                'last_name' => '{{sn}}',
                'email' => '{{mail}}',
            ],
        ],
        'buffalo' => [
            'name' => 'University at Buffalo',
            // Identifier of the IdP entity  (must be a URI)
            'entityId' => env('SAML2_IDP_ENTITYID', 'urn:mace:incommon:buffalo.edu'),
            // SSO endpoint info of the IdP. (Authentication Request protocol)
            'singleSignOnService' => array(
                // URL Target of the IdP where the SP will send the Authentication Request Message,
                // using HTTP-Redirect binding.
                'url' => 'https://shibboleth.buffalo.edu/idp/profile/SAML2/Redirect/SSO',
            ),
            // SLO endpoint info of the IdP.
            'singleLogoutService' => array(
                // URL Location of the IdP where the SP will send the SLO Request,
                // using HTTP-Redirect binding.
                'url' => null,
            ),
            // Public x509 certificate of the IdP
            'x509cert' => env('SAML2_IDP_x509', '
            MIID8TCCAtmgAwIBAgIJAIwAxS9816XYMA0GCSqGSIb3DQEBCwUAMIGOMQswCQYD
            VQQGEwJVUzERMA8GA1UECAwITmV3IFlvcmsxEDAOBgNVBAcMB0J1ZmZhbG8xJDAi
            BgNVBAoMG1NVTlksIFVuaXZlcnNpdHkgYXQgQnVmZmFsbzETMBEGA1UECwwKc2hp
            YmJvbGV0aDEfMB0GA1UEAwwWc2hpYmJvbGV0aC5idWZmYWxvLmVkdTAeFw0xOTA3
            MDUxOTA3MDhaFw0yOTA3MDIxOTA3MDhaMIGOMQswCQYDVQQGEwJVUzERMA8GA1UE
            CAwITmV3IFlvcmsxEDAOBgNVBAcMB0J1ZmZhbG8xJDAiBgNVBAoMG1NVTlksIFVu
            aXZlcnNpdHkgYXQgQnVmZmFsbzETMBEGA1UECwwKc2hpYmJvbGV0aDEfMB0GA1UE
            AwwWc2hpYmJvbGV0aC5idWZmYWxvLmVkdTCCASIwDQYJKoZIhvcNAQEBBQADggEP
            ADCCAQoCggEBAMrK92tQ933kjE8h/XjtCOOb6QQGx9p+h7kjlkBLB9jw4fswBYQt
            S6yVM7/2+DeJfTPc+FKuBjLUhR9OqNxpExGO2kwvHkQWcUtrnPv+1duzIvcjeerM
            To6qH3TqcpMQrHaljYfl4pKhrIK/D/CFer/apOUDTUO1/Uc09tLqEZlqhNfSdA9A
            kpfDqj5dNk3mQ4zIyTy2TVdGq6Fm+qeQEhDC6xica+5J+NditOzcDxaMxGmpyxFJ
            YX66rQdmOQWNbuDokVU/RH2YMibFV+HC5iazh5msFHGmw+FM6oTQUygdfdy03HO5
            88nbaNcZ+y//UERGsilx4aJnpcKmCl9q3qkCAwEAAaNQME4wHQYDVR0OBBYEFK6b
            sIWN6u56XHvWBhpcVSVE0iDLMB8GA1UdIwQYMBaAFK6bsIWN6u56XHvWBhpcVSVE
            0iDLMAwGA1UdEwQFMAMBAf8wDQYJKoZIhvcNAQELBQADggEBACJLMAk0t3N60xEa
            6aAm7y4dxrhpevJLrtedyLS32VYLnosS1Yg92bd5cPywzbY057fbkrFJkfgJbgj8
            fx923zvbsVQfP7yl01rEy9DUiYi4OHZKeqT6xv7pw9sU7KuYQ9k/cxY2tVcCmIPW
            3ncO9aDu9QEKjZUM03Lb4TvRUH47V/1Xdoz2eX/94BwoMXuZz1MkEgnx8jHtOGj4
            edhmqk6AXkjW4XLs6hjGUErW3C2vy/2p2WRXmI6UQKvSFKpFwypeI9+oCp2AAa/D
            YNClrvzNFVFT6XeUaftcsCuBUdzg0Iocm3E/blWgyBmdw7zE5EcPZXtYfi989TuB
            hZTw6bQ=
            '),
            'data_map' => [
                'unique_id' => '{{#eduPersonTargetedID}}{{eduPersonTargetedID}}{{/eduPersonTargetedID}}{{^eduPersonTargetedID}}{{mail}}{{/eduPersonTargetedID}}',
                'first_name' => '{{givenName}}',
                'last_name' => '{{sn}}',
                'email' => '{{mail}}',
            ],
        ],
        'albany' => [
            'name' => 'University at Albany',
            // Identifier of the IdP entity  (must be a URI)
            'entityId' => env('SAML2_IDP_ENTITYID', 'https://weblogin.albany.edu/shibboleth/idp2'),
            // SSO endpoint info of the IdP. (Authentication Request protocol)
            'singleSignOnService' => array(
                // URL Target of the IdP where the SP will send the Authentication Request Message,
                // using HTTP-Redirect binding.
                'url' => 'https://weblogin.albany.edu/idp2/profile/SAML2/Redirect/SSO',
            ),
            // SLO endpoint info of the IdP.
            'singleLogoutService' => array(
                // URL Location of the IdP where the SP will send the SLO Request,
                // using HTTP-Redirect binding.
                'url' => null,
            ),
            // Public x509 certificate of the IdP
            'x509cert' => env('SAML2_IDP_x509', '
            MIIDODCCAiCgAwIBAgIVAKWha+CKTeinjttEcaqLm7fQSZLLMA0GCSqGSIb3DQEB
            BQUAMB4xHDAaBgNVBAMTE3dlYmxvZ2luLmFsYmFueS5lZHUwHhcNMDkwMjExMTk1
            NDE0WhcNMjkwMjExMTk1NDE0WjAeMRwwGgYDVQQDExN3ZWJsb2dpbi5hbGJhbnku
            ZWR1MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjAY1R6LqpUcsbpn3
            8AcJW/KtQTyVcdcHmkEeCdW3UNzd6ibmhRFcPVhXwY9Z8jAuHQLjIkPj4QuiaGXI
            lfRJBQkTnkWeGLrHCzZA7/eZ0uJxL+SBf84OiK2jSmLqw6xGAADE8GmcyBpTQe2d
            HDFDbLvsLc3UNs0U/WzIGEE5iPBd33UntVEYezO8vszjdKZr8r4ZRVTeSUe+qkgW
            19ncHiqrbPBT+5qK2j8PaCiFrOjc0ScnsqHkgxr5krBM//SGoFPc+2bClUtbF/ci
            wuNZ3RHxhkyxlwEwkhLyuhr9gpZDYH6mi/SKUXMcXdLBKev13ddXN3Is6JZxagXn
            qZv/cwIDAQABo20wazBKBgNVHREEQzBBghN3ZWJsb2dpbi5hbGJhbnkuZWR1hipo
            dHRwczovL3dlYmxvZ2luLmFsYmFueS5lZHUvaWRwL3NoaWJib2xldGgwHQYDVR0O
            BBYEFC5IsCUVHd9dT/Q7onCX7ItOnehlMA0GCSqGSIb3DQEBBQUAA4IBAQBy+8+Q
            vnDnS010CWPNF76wHA24ka7cX4FsV/+nsUbxCtL9lundZdBfzqTquy5GuF3j96ej
            foXg0Vz9GOn/KtRDDCFz8Gym+8hZdvPQ/XOdxHW1kjb7EbbL/K8dLilUYR9B/IOZ
            AXr4j4M4Qrh3jyxoFP+a4QTkUj/9+bcDPmqwftxaRoUIGcmyRzUvwU5pl227UkNy
            nftVP/rsZZ6nr0wJBZfPbhiBDAuzamzI05QHB+sFskVXB6Z8h2KawwNSrjgc2poj
            QWzZNOuth932vN8p2Lc6iI5v5bC/QSDDBlZ7tl+Uj/ejZC9b9WG/y83RrM333twp
            Kq1D/QWBwg4oa9Zq
            '),
            'data_map' => [
                'unique_id' => '{{#eduPersonTargetedID}}{{eduPersonTargetedID}}{{/eduPersonTargetedID}}{{^eduPersonTargetedID}}{{mail}}{{/eduPersonTargetedID}}',
                'first_name' => '{{givenName}}',
                'last_name' => '{{sn}}',
                'email' => '{{mail}}',
            ],
        ],
        'stonybrook' => [
            'name' => 'Stony Brook University',
            // Identifier of the IdP entity  (must be a URI)
            'entityId' => env('SAML2_IDP_ENTITYID', 'urn:mace:incommon:stonybrook.edu'),
            // SSO endpoint info of the IdP. (Authentication Request protocol)
            'singleSignOnService' => array(
                // URL Target of the IdP where the SP will send the Authentication Request Message,
                // using HTTP-Redirect binding.
                'url' => 'https://sso.cc.stonybrook.edu/idp/profile/SAML2/Redirect/SSO',
            ),
            // SLO endpoint info of the IdP.
            'singleLogoutService' => array(
                // URL Location of the IdP where the SP will send the SLO Request,
                // using HTTP-Redirect binding.
                'url' => null,
            ),
            // Public x509 certificate of the IdP
            'x509cert' => env('SAML2_IDP_x509', '
            MIIDQDCCAiigAwIBAgIVAM6zo1Tg/Cni0U1ZiS9qUjHwTb0qMA0GCSqGSIb3DQEB
            BQUAMCAxHjAcBgNVBAMTFXNzby5jYy5zdG9ueWJyb29rLmVkdTAeFw0xMjAzMzAy
            MTI4MTBaFw0zMjAzMzAyMTI4MTBaMCAxHjAcBgNVBAMTFXNzby5jYy5zdG9ueWJy
            b29rLmVkdTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAIZH1YAL8Nsb
            ZP7r1ZCfT0iXKNeVMUoes4lotQGna8lfAkwbBAOIi8z6Ck1pHtTwZBnRf0HalU2r
            +INFd/U2MQMUl63YrdhjXwkM7LepxMqj1nBRRS7W1qnS4B9N1Gx8h4RwaYVlW7YR
            EFSZjuqVTz3aJqr4IY6OjxHlXKCYy9q0x2QGgJm7z0/0K0K1w1LymoL8smE+7X4T
            foEKIZohNHxTPoM8tLU3XZhuMQL8TAtbUIz18+gyq0ug8Nf5mXkBPDSIQ92VWTKN
            CbFuu1meG18OXGNQwDsjj3D6WzoV8/h7wsVhf1tyBXfJ6GFfkhdJPJZTCgJzsGEk
            wVmSKNwTwzkCAwEAAaNxMG8wTgYDVR0RBEcwRYIVc3NvLmNjLnN0b255YnJvb2su
            ZWR1hixodHRwczovL3Nzby5jYy5zdG9ueWJyb29rLmVkdS9pZHAvc2hpYmJvbGV0
            aDAdBgNVHQ4EFgQUoH3ycV3oftfj3WFA33xtpoaRyUYwDQYJKoZIhvcNAQEFBQAD
            ggEBAE2vfQbGmZWnFMWylYeLqj7lvX5P1Se9i8DBJjy3tdCTIHdHTSRPLnnroFEb
            Au55cnXU3SeJ4jzHj3k4tOXQQfE+BGER47DtPuJ5Ey2Ug33DCrMoP0yjpwp3uTcy
            NRSzJT6FikcvJbGxzswA6chGOHWtGwe4dq+5Om0q8QQsQMX5o3TUrkL/9e4cSyHV
            beoZeLMhDf4M7wf971qx6tV+qVQqqSdDbQOPx+IKKXGuHCwKXwi1V1KjmYFqnOm6
            vjLJq/ZYknekwIgXDYdL99d5kwqV6W7vHm5V7j2fv0o+mNu46sL9Y+TVZPAnyw8b
            P5kJpNl6SkvUOjZ4nvr9i9FgmHc=
            '),
            'data_map' => [
                'unique_id' => '{{#eduPersonTargetedID}}{{eduPersonTargetedID}}{{/eduPersonTargetedID}}{{^eduPersonTargetedID}}{{mail}}{{/eduPersonTargetedID}}',
                'first_name' => '{{givenName}}',
                'last_name' => '{{sn}}',
                'email' => '{{mail}}',
            ],
        ],
        'sunyfederation' => [
            'name' => 'SUNY Federation (All SUNY Schools)',
            // Identifier of the IdP entity  (must be a URI)
            'entityId' => env('SAML2_IDP_ENTITYID', 'https://idm.suny.edu/fed/sp/metadata'),
            // SSO endpoint info of the IdP. (Authentication Request protocol)
            'singleSignOnService' => array(
                // URL Target of the IdP where the SP will send the Authentication Request Message,
                // using HTTP-Redirect binding.
                'url' => 'https://idm.suny.edu/fed/sp/samlv20',
            ),
            // SLO endpoint info of the IdP.
            'singleLogoutService' => array(
                // URL Location of the IdP where the SP will send the SLO Request,
                // using HTTP-Redirect binding.
                'url' => null,
            ),
            // Public x509 certificate of the IdP
            'x509cert' => env('SAML2_IDP_x509', '
            MIIDXDCCAkSgAwIBAgIEU0hI7zANBgkqhkiG9w0BAQUFADBwMQswCQYDVQQGEwJV UzELMAkGA1UECBMCTlkxDzANBgNVBAcTBkFsYmFueTEMMAoGA1UEChMDT0lUMSMw IQYDVQQLExpTVU5ZIFN5c3RlbSBBZG1pbmlzdHJhdGlvbjEQMA4GA1UEAxMHVW5r bm93bjAeFw0xNDA0MTExOTU2MzFaFw0yNDA0MDgxOTU2MzFaMHAxCzAJBgNVBAYT AlVTMQswCQYDVQQIEwJOWTEPMA0GA1UEBxMGQWxiYW55MQwwCgYDVQQKEwNPSVQx IzAhBgNVBAsTGlNVTlkgU3lzdGVtIEFkbWluaXN0cmF0aW9uMRAwDgYDVQQDEwdV bmtub3duMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAlZSaYvjczolB nsayjKHHcjQ9Z8EFy9C5w3l9SBsdrqXDmf+qtnl8JEaGiYTOnLXRt8bQpDWbqppD 5T8j840N8aKAvLZBtaB6Rj510cAY7ktVMSCkx6RvBumxEHrELyJ72azkWVsL2NHS 013jMrYub/fjiBXHiMlV/tYgU9uSCFHJdDVuETTHIwbYF4707hncPV1nGRMwqAaf pfddVkApNIiecklgsCbnNa7eASLK9S09J/Sq6gvxl0hkEMOZga+HByoTkMRdgDQT vd2G8mfV59KCUe/r1dl56RMnlrUu0015qouIjl3XPUQCfb+YJQHHBJEfoCyei7Ay /tZF5dD7EwIDAQABMA0GCSqGSIb3DQEBBQUAA4IBAQCM+xTaN0xvStbn51bXDTxG h6iA9DuiDCwZ10CS7R0aEBpbND4qdt1iq7fBqkYcYWnMqrhlOKbbUhsR+4eapGnB ih4+rQmOesAJQKpJJ7xF0z2nP9Molqp0vesPRaFUZPEcNN5x5ruAijrTPImzVLIK UN1UT8UjSsca2q+hnM0ow1G86KG07ObEYD/hYpQt19ktB+6tPDCYQj7Q4JoSNiuj oAbVDHc1K/l83Zb3EojEs4HQbEnMB8oHv/IqGmn75+NMrx8jZdV8RI8iek5rZWex vqyRb8fl8H0+CzXD5Ds6f0EX31/c4CMEhoL+RK8i2cnWpZzUE6/P7I6PyQnGRlgF
            '),
            'data_map' => [
                'unique_id' => '{{#eduPersonTargetedID}}{{eduPersonTargetedID}}{{/eduPersonTargetedID}}{{^eduPersonTargetedID}}{{mail}}{{/eduPersonTargetedID}}',
                'first_name' => '{{givenName}}',
                'last_name' => '{{sn}}',
                'email' => '{{mail}}',
            ],
        ],

    ]
];
