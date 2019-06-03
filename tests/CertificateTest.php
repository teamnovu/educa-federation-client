<?php
namespace Teamnovu\SamlClient\Tests;

use PHPUnit\Framework\TestCase;
use Teamnovu\SamlClient\Certificate;
use phpseclib\File\X509;
use phpseclib\Crypt\RSA;

class CertificateTest extends TestCase
{
    /** @test */
    public function it_can_create_cert_with_given_dn_and_default_dates()
    {
        // Arrange

        // Act
        $cert = Certificate::new()
        ->setCountry('CH')
        ->setStateOrProvinceName('Bern')
        ->setLocality('Thun')
        ->setOrganization('Example Company')
        ->setOrganizationUnit('IT Department')
        ->setCommonName('example.com')
        ->generate();

        // Assert
        $x509 = new X509();
        $x509->loadX509($cert['x509']);
        $this->assertEquals('CH', $x509->getDNProp('C')[0]);
        $this->assertEquals('Bern', $x509->getDNProp('ST')[0]);
        $this->assertEquals('Thun', $x509->getDNProp('L')[0]);
        $this->assertEquals('Example Company', $x509->getDNProp('O')[0]);
        $this->assertEquals('IT Department', $x509->getDNProp('OU')[0]);
        $this->assertEquals('example.com', $x509->getDNProp('CN')[0]);
        $this->assertTrue($x509->validateDate());
        $this->assertFalse($x509->validateDate('+1 years 1 days'));

        $rsa = new RSA();
        $rsa->setPrivateKey($cert['private_key']);
        $this->assertEquals(4096, $rsa->getSize());
    }

    /** @test */
    public function it_can_create_cert_with_expiry_date()
    {
        // Arrange

        // Act
        $cert = Certificate::new()
        ->setCountry('CH')
        ->setStateOrProvinceName('Bern')
        ->setLocality('Thun')
        ->setOrganization('Example Company')
        ->setOrganizationUnit('IT Department')
        ->setCommonName('example.com')
        ->setExpiryDate('lifetime')
        ->generate();

        // Assert
        $x509 = new X509();
        $x509->loadX509($cert['x509']);
        $this->assertEquals('CH', $x509->getDNProp('C')[0]);
        $this->assertEquals('Bern', $x509->getDNProp('ST')[0]);
        $this->assertEquals('Thun', $x509->getDNProp('L')[0]);
        $this->assertEquals('Example Company', $x509->getDNProp('O')[0]);
        $this->assertEquals('IT Department', $x509->getDNProp('OU')[0]);
        $this->assertEquals('example.com', $x509->getDNProp('CN')[0]);
        $this->assertTrue($x509->validateDate());
        $this->assertTrue($x509->validateDate('+4 years 364 days'));

        $rsa = new RSA();
        $rsa->setPrivateKey($cert['private_key']);
        $this->assertEquals(4096, $rsa->getSize());
    }
}
