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
        $orderDate = '2021-02-02T00:00:00+00:00';
        $orderId = 25;
        $products = [
            15  => 50.20,
            150 => 500.20
        ];

        $interactivePurchase = InteractivePurchase::create($userId)
                                ->setProducts($products)
                                ->setOrderDate($orderDate)
                                ->setOrderId($orderId);
        $this->assertEquals('InteractivePurchase', $interactivePurchase->getType());
        $this->assertEquals($interactivePurchase->getUserId(), $userId);
        $this->assertEquals($interactivePurchase->getProducts(), $products);
        $this->assertEquals($interactivePurchase->getOrderDate(), $orderDate);
        $this->assertEquals($interactivePurchase->getOrderId(), $orderId);
    }

    public function testSetters1()
    {
        $userId = 124;
        $orderDate = '2021-03-02T00:00:00+00:00';
        $orderId = 26;
        $products = [
            16  => 51.20,
            162 => 510.20
        ];

        $interactivePurchase = InteractivePurchase::create($userId, [
            "products" => $products,
            "order_date" => $orderDate,
            "order_id" => $orderId
        ]);

        $this->assertEquals('InteractivePurchase', $interactivePurchase->getType());
        $this->assertEquals($interactivePurchase->getUserId(), $userId);
        $this->assertEquals($interactivePurchase->getProducts(), $products);
        $this->assertEquals($interactivePurchase->getOrderDate(), $orderDate);
        $this->assertEquals($interactivePurchase->getOrderId(), $orderId);
    }
}
