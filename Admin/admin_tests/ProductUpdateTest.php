<?php
use PHPUnit\Framework\TestCase;
require_once dirname(__DIR__) . '/admin_functions/editProduct.php';

class ProductUpdateTest extends TestCase
{
    private $mockDatabase;
    private $mockLogger;

    protected function setUp(): void
    {
        $this->mockDatabase = $this->getMockBuilder(Database::class)
            ->onlyMethods(['query', 'prepare'])
            ->getMock();

        $this->mockLogger = $this->getMockBuilder(Admin_Manager_logs::class)->getMock();
    }

    public function testProductUpdateWithPic()
    {
        $this->mockDatabase->expects($this->once())
            ->method('query')
            ->willReturn(true);

        $this->mockLogger->expects($this->once())
            ->method('logProductUpdate');

        // Set up POST data
        $_SERVER["REQUEST_METHOD"] = "POST";
        $_POST["userid"] = 1;
        $_POST["prodID"] = 123;
        $_POST["prodDesc"] = "New Product Description";
        $_POST["prodPrice"] = "25.99";
        $_POST["prodRemainingQuantity"] = "50";
        $_POST["productName"] = "New Product Name";
        $_POST["prodQuantity"] = "10";
        $_POST["TotalQuantity"] = "100";
        $_POST["prodOldPrice"] = "20.99";

        // Mock file upload
        $_FILES['productPic'] = [
            'name' => ['new_product_image.jpg'],
            'tmp_name' => ['path/to/temp/file'],
            'size' => [12345],
            'error' => [0],
        ];
    }

    public function testProductUpdateWithoutPic()
    {
        $this->mockDatabase->expects($this->once())
            ->method('prepare')
            ->willReturn($this->getMockBuilder(stdClass::class)->getMock());

        $this->mockLogger->expects($this->once())
            ->method('logProductUpdate');
    }
}
