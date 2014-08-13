<?php

class ApiServicesTest extends PHPUnit_Framework_TestCase
{

    private $CI;
    private $controller;
    private $result;

    private $keys = array(
        'acknowledgement_type',
        'active_checks_enabled',
        'check_command',
        'check_execution_time',
        'check_interval',
        'check_latency',
        'check_options',
        'check_period',
        'check_type',
        'current_attempt',
        'current_event_id',
        'current_notification_id',
        'current_notification_number',
        'current_problem_id',
        'current_state',
        'event_handler',
        'event_handler_enabled',
        'failure_prediction_enabled',
        'flap_detection_enabled',
        'has_been_checked',
        'host_current_state',
        'host_id',
        'host_is_flapping',
        'host_name',
        'host_problem_has_been_acknowledged',
        'host_scheduled_downtime_depth',
        'id',
        'is_flapping',
        'last_check',
        'last_event_id',
        'last_hard_state',
        'last_hard_state_change',
        'last_notification',
        'last_problem_id',
        'last_state_change',
        'last_time_critical',
        'last_time_ok',
        'last_time_unknown',
        'last_time_warning',
        'last_update',
        'long_plugin_output',
        'max_attempts',
        'modified_attributes',
        'name',
        'next_check',
        'next_notification',
        'no_more_notifications',
        'notification_period',
        'notifications_enabled',
        'obsess_over_service',
        'passive_checks_enabled',
        'percent_state_change',
        'performance_data',
        'plugin_output',
        'problem_has_been_acknowledged',
        'process_performance_data',
        'retry_interval',
        'scheduled_downtime_depth',
        'service_description',
        'should_be_scheduled',
        'state_type'
    );

    public function setUp()
    {
        $this->CI = &get_instance();
        $this->controller = new API();
        $this->controller->set_output_type('test');
    }

    private function init($host_name = '', $service_name = '')
    {
        $this->controller->servicestatus($host_name, $service_name);
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
        $this->assertEquals($count, 7);
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

    public function testSingleHostOutputNotEmpty()
    {
        $this->init('up.example.com');
        $not_empty = !empty($this->result);
        $this->assertTrue($not_empty);
    }

    public function testSingleHostOutputEmptyWhenHostDoesNotExist()
    {
        $this->init('nonexistant.host.name');
        $is_empty = empty($this->result);
        $this->assertTrue($is_empty);
    }

    public function testSingleHostOutputExpectedSize()
    {
        $this->init('up.example.com');
        $count = count((array) $this->result);
        $this->assertEquals($count, 3);
    }

    public function testSingleServiceOutputNotEmpty()
    {
        $this->init('up.example.com', 'SSH');
        $not_empty = !empty($this->result);
        $this->assertTrue($not_empty);
    }

    public function testSingleServiceOutputEmptyWhenServiceDoesNotExist()
    {
        $this->init('up.example.com', 'Non existant service');
        $is_empty = empty($this->result);
        $this->assertTrue($is_empty);
    }
}

/* End of file application/tests/controllers/ApiServicesTest.php */
