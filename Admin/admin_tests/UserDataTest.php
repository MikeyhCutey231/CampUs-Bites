<?php
use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__) . '/admin_functions/UserData.php';

class UserDataTest extends TestCase
{
    private $userData;

    protected function setUp(): void
    {
        $this->userData = new UserData();
    }

    public function testGetAdminAccounts()
    {
        $adminAccounts = $this->userData->getAdminAccounts();
        $this->assertIsArray($adminAccounts);
    }

    public function testGetUserData()
    {
        $userId = 1;
        $userData = $this->userData->getUserData($userId);
        $this->assertIsArray($userData);
    }

    public function testGetBasicSalaryByRole()
    {
        $roleName = 'manager';
        $basicSalary = $this->userData->getBasicSalaryByRole($roleName);
        $this->assertIsNumeric($basicSalary);
    }

    public function testGetDaysWorked()
    {
        $userId = 1;
        $daysWorked = $this->userData->getDaysWorked($userId);
        $this->assertIsInt($daysWorked);
    }

    public function testGetTotalOvertimeHours()
    {
        $userId = 8;
        $totalOvertimeHours = $this->userData->getTotalOvertimeHours($userId);
        $this->assertMatchesRegularExpression('/^\d{2}:\d{2}:\d{2}$/', $totalOvertimeHours);
    }

    public function testGetPositionByRoleCode()
    {
        $roleCode = 'adm_manager';
        $position = $this->userData->getPositionByRoleCode($roleCode);
        $this->assertIsString($position);
    }

    public function testGetEmployeeData()
    {
        $employeeId = 1;
        $employeeData = $this->userData->getEmployeeData($employeeId);
        $this->assertIsArray($employeeData);
    }

    public function testGetEmployeeDataCourier()
    {
        $employeeDataCourier = $this->userData->getEmployeeDataCourier();
        $this->assertIsArray($employeeDataCourier);
    }

    public function testGetEmployeeDataCashier()
    {
        $employeeDataCashier = $this->userData->getEmployeeDataCashier();
        $this->assertIsArray($employeeDataCashier);
    }

    public function testGetEmployeeDataServer()
    {
        $employeeDataServer = $this->userData->getEmployeeDataServer();
        $this->assertIsArray($employeeDataServer);
    }

    public function testGetEmployeeDataManager()
    {
        $employeeDataManager = $this->userData->getEmployeeDataManager();
        $this->assertIsArray($employeeDataManager);
    }

    public function testGetEmployeeDataCook()
    {
        $employeeDataCook = $this->userData->getEmployeeDataCook();
        $this->assertIsArray($employeeDataCook);
    }

    public function testGetEmployeeDataAsstCook()
    {
        $employeeDataAsstCook = $this->userData->getEmployeeDataAsstCook();
        $this->assertIsArray($employeeDataAsstCook);
    }

    public function testGetCustomerData()
    {
        $customerData = $this->userData->getCustomerData();
        $this->assertIsArray($customerData);
    }

    protected function tearDown(): void
    {
        // Add cleanup code if necessary
    }
}
