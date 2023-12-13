<?php

use PHPUnit\Framework\TestCase;

class Myclasstest extends TestCase {
    public function testAdd() {
        $myClass = new Myclass();
        $result = $myClass->add(2, 3);
        $this->assertEquals(5, $result);
    }
}
