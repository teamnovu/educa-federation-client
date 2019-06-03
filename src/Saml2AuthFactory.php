<?php

namespace Teamnovu\SamlClient;

use OneLogin\Saml2\Auth as OneLoginSaml2Auth;

class Saml2AuthFactory
{
    public static function make(array $overwriteSettings = []): Saml2Auth
    {
        $settings = require(__DIR__.'/../config/settings.php');
        $settings = array_merge($settings, $overwriteSettings);
        $login = new OneLoginSaml2Auth($settings);
        $saml2Auth = new Saml2Auth($login);

        return $saml2Auth;
    }
}
