<?php
namespace Serato\UserProfileSdk\Message;

use Serato\UserProfileSdk\Message\AbstractMessage;

/**
 * A message representing a user starting a trial of a product.
 *
 * The product may be being trialled for the first time, or for successive times
 * in which case the expiry date of the product will be updated.
 */
class TrialProductUpdate extends AbstractMessage
{
    const TRIAL_PRODUCT_PARAM_NAME = 'trial-product';
    const TRIAL_PRODUCT_EXPIRY_PARAM_NAME = 'expiry';

    /**
     * Set the trial Product Type name
     *
     * @param string $productName    Trial Product name
     * @return self
     */
    public function setProductName($productName)
    {
        return $this->setParam(self::TRIAL_PRODUCT_PARAM_NAME, $productName);
    }

    /**
     * Get trial Product Type name
     *
     * @return string
     */
    public function getProductName()
    {
        return $this->getParam(self::TRIAL_PRODUCT_PARAM_NAME);
    }

    /**
     * Set expiry date for trial Product.
     *
     * Date format: `YYYY-MM-DD`
     *
     * @param string $expiryDate    Trial expiry date
     * @return self
     */
    public function setExpiry($expiryDate)
    {
        return $this->setParam(self::TRIAL_PRODUCT_EXPIRY_PARAM_NAME, $expiryDate);
    }

    /**
     * Get expiry date for trial Product.
     *
     * @return string
     */
    public function getExpiry()
    {
        return $this->getParam(self::TRIAL_PRODUCT_EXPIRY_PARAM_NAME);
    }
}
