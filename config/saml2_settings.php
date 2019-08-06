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
        'support' => array(
            'givenName' => 'Sarah Lynch',
            'emailAddress' => 'selynch@binghamton.edu'
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
            'entityId' => env('SAML2_IDP_ENTITYID', 'https://shibboleth.buffalo.edu/idp/shibboleth'),
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
                'url' => 'https://shibboleth.buffalo.edu/idp/profile/SAML2/Redirect/SLO',
            ),
            // Public x509 certificate of the IdP
            'x509cert' => env('SAML2_IDP_x509', '
            MIIGiDCCBXCgAwIBAgIRAPhtlvy0TuFvgXffz2JAt+wwDQYJKoZIhvcNAQELBQAw
            djELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAk1JMRIwEAYDVQQHEwlBbm4gQXJib3Ix
            EjAQBgNVBAoTCUludGVybmV0MjERMA8GA1UECxMISW5Db21tb24xHzAdBgNVBAMT
            FkluQ29tbW9uIFJTQSBTZXJ2ZXIgQ0EwHhcNMTgwNzE2MDAwMDAwWhcNMTkwNzE2
            MjM1OTU5WjCBsTELMAkGA1UEBhMCVVMxDjAMBgNVBBETBTE0MjYwMQswCQYDVQQI
            EwJOWTEQMA4GA1UEBxMHQnVmZmFsbzEXMBUGA1UECRMONTAxIENhcGVuIEhhbGwx
            JDAiBgNVBAoTG1NVTlksIFVuaXZlcnNpdHkgYXQgQnVmZmFsbzETMBEGA1UECxMK
            c2hpYmJvbGV0aDEfMB0GA1UEAxMWc2hpYmJvbGV0aC5idWZmYWxvLmVkdTCCASIw
            DQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAMrK92tQ933kjE8h/XjtCOOb6QQG
            x9p+h7kjlkBLB9jw4fswBYQtS6yVM7/2+DeJfTPc+FKuBjLUhR9OqNxpExGO2kwv
            HkQWcUtrnPv+1duzIvcjeerMTo6qH3TqcpMQrHaljYfl4pKhrIK/D/CFer/apOUD
            TUO1/Uc09tLqEZlqhNfSdA9AkpfDqj5dNk3mQ4zIyTy2TVdGq6Fm+qeQEhDC6xic
            a+5J+NditOzcDxaMxGmpyxFJYX66rQdmOQWNbuDokVU/RH2YMibFV+HC5iazh5ms
            FHGmw+FM6oTQUygdfdy03HO588nbaNcZ+y//UERGsilx4aJnpcKmCl9q3qkCAwEA
            AaOCAtMwggLPMB8GA1UdIwQYMBaAFB4Fo3ePbJbiW4dLprSGrHEADOc4MB0GA1Ud
            DgQWBBSum7CFjeruelx71gYaXFUlRNIgyzAOBgNVHQ8BAf8EBAMCBaAwDAYDVR0T
            AQH/BAIwADAdBgNVHSUEFjAUBggrBgEFBQcDAQYIKwYBBQUHAwIwZwYDVR0gBGAw
            XjBSBgwrBgEEAa4jAQQDAQEwQjBABggrBgEFBQcCARY0aHR0cHM6Ly93d3cuaW5j
            b21tb24ub3JnL2NlcnQvcmVwb3NpdG9yeS9jcHNfc3NsLnBkZjAIBgZngQwBAgIw
            RAYDVR0fBD0wOzA5oDegNYYzaHR0cDovL2NybC5pbmNvbW1vbi1yc2Eub3JnL0lu
            Q29tbW9uUlNBU2VydmVyQ0EuY3JsMHUGCCsGAQUFBwEBBGkwZzA+BggrBgEFBQcw
            AoYyaHR0cDovL2NydC51c2VydHJ1c3QuY29tL0luQ29tbW9uUlNBU2VydmVyQ0Ff
            Mi5jcnQwJQYIKwYBBQUHMAGGGWh0dHA6Ly9vY3NwLnVzZXJ0cnVzdC5jb20wIQYD
            VR0RBBowGIIWc2hpYmJvbGV0aC5idWZmYWxvLmVkdTCCAQUGCisGAQQB1nkCBAIE
            gfYEgfMA8QB2AO5Lvbd1zmC64UJpH6vhnmajD35fsHLYgwDEe4l6qP3LAAABZKQ/
            aa8AAAQDAEcwRQIgXJW5jyo7b/vmzTAHaAcdpSjTdf3mhgD6U3Ic4Lg5FhwCIQCN
            oCGURhiCxOdbY9dyJGj54v2ZcAR6STFCe+47RQDqmwB3AHR+2oMxrTMQkSGcziVP
            QnDCv/1eQiAIxjc1eeYQe8xWAAABZKQ/bCUAAAQDAEgwRgIhAIWkDWn2SXXCphLF
            Nwk4kYTSP7g99uLG556jdjc8EGOqAiEAiVOqSfEzC+9X1HlPhNc3ZXFuvWkjyghC
            G9svSBdp43owDQYJKoZIhvcNAQELBQADggEBAFfOEcxaHgturAna2KrjYcCaUlF1
            2d0elyRojp1KqaOy1QqD3SaYexQYxm1sm+xYekN5QsKOcUdynrTh5/YBVYe3OdNT
            7Ztf2gkz9tffE456DCsSL1qo09CFPagTiOrOHNLw3KtA8ir4q3Es/KML6VqV/Rbd
            bdLVQxUEHSAXl4GquQ4pawR6eoBJmSn2YL8rK24/TAVjGrFpVXMFxEx73g+6vn2L
            ca8qV6BzmUtwnA7UTCeL/eIaM41vwKuiRGamhGT5qR++fibr0qFtXn1k436M5rD+
            DhluZZ0sGI/0bkISU4g/vGZTu8xwTnCifQKAXf4VfP35h/VeHbQyv5922JU=
            '),
            'data_map' => [
                'unique_id' => '{{bnumber}}',
                'first_name' => '{{givenName}}',
                'last_name' => '{{sn}}',
                'email' => '{{mail}}',
            ],
        ],
    ]
];
