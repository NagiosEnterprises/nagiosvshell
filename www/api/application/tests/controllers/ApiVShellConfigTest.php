<?php

class ApiVShellConfigTest extends PHPUnit_Framework_TestCase
{

    private $CI;
    private $controller;
    private $result;

    private $keys = array(
        'baseurl',
        'cgicfg',
        'coreurl',
        'lang',
        'objectsfile',
        'statusfile',
        'ttl',
        'updateinterval'
    );

    public function setUp()
    {
        $this->CI = &get_instance();

        $this->controller = new API();
        $this->controller->set_output_type('test');
        $this->controller->vshellconfig();

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

/* End of file application/tests/controllers/ApiVShellConfigTest.php */
