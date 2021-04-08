<?php
declare(strict_types=1);

namespace Serato\UserProfileSdk\Test\Message;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Serato\UserProfileSdk\Message\InteractivePurchase;

class InteractivePurchaseTest extends PHPUnitTestCase
{
    public function testSetters0()
    {
        $userId = 123;
        $productIds = [15, 150];
        $orderDate = '2021-02-02T00:00:00+00:00';
        $orderId = 25;
        $invoiceNumber = 'S00076239';
        $price = 50.20;

        $interactivePurchase = InteractivePurchase::create($userId)
                                ->setProductIds($productIds)
                                ->setOrderDate($orderDate)
                                ->setOrderId($orderId)
                                ->setInvoiceNumber($invoiceNumber)
                                ->setPrice($price);
        $this->assertEquals('InteractivePurchase', $interactivePurchase->getType());
        $this->assertEquals($interactivePurchase->getUserId(), $userId);
        $this->assertEquals($interactivePurchase->getProductIds(), $productIds);
        $this->assertEquals($interactivePurchase->getOrderDate(), $orderDate);
        $this->assertEquals($interactivePurchase->getOrderId(), $orderId);
        $this->assertEquals($interactivePurchase->getInvoiceNumber(), $invoiceNumber);
        $this->assertEquals($interactivePurchase->getPrice(), $price);
    }

    public function testSetters1()
    {
        $userId = 124;
        $productIds = [16, 162];
        $orderDate = '2021-03-02T00:00:00+00:00';
        $orderId = 26;
        $invoiceNumber = 'S00077239';
        $price = 51.20;

        $interactivePurchase = InteractivePurchase::create($userId, [
            "product_ids" => $productIds,
            "order_date" => $orderDate,
            "order_id" => $orderId,
            "invoice_number" => $invoiceNumber,
            "price" => $price
        ]);

        $this->assertEquals('InteractivePurchase', $interactivePurchase->getType());
        $this->assertEquals($interactivePurchase->getUserId(), $userId);
        $this->assertEquals($interactivePurchase->getProductIds(), $productIds);
        $this->assertEquals($interactivePurchase->getOrderDate(), $orderDate);
        $this->assertEquals($interactivePurchase->getOrderId(), $orderId);
        $this->assertEquals($interactivePurchase->getInvoiceNumber(), $invoiceNumber);
        $this->assertEquals($interactivePurchase->getPrice(), $price);
    }
}
