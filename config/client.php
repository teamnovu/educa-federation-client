<?php

return [
    'strict' => true,
    'debug' => false,
    'sp' => [

        // Specifies constraints on the name identifier to be used to
        // represent the requested subject.
        // Take a look on lib/Saml2/Constants.php to see the NameIdFormat supported
        'NameIDFormat' =>  \LightSaml\SamlConstants::NAME_ID_FORMAT_UNSPECIFIED,

        // Usually x509cert and privateKey of the SP are provided by files placed at
        // the certs folder. But we can also provide them with the following parameters
        'x509cert' => null,

        'privateKey' => null,

        // Identifier (URI) of the SP entity.
        'entityId' => null,

        // Specifies info about where and how the <AuthnResponse> message MUST be
        // returned to the requester, in this case our SP.
        'assertionConsumerService' => [
            // URL Location where the <Response> from the IdP will be returned,
            // using HTTP-POST binding.
            'url' => null,
        ],
        // Specifies info about where and how the <Logout Response> message MUST be
        // returned to the requester, in this case our SP.
        // Remove this part to not include any URL Location in the metadata.
        'singleLogoutService' => [
            // URL Location where the <Response> from the IdP will be returned,
            // using HTTP-Redirect binding.
            'url' => null,
        ],
    ],

    // Identity Provider Data that we want connect with our SP
    'idp' => [
        // Identifier of the IdP entity  (must be a URI)
        'entityId' => 'https://federation.educa.ch/saml/metadata',
        // SSO endpoint info of the IdP. (Authentication Request protocol)
        'singleSignOnService' => [
            // URL Target of the IdP where the SP will send the Authentication Request Message,
            // using HTTP-Redirect binding.
            'url' => 'http://localhost/login',
        ],
        // SLO endpoint info of the IdP.
        'singleLogoutService' => [
            // URL Location of the IdP where the SP will send the SLO Request,
            // using HTTP-Redirect binding.
            'url' => null, // not yet supported
        ],
        // Public x509 certificate of the IdP
        'x509cert' => 'MIIFejCCA2KgAwIBAgIUac6ofpeV+D8aUapcQBJop6HwxlowDQYJKoZIhvcNAQEFBQAwdzELMAkGA1UEBgwCQ0gxDTALBgNVBAgMBEJlcm4xDTALBgNVBAcMBEJlcm4xFDASBgNVBAoMC0RldmVsb3BtZW50MRYwFAYDVQQLDA1JVCBEZXBhcnRtZW50MRwwGgYDVQQDDBNmZWRlcmF0aW9uLmVkdWNhLmNoMB4XDTE5MDUxNjA4MTgyMVoXDTIwMDUxNjA4MTgyMVowdzELMAkGA1UEBgwCQ0gxDTALBgNVBAgMBEJlcm4xDTALBgNVBAcMBEJlcm4xFDASBgNVBAoMC0RldmVsb3BtZW50MRYwFAYDVQQLDA1JVCBEZXBhcnRtZW50MRwwGgYDVQQDDBNmZWRlcmF0aW9uLmVkdWNhLmNoMIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEArIt+p3PmUlXj/KJWL9TmMB1h8hXCtXuJcNsEGqhClvRyc3igzHUZHN7A5o4Hhphy4iQqUrG1SvGB+sAAbxYr0esbh7yVE6VIengUHyrgl0wdUIftiaj3LNhVl+eaPjJqyjIjzi4wy4zLn+Ozh2hWOT52I5AuUcYFjIfOUc2tJnZpjvfEyBPE6pvubPTImZIEjkRyxG9zHee8c1E2/MvbrZUQgsc8670a33M0Bpd3aWtMqvJ1bBCuIIICKUSuoK9KfZ3wEo8vpFNQ7bBk1YAaRucG43Ga2Nhz185kHuhBtliwcOO2XhwHVb+flmD5djRgf9ZNv3gR3oE5R8Ny6CShTyoH6VAT+Kcdixj2EHSSWSbGqXbrKHMTCq7d2wl01Ffj7qTmYb6O5JoDq7oxZipv/2oubsAhEIsWbFSdIZRdbdLJ/LREu/R6UAVdDljwRWzlKAuveTUW8WqLGoFJaAVGpw2GEhpZYoC1u7+02jqwY7dY6B4ASvaP66TFr5ognzK5HripTGbEovORcFoKjRoFcTtS6NiCbB7wTCb9M0GnRe59/IRgco31VOYBsFwQQhOgUOxy1cyWOoN4bKOXUmQFTzu2I4RC7RLRs2EaYLFn/eJBpgJGGDcDpzT5P/Ug1rI4DGoOiNxEL+TKzmLfCj8oRUdyOGE0NKtLC1mbJAtJwwMCAwEAATANBgkqhkiG9w0BAQUFAAOCAgEANczseWnuWxx7hyThm4591cR66adWZnPYE3r4i+JEg3Ri28AD0z+aXVNX5KTko2b38B379GkwNSZk7E4LneGFKHNRi5Rb37YsIWDcmlN2nLk2pDDg1LL8/TPhtfQr0LpiNOxvnZYVlVNjxeYioD3qA6JYLNJvAlWGog3K5n+kRtgxp2y23YCQYK6MDpPF2ETJGjeIgqN7nyfmM+I49WNt5Ha0Eq9WPkJh/dahKwc0zNlGvKzX7bLGaGR80+jQ2fdD48h4jv1qrf2XbBlYGQ4SN2A5fAl2a4iq4F/V8M0tvMIvABdZSknK4oUpcttvLg+Lbg0vn1XBS1if1XloRwV/Yzxwe2MXhN/sRgJmFuCJHAids+pCNaGZefUP6LyDgyAnHot+fKmBM+OCaUuBalWcypxZ3ahxCEYo5Esv8ERmG/0NRnc7eYS+Rnoz39eDIm2oRJUpo3NS4B+LvBSB8TTdvViXd3Mmqa5cz3DcMPlbV7MuNkd9EzNwi+3Suk8n8cqwQV9aM167aJ2Wb+m2o75ITZmYsul63Z0CwmkzIZAM+OW43nR4lOKIlcmKBW2uyh4j7THnajViAPVEkcsrGaC5InL7mz6W67Oaamh4TLKu1jIw5Y+e6TOWuyOOPpoPH3oWFwU3FSvQ2AnB0k3kwXnKCAw6IoCjNOhMgMYggvZc23c=',
    ],

    /***
     *  OneLogin advanced settings
     */
    'security' => [

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

        /* signatures and encryptions required **/

        // Indicates a requirement for the <samlp:Response>, <samlp:LogoutRequest> and
        // <samlp:LogoutResponse> elements received by this SP to be signed.
        'wantMessagesSigned' => true,

        // Indicates a requirement for the <saml:Assertion> elements received by
        // this SP to be signed.        [The Metadata of the SP will offer this info]
        'wantAssertionsSigned' => true,

        'wantAssertionsEncrypted' => true,

        // Indicates a requirement for the NameID received by
        // this SP to be encrypted.
        'wantNameIdEncrypted' => false,

        // Authentication context.
        // Set to false and no AuthContext will be sent in the AuthNRequest,
        // Set true or don't present thi parameter and you will get an AuthContext 'exact' 'urn:oasis:names:tc:SAML:2.0:ac:classes:PasswordProtectedTransport'
        // Set an array with the possible auth context values: array ('urn:oasis:names:tc:SAML:2.0:ac:classes:Password', 'urn:oasis:names:tc:SAML:2.0:ac:classes:X509'),
        'requestedAuthnContext' => true,
    ],

    // Contact information template, it is recommended to suply a technical and support contacts
    'contactPerson' => [
        'technical' => [
            'givenName' => null,
            'emailAddress' => null,
        ],
        'support' => [
            'givenName' => null,
            'emailAddress' => null,
        ],
    ],

    // Organization information template, the info in en_US lang is recomended, add more if required
    'organization' => [
        'en-US' => [
            'name' => 'Schweizer Medieninstitut für Bildung und Kultur Genossenschaft',
            'displayname' => 'educa.ch',
            'url' => 'https://educa.ch',
        ],
    ],

    /* Interoperable SAML 2.0 Web Browser SSO Profile [saml2int]   http://saml2int.org/profile/current

       'authnRequestsSigned' => false,    // SP SHOULD NOT sign the <samlp:AuthnRequest>,
                                          // MUST NOT assume that the IdP validates the sign
       'wantAssertionsSigned' => true,
       'wantAssertionsEncrypted' => true, // MUST be enabled if SSL/HTTPs is disabled
       'wantNameIdEncrypted' => false,
    */

];
