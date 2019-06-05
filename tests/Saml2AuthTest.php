<?php

namespace Teamnovu\SamlClient\Tests;

use LightSaml\ClaimTypes;
use PHPUnit\Framework\TestCase;
use Teamnovu\SamlClient\Saml2AuthFactory;
use Teamnovu\SamlClient\Tests\Support\SamlHelper;

class Saml2AuthTest extends TestCase
{
    /** @test */
    public function it_can_setup_a_new_instance_with_default_settings()
    {
        // Arrange
        $settings = require __DIR__.'/Fixtures/settings.php';
        $attributes = [
            ClaimTypes::EMAIL_ADDRESS => 'test@example.com',
            ClaimTypes::COMMON_NAME => 'John Doe',
        ];

        $samlResponse = SamlHelper::createSamlResponse(
            $attributes,
            'test@example.com',
            'http://localhost/relay-state',
            'http://localhost/saml/sp/acs',
            'http://localhost/saml/sp/metadata',
            $settings['sp']['x509cert'],
            'http://localhost/saml/idp/metadata',
            $settings['idp']['privateKey'],
            $settings['idp']['x509cert'],
            $settings['security']['wantAssertionsSigned'],
            $settings['security']['wantMessagesSigned'],
            $settings['security']['wantAssertionsEncrypted']
        );
        $_POST['SAMLResponse'] = $samlResponse;
        $_POST['RelayState'] = 'http://localhost/relay-state';

        // Act
        $client = Saml2AuthFactory::make($settings);
        $errors = $client->acs();
        $lastError = $client->getLastErrorReason();

        // Assert
        $this->assertIsNotArray($errors);
        $user = $client->getSaml2User();
        $this->assertEquals(2, count($user->getAttributes()));
        $this->assertEquals('test@example.com', $user->getAttribute(ClaimTypes::EMAIL_ADDRESS)[0]);
        $this->assertEquals('John Doe', $user->getAttribute(ClaimTypes::COMMON_NAME)[0]);
        $this->assertEquals('test@example.com', $user->getNameId());
        $this->assertEquals('http://localhost/relay-state', $user->getIntendedUrl());
    }

    /** @test */
    public function it_can_generate_the_metadata_xml_content()
    {
        // Arrange
        $settings = require __DIR__.'/Fixtures/settings.php';
        $client = Saml2AuthFactory::make($settings);

        // Act
        $metadata = $client->getMetadata();

        // Assert
        $this->assertNotNull($metadata);
    }
}
