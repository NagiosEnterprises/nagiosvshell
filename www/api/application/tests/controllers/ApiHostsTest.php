<?php

class ApiHostsTest extends PHPUnit_Framework_TestCase
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
        'host_name',
        'id',
        'is_flapping',
        'last_check',
        'last_event_id',
        'last_hard_state',
        'last_hard_state_change',
        'last_notification',
        'last_problem_id',
        'last_state_change',
        'last_time_down',
        'last_time_unreachable',
        'last_time_up',
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
        'obsess_over_host',
        'passive_checks_enabled',
        'percent_state_change',
        'performance_data',
        'plugin_output',
        'problem_has_been_acknowledged',
        'process_performance_data',
        'retry_interval',
        'scheduled_downtime_depth',
        'should_be_scheduled',
        'state_type'
    );

    public function setUp()
    {
        $this->CI = &get_instance();

        $this->controller = new API();
        $this->controller->set_output_type('test');
        $this->controller->hoststatus();

        $this->result = $this->controller->get_output_data();
    }

    public function testOutputNotEmpty()
    {
        $not_empty = !empty($this->result);
        $this->assertTrue($not_empty);
    }

    public function testOutputExpectedSize()
    {
        $count = count((array) $this->result);
        $this->assertEquals($count, 3);
    }

    public function testOutputItemKeysMatch()
    {
        $first_result = (array) reset($this->result);
        $result_keys = array_keys($first_result);
        $different_keys = array_diff($this->keys, $result_keys);
        $this->assertEmpty($different_keys);
    }
}

/* End of file application/tests/controllers/ApiHostsTest.php */
