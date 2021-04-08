<?php
declare(strict_types=1);

namespace Serato\UserProfileSdk\Message;

use Serato\UserProfileSdk\Message\AbstractMessage;

/**
 * A message representing a user's interactive purchase
 *
 * This message conveys data about the product purchased in an interactive manner.
 * At this point, it covers any product that has been invoiced from a user driven order,
 * such as those through the Express Checkout.
 *
 * In this message we are capturing:
 * - User ID
 * - Product ID
 * - Order Date
 * - Order ID
 * - Invoice Number/Invoice ID
 */
class InteractivePurchase extends AbstractMessage
{
    const PRODUCT_IDS = 'product_ids';
    const ORDER_DATE = 'order_date';
    const ORDER_ID = 'order_id';
    const INVOICE_NUMBER = 'invoice_number';
    const PRICE = 'price';

    /**
     * Creates a new message instance
     *
     * @param int   $userId    User ID
     * @param array $params      Array of message parameters
     * @return self
     */
    public static function create(int $userId, array $params = []): self
    {
        return new static($userId, $params);
    }

    /**
     * Set the Product ID of the interactive purchase
     *
     * @param array $productId
     * @return self
     */
    public function setProductIds(array $productIds): self
    {
        $this->setParam(self::PRODUCT_IDS, $productIds);
        return $this;
    }


    /**
     * Get the Product ID from an interactive purchase
     *
     * @return array
     */
    public function getProductIds(): array
    {
        return $this->getParam(self::PRODUCT_IDS);
    }

    /**
     * Set the Order date of the interactive purchase
     *
     * @param string orderDate
     * @return self
     */
    public function setOrderDate(string $orderDate): self
    {
        $this->setParam(self::ORDER_DATE, $orderDate);
        return $this;
    }

    /**
     * Get the Order date of the interactive purchase
     *
     * @return string
     */
    public function getOrderDate(): string
    {
        return $this->getParam(self::ORDER_DATE);
    }

    /**
     * Set the Order ID for the interactive purchase
     *
     * @param int $orderId
     * @return self
     */
    public function setOrderId(int $orderId): self
    {
        $this->setParam(self::ORDER_ID, $orderId);
        return $this;
    }

    /**
     * Get the Order Id of the interactive purchase
     *
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->getParam(self::ORDER_ID);
    }

    /**
     * Set the Invoice ID for the interactive purchase
     *
     * @param int $invoiceNumber
     * @return self
     */
    public function setInvoiceNumber(int $invoiceNumber): self
    {
        $this->setParam(self::INVOICE_NUMBER, $invoiceNumber);
        return $this;
    }

    /**
     * Get the invoice Id of the interactive purchase
     *
     * @return int
     */
    public function getInvoiceNumber(): int
    {
        return $this->getParam(self::INVOICE_NUMBER);
    }

    /**
     * Set the Price for the interactive purchase
     *
     * @param float $price
     * @return self
     */
    public function setPrice(float $price): self
    {
        $this->setParam(self::PRICE, $price);
        return $this;
    }

    /**
     * Get the price of the interactive purchase
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->getParam(self::PRICE);
    }
}
