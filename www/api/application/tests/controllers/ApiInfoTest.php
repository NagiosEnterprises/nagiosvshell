<?php

class ApiInfoTest extends PHPUnit_Framework_TestCase
{

    private $CI;
    private $controller;
    private $result;

    private $keys = array(
        'created',
        'id',
        'last_update_check',
        'last_version',
        'name',
        'new_version',
        'update_available',
        'version'
    );

    public function setUp()
    {
        $this->CI = &get_instance();

        $this->controller = new API();
        $this->controller->set_output_type('test');
        $this->controller->info();

        $this->result = $this->controller->get_output_data();
    }

    public function testOutputNotEmpty()
    {
        $not_empty = !empty($this->result);
        $this->assertTrue($not_empty);
    }

    public function testOutputKeysMatch()
    {
        $result_keys = array_keys((array) $this->result);
        $different_keys = array_diff($this->keys, $result_keys);
        $this->assertFalse(empty($result_keys));
        $this->assertEmpty($different_keys);
    }
}

/* End of file application/tests/controllers/ApiInfoTest.php */
