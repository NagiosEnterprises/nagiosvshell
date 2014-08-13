<?php

class ApiOverviewTest extends PHPUnit_Framework_TestCase
{

    private $CI;
    private $controller;
    private $result;

    private $keys = array(
        'active_service_checks',
        'event_handlers',
        'flap_detection',
        'hostlink',
        'hostsAcHtml',
        'hostsAcknowledgedTotal',
        'hostsActiveChecksDisabled',
        'hostsDownAcknowledged',
        'hostsDownDisabled',
        'hostsDownScheduled',
        'hostsDownTotal',
        'hostsDownUnhandled',
        'hostsEhHtml',
        'hostsEventHandlerDisabled',
        'hostsFdHtml',
        'hostsFlapHtml',
        'hostsFlapping',
        'hostsFlappingDisabled',
        'hostsNotificationsDisabled',
        'hostsNtfHtml',
        'hostsPassiveChecksDisabled',
        'hostsPcHtml',
        'hostsPending',
        'hostsPendingDisabled',
        'hostsProblemsTotal',
        'hostsTotal',
        'hostsUnhandledTotal',
        'hostsUnreachableAcknowledged',
        'hostsUnreachableDisabled',
        'hostsUnreachableScheduled',
        'hostsUnreachableTotal',
        'hostsUnreachableUnhandled',
        'hostsUpDisabled',
        'hostsUpTotal',
        'last_command_check',
        'lastcmd',
        'notifications',
        'passive_service_checks',
        'servicesAcHtml',
        'servicesAcknowledgedTotal',
        'servicesActiveChecksDisabled',
        'servicesCriticalAcknowledged',
        'servicesCriticalDisabled',
        'servicesCriticalHostProblem',
        'servicesCriticalScheduled',
        'servicesCriticalTotal',
        'servicesCriticalUnhandled',
        'servicesEhHtml',
        'servicesEventHandlerDisabled',
        'servicesFdHtml',
        'servicesFlapHtml',
        'servicesFlapping',
        'servicesFlappingDisabled',
        'servicesNotificationsDisabled',
        'servicesNtfHtml',
        'servicesOkDisabled',
        'servicesOkTotal',
        'servicesPassiveChecksDisabled',
        'servicesPcHtml',
        'servicesPending',
        'servicesPendingDisabled',
        'servicesPendingTotal',
        'servicesProblemsTotal',
        'servicesTotal',
        'servicesTotalDisabled',
        'servicesUnhandledTotal',
        'servicesUnknownAcknowledged',
        'servicesUnknownDisabled',
        'servicesUnknownHostProblem',
        'servicesUnknownScheduled',
        'servicesUnknownTotal',
        'servicesUnknownUnhandled',
        'servicesWarningAcknowledged',
        'servicesWarningDisabled',
        'servicesWarningHostProblem',
        'servicesWarningScheduled',
        'servicesWarningTotal',
        'servicesWarningUnhandled',
        'servlink',
        'version'
    );

    public function setUp()
    {
        $this->CI = &get_instance();

        $this->controller = new API();
        $this->controller->set_output_type('test');
        $this->controller->tacticaloverview();

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

/* End of file application/tests/controllers/ApiOverviewTest.php */
