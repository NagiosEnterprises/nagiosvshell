<?php

class ApiServiceGroupsTest extends PHPUnit_Framework_TestCase
{

    private $CI;
    private $controller;
    private $result;

    private $keys = array(
        'ServiceStatusCollection',
        'alias',
        'id',
        'members',
        'name',
        'servicegroup_name',
        'servicesCritical',
        'servicesOK',
        'servicesPending',
        'servicesUnknown',
        'servicesWarning'
    );

    public function setUp()
    {
        $this->CI = &get_instance();
        $this->controller = new API();
        $this->controller->set_output_type('test');
    }

    private function init($servicegroup_name = '')
    {
        $this->controller->servicegroupstatus($servicegroup_name);
        $this->result = $this->controller->get_output_data();
    }

    public function testOutputNotEmpty()
    {
        $this->init();
        $not_empty = !empty($this->result);
        $this->assertTrue($not_empty);
    }

    public function testOutputExpectedSize()
    {
        $this->init();
        $count = count((array) $this->result);
        $this->assertEquals($count, 2);
    }

    public function testOutputItemKeysMatch()
    {
        $this->init();
        $first_result = (array) reset($this->result);
        $result_keys = array_keys($first_result);
        $different_keys = array_diff($this->keys, $result_keys);
        $this->assertFalse(empty($result_keys));
        $this->assertEmpty($different_keys);
    }

    public function testSingleOutputNotEmpty()
    {
        $this->init('websites');
        $not_empty = !empty($this->result);
        $this->assertTrue($not_empty);
    }

    public function testSingleOutputEmptyWhenServiceGroupDoesNotExist()
    {
        $this->init('nonexistant-service-group');
        $is_empty = empty($this->result);
        $this->assertTrue($is_empty);
    }
}

/* End of file application/tests/controllers/ApiServiceGroupsTest.php */
