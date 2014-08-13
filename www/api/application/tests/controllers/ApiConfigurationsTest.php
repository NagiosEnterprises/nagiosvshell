<?php

class ApiConfigurationsTest extends PHPUnit_Framework_TestCase
{

    private $CI;
    private $controller;
    private $result;

    private $keys = array(
        'items',
        'name',
        'objtype'
    );

    public function setUp()
    {
        $this->CI = &get_instance();
        $this->controller = new API();
        $this->controller->set_output_type('test');
    }

    private function init($type = '')
    {
        $this->controller->configurations($type);
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
        $this->assertEquals($count, 8);
    }

    public function testSingleOutputNotEmpty()
    {
        $this->init('timeperiods');
        $not_empty = !empty($this->result);
        $this->assertTrue($not_empty);
    }

    public function testSingleOutputItemKeysMatch()
    {
        $this->init('timeperiods');
        $first_result = (array) reset($this->result);
        $result_keys = array_keys($first_result);
        $different_keys = array_diff($this->keys, $result_keys);
        $this->assertFalse(empty($result_keys));
        $this->assertEmpty($different_keys);
    }

    public function testSingleOutputEmptyWhenConfigurationTypeDoesNotExist()
    {
        $this->init('nonexistant-configuration-type');
        $is_empty = empty($this->result);
        $this->assertTrue($is_empty);
    }
}

/* End of file application/tests/controllers/ApiConfigurationsTest.php */
