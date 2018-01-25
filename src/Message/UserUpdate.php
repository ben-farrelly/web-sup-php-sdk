<?php
namespace Serato\UserProfileSdk\Message;

use Serato\UserProfileSdk\Message\AbstractMessage;

/**
 * A message representing a user updating profile information.
 *
 * Each message can convey the updating of all possible parameters or subset
 * of parameters including individual parameters.
 */
class UserUpdate extends AbstractMessage
{
    const EMAIL_PARAM_NAME = 'email';
    const DAW_PARAM_NAME = 'daw';
    const HAS_DJ_HARDWARE_PARAM_NAME = 'dj_hardware';
    const LANGUAGE_PARAM_NAME = 'language';
    const COUNTRY_PARAM_NAME = 'country';

    /**
     * Set user email address
     *
     * @param string    $email  Email address
     * @return self
     */
    public function setEmail($email)
    {
        return $this->setParam(self::EMAIL_PARAM_NAME, $email);
    }

    /**
     * Get user email address
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getParam(self::EMAIL_PARAM_NAME);
    }

    /**
     * Set user primary DAW option
     *
     * @param string $dawOption
     * @return self
     */
    public function setDaw($dawOption)
    {
        return $this->setParam(self::DAW_PARAM_NAME, $dawOption);
    }

    /**
     * Get user primary DAW option
     *
     * @return string
     */
    public function getDaw()
    {
        return $this->getParam(self::DAW_PARAM_NAME);
    }

    /**
     * Sets whether or not the user has DJ hardware
     *
     * @param bool $hardware
     * @return self
     */
    public function setHasDjHardware($hardware)
    {
        return $this->setParam(self::HAS_DJ_HARDWARE_PARAM_NAME, $hardware);
    }

    /**
     * Returns whether or not the user has DJ hardware
     *
     * @return bool
     */
    public function getHasDjHardware()
    {
        return $this->getParam(self::HAS_DJ_HARDWARE_PARAM_NAME);
    }

    /**
     * Set user language
     *
     * @param string $language
     * @return self
     */
    public function setLanguage($language)
    {
        return $this->setParam(self::LANGUAGE_PARAM_NAME, $language);
    }

    /**
     * Get user language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->getParam(self::LANGUAGE_PARAM_NAME);
    }

    /**
     * Set user country
     *
     * @param string $country
     * @return self
     */
    public function setCountry($country)
    {
        return $this->setParam(self::COUNTRY_PARAM_NAME, $country);
    }

    /**
     * Get user country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->getParam(self::COUNTRY_PARAM_NAME);
    }
}
