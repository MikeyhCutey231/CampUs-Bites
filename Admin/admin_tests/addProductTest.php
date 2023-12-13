<?php
use PHPUnit\Framework\TestCase;
require_once dirname(__DIR__) . '/admin_functions/addProduct.php';

class addProductTest extends TestCase
{
    public function testProductAdditionSuccess()
    {
        $mockConnection = $this->getMockBuilder(mysqli::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockConnection->expects($this->once())
            ->method('query')
            ->willReturn(true);

        $mockLogger = $this->getMockBuilder(Admin_Manager_logs::class)
            ->getMock();

        // Set up expectations for the logProductAddition method
        $mockLogger->expects($this->once())
            ->method('logProductAddition');

        $_POST['userid'] = 123;
        $_POST['prodName'] = 'TestProduct';

        $_FILES['productPic']['name'][0] = 'test_image.jpg';
        $_FILES['productPic']['tmp_name'][0] = 'path/to/tmp_file';

        // Mock the move_uploaded_file function
        $this->expectsFunction('move_uploaded_file')
            ->willReturn(true);

        // Set up expectations for the move_uploaded_file function
        $this->assertTrue(move_uploaded_file('path/to/tmp_file', '../admin_php/upload/test_image.jpg'));

        // Replace the global connection variable with the mock
        $GLOBALS['conn'] = $mockConnection;

        // Replace the Admin_Manager_logs instance with the mock
        $GLOBALS['logger'] = $mockLogger;

/*        require 'path/to/your/script.php';*/

        // Assert any additional expectations as needed
        $this->assertContains('Added', $GLOBALS['rec_success']);
    }

}

?>