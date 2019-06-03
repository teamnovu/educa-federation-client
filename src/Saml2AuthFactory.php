<?php

namespace App\Saml;

use OneLogin\Saml2\Auth as OneLoginSaml2Auth;

class Saml2AuthFactory
{
    public static function make(array $settings): Saml2Auth
    {
        $login = new OneLoginSaml2Auth($settings);
        $saml2Auth = new Saml2Auth($login);

        return $saml2Auth;
    }
}
