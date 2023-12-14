<?php

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__) . '/functions/loginUser.php';

class LoginUserTest extends TestCase {
    private $loginUser;

    protected function setUp(): void {
        $this->loginUser = new LoginUser();
    }

    public function testUserLoginWithValidCredentials() {
        // Mock the database connection
        $mockDatabase = $this->createMock(mysqli::class);
        $this->loginUser->setConnection($mockDatabase);

        // Mock the result of the database query
        $mockResult = $this->createMock(mysqli_result::class);
        $mockResult->method('fetch_assoc')->willReturn(['U_USER_NAME' => 'mikee', 'U_PASSWORD' => password_hash('P@ssw0rd', PASSWORD_DEFAULT), 'USER_ID' => 9]);

        $mockDatabase->expects($this->once())
            ->method('query')
            ->willReturn($mockResult);

        $realResult = $this->loginUser->userLogin('9', 'P@ssw0rd');
        $result = $realResult->fetch_assoc();

        // Assert that the login was successful
        $this->assertEquals(LoginUser::REGISTRATION_SUCCESS, $result);
    }

    // Add more test cases for other scenarios...

    protected function tearDown(): void {
        // Clean up resources, if any
    }
}
?>
