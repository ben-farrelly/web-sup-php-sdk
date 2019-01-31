<?php
declare(strict_types=1);

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
    const EMAIL = 'email';
    const DAW = 'daw';
    const HAS_DJ_HARDWARE = 'dj_hardware';
    const LANGUAGE = 'language';
    const COUNTRY = 'country';
    const HISTORICAL_MAILCHIMP_SUBSCRIBED = 'historical_mailchimp_subscribed';
    const GLOBAL_CONTACT_ME = 'global_contact_me';
    const IMPLICIT_OPT_OUT = 0;
    const IMPLICIT_OPT_IN = 1;
    const EXPLICIT_OPT_OUT = 2;
    const EXPLICIT_OPT_IN = 3;
    const NO_VALUE_CONTACT_ME = 4;

    /**
     * Set user email address
     *
     * @param string    $email  Email address
     * @return self
     */
    public function setEmail($email)
    {
        $this->setParam(self::EMAIL, $email);
        return $this;
    }

    /**
     * Get user email address
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getParam(self::EMAIL);
    }

    /**
     * Set user primary DAW option
     *
     * @param string $dawOption
     * @return self
     */
    public function setDaw($dawOption)
    {
        $this->setParam(self::DAW, $dawOption);
        return $this;
    }

    /**
     * Get user primary DAW option
     *
     * @return string
     */
    public function getDaw()
    {
        return $this->getParam(self::DAW);
    }

    /**
     * Sets whether or not the user has DJ hardware
     *
     * @param bool $hardware
     * @return self
     */
    public function setHasDjHardware($hardware)
    {
        $this->setParam(self::HAS_DJ_HARDWARE, $hardware);
        return $this;
    }

    /**
     * Returns whether or not the user has DJ hardware
     *
     * @return bool
     */
    public function getHasDjHardware()
    {
        return $this->getParam(self::HAS_DJ_HARDWARE);
    }

    /**
     * Set user language
     *
     * @param string $language
     * @return self
     */
    public function setLanguage($language)
    {
        $this->setParam(self::LANGUAGE, $language);
        return $this;
    }

    /**
     * Get user language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->getParam(self::LANGUAGE);
    }

    /**
     * Set user country
     *
     * @param string $country
     * @return self
     */
    public function setCountry($country)
    {
        $this->setParam(self::COUNTRY, $country);
        return $this;
    }

    /**
     * Get user country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->getParam(self::COUNTRY);
    }

    /**
     * Set whether or not the user is subscribed to the historical MailChimp list
     *
     * @param bool $subscribed
     * @return self
     */
    public function setHistoricalMailchimpSubscribed($subscribed)
    {
        $this->setParam(self::HISTORICAL_MAILCHIMP_SUBSCRIBED, $subscribed);
        return $this;
    }

    /**
     * Return whether or not the user is subscribed to the historical MailChimp list
     *
     * @return bool
     */
    public function getHistoricalMailchimpSubscribed()
    {
        return $this->getParam(self::HISTORICAL_MAILCHIMP_SUBSCRIBED);
    }

    /**
     * Set notification setting
     *
     * @param int $globalContactMe This value should be one of:
     *      slef::IMPLICIT_OPT_OUT
     *      slef::IMPLICIT_OPT_IN
     *      slef::EXPLICIT_OPT_OUT
     *      slef::EXPLICIT_OPT_IN
     *      self::NO_VALUE_CONTACT_ME
     * @return self
     */
    public function setGlobalContactMe($globalContactMe)
    {
        $this->setParam(self::GLOBAL_CONTACT_ME, $globalContactMe);
        return $this;
    }

    /**
     * Get notification setting
     *
     * @return int
     */
    public function getGlobalContactMe()
    {
        return $this->getParam(self::GLOBAL_CONTACT_ME);
    }
}
