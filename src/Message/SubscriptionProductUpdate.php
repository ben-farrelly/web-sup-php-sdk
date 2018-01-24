<?php
namespace Serato\UserProfileSdk\Message;

use Serato\UserProfileSdk\Message\AbstractMessage;

/**
 * User's subscription products.
 * Find information about available
 * options for `plan` and `expiry` at:
 * https://github.com/serato/web-sup-php-app
 */
class SubscriptionProductUpdate extends AbstractMessage
{
    const SUB_PLAN_PARAM_NAME = 'plan';
    const SUB_EXPIRY_PARAM_NAME = 'expiry';

    /**
     * Set the plan name for Subscription
     *
     * @param string    $planName  Subscription plan name
     * @return self
     */
    public function setPlan($planName)
    {
        return $this->setParam(self::SUB_PLAN_PARAM_NAME, $planName);
    }

    /**
     * Get the plan name for Subscription
     *
     * @return string
     */
    public function getPlan()
    {
        return $this->getParam(self::SUB_PLAN_PARAM_NAME);
    }

    /**
     * Set the expiry date for Subscription
     *
     * Date format: `YYYY-MM-DD`
     *
     * @param string    $expiryDate    Subscription expiry date
     * @return self
     */
    public function setExpiry($expiryDate)
    {
        return $this->setParam(self::SUB_EXPIRY_PARAM_NAME, $expiryDate);
    }

    /**
     * Get the expiry date for Subscription
     *
     * @return string
     */
    public function getExpiry()
    {
        return $this->getParam(self::SUB_EXPIRY_PARAM_NAME);
    }
}
