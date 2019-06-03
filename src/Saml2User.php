<?php

namespace Teamnovu\SamlClient;

use OneLogin\Saml2\Auth;

/**
 * A simple class that represents the user that 'came' inside the saml2 assertion
 */
class Saml2User
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @return string User Id retrieved from assertion processed this request
     */
    public function getUserId()
    {
        $auth = $this->auth;

        return $auth->getNameId();
    }

    /**
     * @return array attributes retrieved from assertion processed this request
     */
    public function getAttributes()
    {
        $auth = $this->auth;

        return $auth->getAttributes();
    }

    /**
     * Returns the requested SAML attribute
     *
     * @param string $name The requested attribute of the user.
     * @return array|null Requested SAML attribute ($name).
     */
    public function getAttribute($name)
    {
        $auth = $this->auth;

        return $auth->getAttribute($name);
    }

    /**
     * @return array attributes retrieved from assertion processed this request
     */
    public function getAttributesWithFriendlyName()
    {
        $auth = $this->auth;

        return $auth->getAttributesWithFriendlyName();
    }

    /**
    * Parses a SAML property and adds this property to this user or returns the value
    *
    * @param string $samlAttribute
    * @param string $propertyName
    * @return array|null
    */
    public function parseUserAttribute($samlAttribute = null, $propertyName = null)
    {
        if (empty($samlAttribute)) {
            return null;
        }
        if (empty($propertyName)) {
            return $this->getAttribute($samlAttribute);
        }

        return $this->{$propertyName} = $this->getAttribute($samlAttribute);
    }

    /**
     * Parse the saml attributes and adds it to this user
     *
     * @param array $attributes Array of properties which need to be parsed, like this ['email' => 'urn:oid:0.9.2342.19200300.100.1.3']
     */
    public function parseAttributes($attributes = [])
    {
        foreach ($attributes as $propertyName => $samlAttribute) {
            $this->parseUserAttribute($samlAttribute, $propertyName);
        }
    }

    public function getSessionIndex()
    {
        return $this->auth->getSessionIndex();
    }

    public function getNameId()
    {
        return $this->auth->getNameId();
    }

    public function getIntendedUrl()
    {
        if ($_POST['RelayState']) {
            return $_POST['RelayState'];
        } elseif ($_GET['RelayState']) {
            return $_GET['RelayState'];
        }

        return null;
    }
}
