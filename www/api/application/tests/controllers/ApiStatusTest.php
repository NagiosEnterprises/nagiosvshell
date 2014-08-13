<?php

class ApiStatusTest extends PHPUnit_Framework_TestCase
{

    private $CI;
    private $controller;
    private $result;

    private $keys = array(
        'active_host_checks_enabled',
        'active_ondemand_host_check_stats',
        'active_ondemand_service_check_stats',
        'active_scheduled_host_check_stats',
        'active_scheduled_service_check_stats',
        'active_service_checks_enabled',
        'cached_host_check_stats',
        'cached_service_check_stats',
        'check_host_freshness',
        'check_service_freshness',
        'daemon_mode',
        'enable_event_handlers',
        'enable_failure_prediction',
        'enable_flap_detection',
        'enable_notifications',
        'external_command_stats',
        'global_host_event_handler',
        'global_service_event_handler',
        'high_external_command_buffer_slots',
        'id',
        'last_command_check',
        'last_log_rotation',
        'modified_host_attributes',
        'modified_service_attributes',
        'nagios_pid',
        'name',
        'next_comment_id',
        'next_downtime_id',
        'next_event_id',
        'next_notification_id',
        'next_problem_id',
        'obsess_over_hosts',
        'obsess_over_services',
        'parallel_host_check_stats',
        'passive_host_check_stats',
        'passive_host_checks_enabled',
        'passive_service_check_stats',
        'passive_service_checks_enabled',
        'process_performance_data',
        'program_start',
        'serial_host_check_stats',
        'total_external_command_buffer_slots',
        'used_external_command_buffer_slots'
    );

    public function setUp()
    {
        $this->CI = &get_instance();

        $this->controller = new API();
        $this->controller->set_output_type('test');
        $this->controller->programstatus();

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

/* End of file application/tests/controllers/ApiStatusTest.php */
