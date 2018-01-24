<?php
namespace Serato\UserProfileSdk\Test\Message;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Serato\UserProfileSdk\Message\PermanentLicense;

class PermanentLicenseTest extends PHPUnitTestCase
{
    public function testLicenseImplicitAdd()
    {
        $userId = 123;
        $licenseId = '33';

        $permanentLicense = PermanentLicense::create($userId)
                        ->setLicenseTypeId($licenseId);

        $this->assertEquals($licenseId, $permanentLicense->getLicenseTypeId());
        $this->assertEquals(PermanentLicense::ADD, $permanentLicense->getLicenseAction());
    }

    public function testLicenseExplicitAdd()
    {
        $userId = 123;
        $licenseId = '33';

        $permanentLicense = PermanentLicense::create($userId)
                        ->setLicenseTypeId($licenseId)
                        ->setLicenseAction(PermanentLicense::ADD);

        $this->assertEquals($licenseId, $permanentLicense->getLicenseTypeId());
        $this->assertEquals(PermanentLicense::ADD, $permanentLicense->getLicenseAction());
    }

    public function testLicenseRemove()
    {
        $userId = 123;
        $licenseId = '32';
        $permanentLicense = PermanentLicense::create($userId)
                            ->setLicenseTypeId($licenseId)
                            ->setLicenseAction(PermanentLicense::REMOVE);

        $this->assertEquals($licenseId, $permanentLicense->getLicenseTypeId());
        $this->assertEquals(PermanentLicense::REMOVE, $permanentLicense->getLicenseAction());
    }
}
