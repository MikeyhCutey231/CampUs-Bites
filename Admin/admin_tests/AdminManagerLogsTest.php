<?php

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__) . '/admin_functions/Admin_Manager_logs.php';

class AdminManagerLogsTest extends TestCase
{
    private $mockDatabase;

    protected function setUp(): void
    {
        $this->mockDatabase = $this->getMockBuilder(Database::class)
            ->addMethods(['query'])
            ->getMock();
    }


    public function testLogProductAddition()
    {
        $userId = 2;
        $productName = 'Test Product';

        $this->mockDatabase->expects($this->once())
            ->method('query')
            ->willReturn(true);

        $logger = new Admin_Manager_logs();
        $logger->setDatabase($this->mockDatabase);
        $logger->logProductAddition($userId, $productName);
    }

    public function testLogProductUpdate()
    {
        $userId = 4;
        $productName = 'Test Product';

        $this->mockDatabase->expects($this->once())
            ->method('query')
            ->willReturn(true);

        $logger = new Admin_Manager_logs();
        $logger->setDatabase($this->mockDatabase);
        $logger->logProductUpdate($userId, $productName);
    }

    // Add more test methods for other log functions...

    public function testLogAction()
    {
        $userId = 4;
        $action = 'Test Action';
        $description = 'Test Description';

        $this->mockDatabase->expects($this->once())
            ->method('query')
            ->willReturn(true);

        $logger = new Admin_Manager_logs();
        $logger->setDatabase($this->mockDatabase);
        $logger->logAction($userId, $action, $description);
    }
}
