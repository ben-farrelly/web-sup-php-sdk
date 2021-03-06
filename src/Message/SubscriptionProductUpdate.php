<?php
namespace Serato\UserProfileSdk\Message;

use Serato\UserProfileSdk\Message\AbstractMessage;

/**
 * A message representing a user's subscription plan.
 *
 * The message conveys the user starting the plan for the first time, as well as
 * indicating that the expiry date of the plan has changed.
 */
class SubscriptionProductUpdate extends AbstractMessage
{
    const PLAN = 'plan';
    const EXPIRY = 'expiry';

    /**
     * Set the plan name for Subscription
     *
     * @param string    $planName  Subscription plan name
     * @return self
     */
    public function setPlan($planName)
    {
        return $this->setParam(self::PLAN, $planName);
    }

    /**
     * Get the plan name for Subscription
     *
     * @return string
     */
    public function getPlan()
    {
        return $this->getParam(self::PLAN);
    }

    /**
     * Set the expiry date for Subscription
     *
     * Date format: DATE_ATOM
     * Example: 2017-08-15T15:52:01+00:00
     *
     * @param string    $expiryDate    Subscription expiry date
     * @return self
     */
    public function setExpiry($expiryDate)
    {
        return $this->setParam(self::EXPIRY, $expiryDate);
    }

    /**
     * Get the expiry date for Subscription
     *
     * @return string
     */
    public function getExpiry()
    {
        return $this->getParam(self::EXPIRY);
    }

    /**
     * Set expiry timestamp for subscription
     *
     * @param int $timestamp
     *
     *  @return string
     */
    public function setExpiryTimestamp($timestamp)
    {
        return $this->setExpiry(gmdate(DATE_ATOM, $timestamp));
    }
}
