<?php
declare(strict_types=1);

namespace Serato\UserProfileSdk\Message;

use Serato\UserProfileSdk\Message\AbstractMessage;

/**
 * A message representing a user adding or removing a time limited license.
 */
class TimeLimitedLicense extends AbstractMessage
{
    const LICENSE_TYPE_ID = 'license-type-id';
    const LICENSE_ID = 'license-id';
    const LICENSE_ACTION = 'license-action';
    const ADD = 'license-action-add';
    const REMOVE = 'license-action-remove';
    const EXPIRY = 'expiry';

    /**
     * {@inheritdoc}
     */
    public function __construct($userId, array $params = [])
    {
        parent::__construct($userId, $params);
        $this->setLicenseAction(self::ADD);
    }

    /**
     * Set the license type id
     *
     * @param int $licenseTypeId
     * @return self
     */
    public function setLicenseTypeId($licenseTypeId)
    {
        $this->setParam(self::LICENSE_TYPE_ID, $licenseTypeId);
        return $this;
    }

    /**
     * Get the license type id
     *
     * @return int
     */
    public function getLicenseTypeId()
    {
        return $this->getParam(self::LICENSE_TYPE_ID);
    }

    /**
     * Set the license id
     *
     * @param string $licenseId
     * @return self
     */
    public function setLicenseId($licenseId)
    {
        $this->setParam(self::LICENSE_ID, $licenseId);
        return $this;
    }

    /**
     * Get the license id
     *
     * @return string
     */
    public function getLicenseId()
    {
        return $this->getParam(self::LICENSE_ID);
    }

    /**
     * Set the license action
     *
     * @param string $action
     * @return self
     */
    public function setLicenseAction($action)
    {
        $this->setParam(self::LICENSE_ACTION, $action);
        return $this;
    }

    /**
     * Get the license action
     *
     * @return string
     */
    public function getLicenseAction()
    {
        return $this->getParam(self::LICENSE_ACTION);
    }

    /**
     * Set the expiry date for Time Limited License
     *
     * Date format: DATE_ATOM
     * Example: 2017-08-15T15:52:01+00:00
     *
     * @param string    $expiryDate    Time Limited License expiry date
     * @return self
     */
    public function setExpiry($expiryDate)
    {
        $this->setParam(self::EXPIRY, $expiryDate);
        return $this;
    }

    /**
     * Get the expiry date for Time Limited License
     *
     * @return string
     */
    public function getExpiry()
    {
        return $this->getParam(self::EXPIRY);
    }

    /**
     * Set expiry timestamp for Time Limited License
     *
     * @param int $timestamp
     * @return self
     */
    public function setExpiryTimestamp($timestamp)
    {
        $this->setExpiry(gmdate(DATE_ATOM, $timestamp));
        return $this;
    }
}
