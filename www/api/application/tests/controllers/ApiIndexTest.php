<?php

class ApiIndexTest extends PHPUnit_Framework_TestCase
{    

    private $CI;
    private $controller;
    private $result;

    public function setUp()
    {
        $this->CI = &get_instance();

        $this->controller = new API();
        $this->controller->set_output_type('test');
        $this->controller->index();

        $this->result = $this->controller->get_output_data();
    }

    public function testOutputKeys()
    {

    }
}

/* End of file application/tests/controllers/ApiIndexTest.php */
