<?php

require "Myclass.php";
use PHPUnit\Framework\TestCase;

class Myclasstest extends TestCase {
    public function testValidRegistration()
    {
        // Mock necessary objects (e.g., $conn, $quickemailverification)
        $connMock = $this->createMock(PDO::class);
        $quickemailverificationMock = $this->createMock(QuickEmailVerification::class);

        // Set up the mocked behavior for the database query
        $checkEmailStmtMock = $this->createMock(PDOStatement::class);
        $checkEmailStmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $checkEmailResultMock = $this->createMock(PDOStatement::class);
        $checkEmailResultMock->expects($this->once())
            ->method('num_rows')
            ->willReturn(0);

        $checkEmailStmtMock->expects($this->once())
            ->method('get_result')
            ->willReturn($checkEmailResultMock);

        $connMock->expects($this->once())
            ->method('prepare')
            ->willReturn($checkEmailStmtMock);

        // Set up the mocked behavior for QuickEmailVerification
        $responseMock = (object)['body' => ['result' => 'valid']];
        $quickemailverificationMock->expects($this->once())
            ->method('verify')
            ->willReturn($responseMock);

        // Create an instance of your class with the mocked objects
        $registration = new MyClass($connMock, $quickemailverificationMock);

        // Call the method that you want to test
        $result = $registration->handleRegistration($validUserData);

        // Assertions based on the expected behavior
        $this->assertEquals(['status' => 'success', 'message' => 'Registration successful', 'redirect' => '../customer_html/customer_createAccount.php'], $result);
    }
}


