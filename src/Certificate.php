<?php

namespace Teamnovu\SamlClient;

use phpseclib\Crypt\RSA;
use phpseclib\File\X509;

class Certificate
{
    protected $country;
    protected $stateOrProvinceName;
    protected $locality;
    protected $organization;
    protected $organizationUnit;
    protected $commonName;
    protected $startDate;
    protected $expiryDate;
    protected $keyLength;

    private function __construct()
    {
    }

    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    public function setStateOrProvinceName($stateOrProvinceName)
    {
        $this->stateOrProvinceName = $stateOrProvinceName;

        return $this;
    }

    public function setLocality($locality)
    {
        $this->locality = $locality;

        return $this;
    }

    public function setOrganization($organization)
    {
        $this->organization = $organization;

        return $this;
    }

    public function setOrganizationUnit($organizationUnit)
    {
        $this->organizationUnit = $organizationUnit;

        return $this;
    }

    public function setCommonName($commonName)
    {
        $this->commonName = $commonName;

        return $this;
    }

    /**
     * The start time is, by default, when the cert is created. The current time is converted to UTC time and the fact that * it's UTC time is denoted in the cert. Other X.509 decoders (eg. browsers or email clients or whatever) should decode * this to their timezone so there's no need to set it to do $x509->setStartDate('-1 day') or anything like that.
     *
     * setStartDate() / setEndDate() are passed through strtotime() internally. If you want the cert to last forever pass * * 'lifetime' to it.
     * @param string $startDate
     * @return void
     */
    public function setStartDate(string $startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * The end date, by default, is one year from the current time.
     *
     * setStartDate() / setEndDate() are passed through strtotime() internally. If you want the cert to last forever pass * * 'lifetime' to it.
     *
     * @param string $expiryDate
     * @return void
     */
    public function setExpiryDate(string $expiryDate)
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    public function setKeyLength($keyLength)
    {
        $this->keyLength = $keyLength;

        return $this;
    }

    public static function new()
    {
        return new self();
    }

    public function generate()
    {
        // followed by this guide http://phpseclib.sourceforge.net/x509/guide.html to create a self signed certificate

        $rsa = new RSA();
        $keys = $rsa->createKey($this->keyLength ? $this->keyLength : 4096);
        $privatekey = $keys['privatekey'];
        $publickey = $keys['publickey'];

        $privKey = new RSA();
        $privKey->loadKey($privatekey);

        $pubKey = new RSA();
        $pubKey->loadKey($publickey);
        $pubKey->setPublicKey();

        $dn = $this->createDn();
        $subject = new X509();
        $subject->setDN($dn);
        $subject->setPublicKey($pubKey);

        $issuer = new X509();
        $issuer->setPrivateKey($privKey);
        $issuer->setDN($subject->getDN());

        $x509 = new X509();

        if ($this->startDate) {
            $x509->setStartDate($this->startDate);
        }

        if ($this->expiryDate) {
            $x509->setEndDate($this->expiryDate);
        }

        $result = $x509->sign($issuer, $subject);
        $key = $privKey->getPrivateKey();
        $cert = $x509->saveX509($result);

        return [
            'private_key' => $key,
            'x509' => $cert,
        ];
    }

    private function createDn()
    {
        return "/C={$this->country}/ST={$this->stateOrProvinceName}/L={$this->locality}/O={$this->organization}/OU={$this->organizationUnit}/CN={$this->commonName}";
    }
}
