<?php

namespace Teamnovu\SamlClient;

use OneLogin\Saml2\Auth as OneLoginSaml2Auth;

class Saml2AuthFactory
{
    public static function forServiceProvider($spConfig)
    {
        $sp = $spConfig;
        $contact = $spConfig['contactPerson'];
        unset($spConfig['contactPerson']);

        return static::make([
            'sp' => $sp,
            'contactPerson' => $contact,
        ]);
    }

    public static function make(array $overwriteConfig = []): Saml2Auth
    {
        $config = require __DIR__.'/../config/client.php';
        $config = array_merge($config, $overwriteConfig);
        $login = new OneLoginSaml2Auth($config);
        $saml2Auth = new Saml2Auth($login);

        return $saml2Auth;
    }
}
