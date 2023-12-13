<?php

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__) . '/admin_functions/user.php';

class UserTest extends TestCase
{
    private $user;

    protected function setUp(): void
    {
        $this->user = new User();
    }

    public function testLoginSuccess()
    {
        $result = $this->user->login('admin', 'P@ssw0rd');
        $this->assertEquals(User::REGISTRATION_SUCCESS, $result);
    }

    public function testLoginEmptyFields()
    {
        $result = $this->user->login('', '');
        $this->assertEquals(User::REGISTRATION_EMPTY_FIELDS, $result);
    }

    public function testIsManager()
    {
        $_SESSION['user_role'] = 'adm_manager';
        $this->assertTrue($this->user->isManager());

        unset($_SESSION['user_role']);
    }

    protected function tearDown(): void
    {

    }
}
