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
        $productId = 15;
        $orderDate = '2021-02-02T00:00:00+00:00';
        $orderId = 25;
        $invoiceId = 35;
        $price = 50.20;

        $interactivePurchase = InteractivePurchase::create($userId)
                                ->setProductId($productId)
                                ->setOrderDate($orderDate)
                                ->setOrderId($orderId)
                                ->setInvoiceId($invoiceId)
                                ->setPrice($price);
        $this->assertEquals('InteractivePurchase', $interactivePurchase->getType());
        $this->assertEquals($interactivePurchase->getUserId(), $userId);
        $this->assertEquals($interactivePurchase->getProductId(), $productId);
        $this->assertEquals($interactivePurchase->getOrderDate(), $orderDate);
        $this->assertEquals($interactivePurchase->getOrderId(), $orderId);
        $this->assertEquals($interactivePurchase->getInvoiceId(), $invoiceId);
        $this->assertEquals($interactivePurchase->getPrice(), $price);
    }

    public function testSetters1()
    {
        $userId = 124;
        $productId = 16;
        $orderDate = '2021-03-02T00:00:00+00:00';
        $orderId = 26;
        $invoiceId = 36;
        $price = 51.20;

        $interactivePurchase = InteractivePurchase::create($userId, [
            "product_id" => $productId,
            "order_date" => $orderDate,
            "order_id" => $orderId,
            "invoice_id" => $invoiceId,
            "price" => $price
        ]);

        $this->assertEquals('InteractivePurchase', $interactivePurchase->getType());
        $this->assertEquals($interactivePurchase->getUserId(), $userId);
        $this->assertEquals($interactivePurchase->getProductId(), $productId);
        $this->assertEquals($interactivePurchase->getOrderDate(), $orderDate);
        $this->assertEquals($interactivePurchase->getOrderId(), $orderId);
        $this->assertEquals($interactivePurchase->getInvoiceId(), $invoiceId);
        $this->assertEquals($interactivePurchase->getPrice(), $price);
    }
}
