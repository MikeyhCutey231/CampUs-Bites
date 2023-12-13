<?php

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__) . '/admin_functions/ProductData.php';

class ProductDataTest extends TestCase
{
    private $productData;

    protected function setUp(): void
    {
        // Assuming you have a testing database, configure it in the constructor
        $this->productData = new ProductData();
    }

    public function testGetProductData()
    {
        $result = $this->productData->getProductData();

        $this->assertIsArray($result);

        $this->assertArrayHasKey('prod_id', $result[0]);
       $this->assertArrayHasKey('prodName', $result[0]);
       $this->assertArrayHasKey('prod_pic', $result[0]);
       $this->assertArrayHasKey('prodQuantity', $result[0]);
       $this->assertArrayHasKey('prodStatus', $result[0]);
       $this->assertArrayHasKey('prod_sold', $result[0]);
       $this->assertArrayHasKey('prod_remaining', $result[0]);
       $this->assertArrayHasKey('prod_price', $result[0]);
       $this->assertArrayHasKey('prodDesc', $result[0]);
       $this->assertArrayHasKey('prodCategory', $result[0]);
       $this->assertArrayHasKey('prodDateCreated', $result[0]);
       $this->assertArrayHasKey('totalSales', $result[0]);
    }

    public function testGetSalesReport()
    {
         $result = $this->productData->getSalesReport();
         $this->assertIsArray($result);

    }

    protected function tearDown(): void
    {
    }
}

