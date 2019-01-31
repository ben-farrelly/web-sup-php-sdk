<?php
declare(strict_types=1);

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
    const STATUS = 'status';
    const NUMBER_OF_BILLING_CYCLES = 'numberOfBillingCycles';
    const CURRENT_BILLING_CYCLE = 'currentBillingCycle';


    /**
     * Set the number of billing cycles for Subscription
     *
     * @param int   $numberOfBillingCycles  Subscription Number Of Billing Cycles
     * @return self
     */
    public function setNumberOfBillingCycles($numberOfBillingCycles)
    {
        $this->setParam(self::NUMBER_OF_BILLING_CYCLES, $numberOfBillingCycles);
        return $this;
    }

    /**
     * Get the number of billing cycles for Subscription
     *
     * @return int
     */
    public function getNumberOfBillingCycles()
    {
        return $this->getParam(self::NUMBER_OF_BILLING_CYCLES);
    }

    /**
     * Set the current billing cycle for Subscription
     *
     * @param int    $currentBillingCycle  Subscription current billing cycle
     * @return self
     */
    public function setCurrentBillingCycle($currentBillingCycle)
    {
        $this->setParam(self::CURRENT_BILLING_CYCLE, $currentBillingCycle);
        return $this;
    }

    /**
     * Get the current billing cycle for Subscription
     *
     * @return int
     */
    public function getCurrentBillingCycle()
    {
        return $this->getParam(self::CURRENT_BILLING_CYCLE);
    }

    /**
     * Set the status for Subscription
     *
     * @param string    $status  Subscription status
     * @return self
     */
    public function setStatus($status)
    {
        $this->setParam(self::STATUS, $status);
        return $this;
    }

    /**
     * Get the status for Subscription
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->getParam(self::STATUS);
    }

    /**
     * Set the plan name for Subscription
     *
     * @param string    $planName  Subscription plan name
     * @return self
     */
    public function setPlan($planName)
    {
        $this->setParam(self::PLAN, $planName);
        return $this;
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
        $this->setParam(self::EXPIRY, $expiryDate);
        return $this;
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
     * @return self
     */
    public function setExpiryTimestamp($timestamp)
    {
        $this->setExpiry(gmdate(DATE_ATOM, $timestamp));
        return $this;
    }
}
