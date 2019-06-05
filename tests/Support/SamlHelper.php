<?php

namespace Teamnovu\SamlClient\Tests\Support;

use LightSaml\Helper;
use LightSaml\SamlConstants;
use LightSaml\Credential\KeyHelper;
use LightSaml\Model\Assertion\Issuer;
use LightSaml\Model\Assertion\NameID;
use LightSaml\Model\Assertion\Subject;
use LightSaml\Model\Assertion\Assertion;
use LightSaml\Model\Assertion\Attribute;
use LightSaml\Credential\X509Certificate;
use LightSaml\Model\Assertion\Conditions;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use LightSaml\Model\Assertion\AuthnContext;
use LightSaml\Model\Assertion\AuthnStatement;
use LightSaml\Model\Assertion\AttributeStatement;
use LightSaml\Model\Assertion\AudienceRestriction;
use LightSaml\Model\Assertion\SubjectConfirmation;
use LightSaml\Model\Assertion\SubjectConfirmationData;
use LightSaml\Model\Assertion\EncryptedAssertionWriter;
use LightSaml\Context\Profile\Helper\MessageContextHelper;

class SamlHelper
{
    public static function createSamlResponse(
        $attributes,
        $userNameId,
        $relayState,
        $acsPath,
        $spEntityId,
        $spCert,
        $idpEntityId,
        $idpPrivateKey,
        $idpCert,
        $signAssertion = true,
        $signMessage = true,
        $encryptAssertion = true
    ) {
        $certificate = (new X509Certificate())->setData($idpCert);
        $privateKey = KeyHelper::createPrivateKey($idpPrivateKey, '', false, XMLSecurityKey::RSA_SHA256);

        $assertion = new Assertion();
        $assertion
            ->setId(Helper::generateID())
            ->setIssueInstant(new \DateTime())
            ->setIssuer(new Issuer($idpEntityId))
            ->setSubject(
                (new Subject())
                    ->setNameID(new NameID(
                        $userNameId,
                        SamlConstants::NAME_ID_FORMAT_UNSPECIFIED
                    ))
                    ->addSubjectConfirmation(
                        (new SubjectConfirmation())
                            ->setMethod(SamlConstants::CONFIRMATION_METHOD_BEARER)
                            ->setSubjectConfirmationData(
                                (new SubjectConfirmationData())
                                    // ->setInResponseTo($cookieId)
                                    ->setNotOnOrAfter(new \DateTime('+1 MINUTE'))
                                    // ->setRecipient($acsPath)
                            )
                    )
            )
            ->setConditions(
                (new Conditions())
                    ->setNotBefore(new \DateTime())
                    ->setNotOnOrAfter(new \DateTime('+1 MINUTE'))
                    ->addItem(
                        new AudienceRestriction([$spEntityId])
                    )
            )
            ->addItem(
                (new AuthnStatement())
                    ->setAuthnInstant(new \DateTime('-10 MINUTE'))
                    ->setSessionIndex(Helper::generateID())
                    ->setAuthnContext(
                        (new AuthnContext())
                            ->setAuthnContextClassRef(SamlConstants::AUTHN_CONTEXT_PASSWORD_PROTECTED_TRANSPORT)
                    )
            );

        $statement = new AttributeStatement();
        foreach ($attributes as $name => $value) {
            $statement->addAttribute(new Attribute(
                    $name,
                    $value
                ));
        }
        $assertion->addItem($statement);

        if ($signAssertion) {
            $assertion->setSignature(new \LightSaml\Model\XmlDSig\SignatureWriter($certificate, $privateKey));
        }

        $response = new \LightSaml\Model\Protocol\Response();
        $response
            ->setStatus(new \LightSaml\Model\Protocol\Status(new \LightSaml\Model\Protocol\StatusCode(\LightSaml\SamlConstants::STATUS_SUCCESS)))
            ->setID(\LightSaml\Helper::generateID())
            ->setIssueInstant(new \DateTime())
            // ->setDestination($acsPath)
            ->setIssuer(new \LightSaml\Model\Assertion\Issuer($idpEntityId));

        if ($encryptAssertion) {
            $remoteCertificate = new \LightSaml\Credential\X509Certificate();
            $remoteCertificate->setData($spCert);
            $encryptedAssertion = new EncryptedAssertionWriter();
            $encryptedAssertion->encrypt($assertion, KeyHelper::createPublicKey($remoteCertificate));
            $response->addEncryptedAssertion($encryptedAssertion);
        } else {
            $response->addAssertion($assertion);
        }

        if ($signMessage) {
            $response->setSignature(new \LightSaml\Model\XmlDSig\SignatureWriter($certificate, $privateKey));
        }

        $messageContext = new \LightSaml\Context\Profile\MessageContext();
        $messageContext->setMessage($response);

        $message = MessageContextHelper::asSamlMessage($messageContext);

        $serializationContext = $messageContext->getSerializationContext();
        $message->serialize($serializationContext->getDocument(), $serializationContext);
        $msgStr = $serializationContext->getDocument()->saveXML();

        $response->setRelayState($relayState);
        $msgStr = base64_encode($msgStr);

        return $msgStr;
    }
}
