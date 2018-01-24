<?php
namespace Serato\UserProfileSdk\Message;

use Serato\UserProfileSdk\Message\AbstractMessage;

/**
 * User's permanent licenses.
 * Find information about available options
 * for `licenseId`
 * https://github.com/serato/web-sup-php-app
 */
class PermanentLicense extends AbstractMessage
{
    const LICENSE_TYPE_ID_PARAM_NAME = 'license-type-id';
    const LICENSE_ACTION_PARAM_NAME = 'license-action';

    const ADD = 'license-action-add';
    const REMOVE = 'license-action-remove';

    /**
     * {@inheritdoc}
     */
    public function __construct($userId, array $params = [])
    {
        parent::__construct($userId, $params);
        if ($this->getLicenseAction() === null) {
            $this->setLicenseAction(self::ADD);
        }
    }

    /**
     * Set the license type id
     *
     * @param int $licenseTypeId
     * @return self
     */
    public function setLicenseTypeId($licenseTypeId)
    {
        return $this->setParam(self::LICENSE_TYPE_ID_PARAM_NAME, $licenseTypeId);
    }

    /**
     * Get the license type id
     *
     * @return int
     */
    public function getLicenseTypeId()
    {
        return $this->getParam(self::LICENSE_TYPE_ID_PARAM_NAME);
    }

    /**
     * Set the license action
     *
     * @param string $action
     * @return self
     */
    public function setLicenseAction($action)
    {
        return $this->setParam(self::LICENSE_ACTION_PARAM_NAME, $action);
    }

    /**
     * Get the license action
     *
     * @return string
     */
    public function getLicenseAction()
    {
        return $this->getParam(self::LICENSE_ACTION_PARAM_NAME);
    }
}
