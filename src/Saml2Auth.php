<?php

namespace Teamnovu\SamlClient;

use OneLogin\Saml2\Auth;
use OneLogin\Saml2\Error;
use Psr\Log\InvalidArgumentException;
use Teamnovu\SamlClient\Exceptions\SamlUnauthenticatedException;

class Saml2Auth
{
    /**
     * @var Auth
     */
    protected $auth;

    protected $samlAssertion;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @return bool if a valid user was fetched from the saml assertion this request.
     */
    public function isAuthenticated()
    {
        $auth = $this->auth;

        return $auth->isAuthenticated();
    }

    /**
     * The user info from the assertion.
     * @return Saml2User
     */
    public function getSaml2User()
    {
        return new Saml2User($this->auth);
    }

    /**
     * The ID of the last message processed.
     * @return string
     */
    public function getLastMessageId()
    {
        return $this->auth->getLastMessageId();
    }

    /**
     * Initiate a saml2 login flow. It will redirect! Before calling this, check if user is
     * authenticated (here in saml2). That would be true when the assertion was received this request.
     *
     * @param string|null $returnTo        The target URL the user should be returned to after login.
     * @param array       $parameters      Extra parameters to be added to the GET
     * @param bool        $forceAuthn      When true the AuthNReuqest will set the ForceAuthn='true'
     * @param bool        $isPassive       When true the AuthNReuqest will set the Ispassive='true'
     * @param bool        $stay            True if we want to stay (returns the url string) False to redirect
     * @param bool        $setNameIdPolicy When true the AuthNReuqest will set a nameIdPolicy element
     *
     * @return string|null If $stay is True, it return a string with the SLO URL + LogoutRequest + parameters
     */
    // public function login($returnTo = null, $parameters = [], $forceAuthn = false, $isPassive = false, $stay = false, $setNameIdPolicy = true)
    // {
    //     $auth = $this->auth;

    //     return $auth->login($returnTo, $parameters, $forceAuthn, $isPassive, $stay, $setNameIdPolicy);
    // }

    /**
     * Initiate a saml2 logout flow. It will close session on all other SSO services. You should close
     * local session if applicable.
     *
     * @param string|null $returnTo            The target URL the user should be returned to after logout.
     * @param string|null $nameId              The NameID that will be set in the LogoutRequest.
     * @param string|null $sessionIndex        The SessionIndex (taken from the SAML Response in the SSO process).
     * @param string|null $nameIdFormat        The NameID Format will be set in the LogoutRequest.
     * @param bool        $stay            True if we want to stay (returns the url string) False to redirect
     * @param string|null $nameIdNameQualifier The NameID NameQualifier will be set in the LogoutRequest.
     *
     * @return string|null If $stay is True, it return a string with the SLO URL + LogoutRequest + parameters
     *
     * @throws Error
     */
    // public function logout($returnTo = null, $nameId = null, $sessionIndex = null, $nameIdFormat = null, $stay = false, $nameIdNameQualifier = null)
    // {
    //     $auth = $this->auth;

    //     return $auth->logout($returnTo, [], $nameId, $sessionIndex, $stay, $nameIdFormat, $nameIdNameQualifier);
    // }

    /**
     * Process a Saml response (assertion consumer service)
     * When errors are encountered, it returns an array with proper description.
     */
    public function acs(): void
    {
        /** @var $auth Auth */
        $auth = $this->auth;

        $auth->processResponse();

        $errors = $auth->getErrors();

        if (is_array($errors) && count($errors) > 0) {
            throw $auth->getLastErrorException();
        }

        if (! $auth->isAuthenticated()) {
            throw new SamlUnauthenticatedException();
        }
    }

    /**
     * Process a Saml response (assertion consumer service)
     * returns an array with errors if it can not logout.
     */
    // public function sls($retrieveParametersFromServer = false, $sessionCallback = null)
    // {
    //     $auth = $this->auth;

    //     // destroy the local session by firing the Logout event
    //     $keepLocalSession = false;

    //     $auth->processSLO($keepLocalSession, null, $retrieveParametersFromServer, $sessionCallback);

    //     $errors = $auth->getErrors();

    //     return $errors;
    // }

    /**
     * Show metadata about the local sp. Use this to configure your saml2 IDP.
     * @return mixed xml string representing metadata
     * @throws \InvalidArgumentException if metadata is not correctly set
     */
    public function getMetadata()
    {
        $auth = $this->auth;
        $settings = $auth->getSettings();
        $metadata = $settings->getSPMetadata();
        $errors = $settings->validateMetadata($metadata);

        if (empty($errors)) {
            return $metadata;
        } else {
            throw new InvalidArgumentException(
                'Invalid SP metadata: '.implode(', ', $errors),
                Error::METADATA_SP_INVALID
            );
        }
    }

    /**
     * Get the last error reason from \Auth, useful for error debugging.
     * @see \Auth::getLastErrorReason()
     * @return string
     */
    public function getLastErrorReason()
    {
        return $this->auth->getLastErrorReason();
    }

    public function getLastErrorException()
    {
        return $this->auth->getLastErrorException();
    }
}
