<?php //process_details.php  prep function for host/service status detail page

//expecting a service ID such as: 
//$arg = 'service15';
function process_service_detail($serviceid)
{
	global $NagiosData;
	global $NagiosUser; 
	
	$sd = $NagiosData->get_details_by('service', $serviceid);
	$hostname = $sd['host_name'];
	if(!$NagiosUser->is_authorized_for_service($hostname,$sd['service_description'])) return false; //bail if not authorized 
	
	//make necessary calculations with array elements 
	$duration = calculate_duration($sd['last_state_change']);
	
	$current_state = return_service_state($sd['current_state']);	
	$statetype = return_state_type($sd['state_type']); //calculate state 
	$current_check = $sd['current_attempt'].' of '.$sd['max_attempts'];

	$date_format = 'M d H:i\:s\s Y';
	$last_check = date($date_format, $sd['last_check']);
	$next_check = date($date_format, $sd['next_check']);
	$last_state_change = date($date_format, $sd['last_state_change']);
	
	if($sd['last_notification']==0){$last_notification='Never';} 
	else {$last_notification = date($date_format, $sd['last_notification']);}
	
	if($sd['check_type']==0) {$check_type = 'Active';} else {$check_type='Passive';}	

	$check_latency  = $sd['check_latency'].' seconds';
	$execution_time = $sd['check_execution_time'].' seconds';
	$state_change   = $sd['percent_state_change'].'%';
	
	$membership = check_membership($hostname, $sd['service_description']);
	$state = return_service_state($sd['current_state']);
	
	//service attributes
	$ser_desc = preg_replace('/\ /', '+', $sd['service_description']);    //replacing spaces with pluses for cgi URL 
	//if else statements generate links for Nagios core commands based on status 
	$active_checks = return_enabled($sd['active_checks_enabled']);
	if($active_checks == 'Enabled') 
	{ $cmd_active_checks     = core_function_link('DISABLE_SVC_CHECK', $hostname, $ser_desc); }
	else{ $cmd_active_checks = core_function_link('ENABLE_SVC_CHECK',  $hostname, $ser_desc); }
	
	$passive_checks = return_enabled($sd['passive_checks_enabled']);
	if($passive_checks == 'Enabled')
	{$cmd_passive_checks     = core_function_link('DISABLE_PASSIVE_SVC_CHECKS', $hostname, $ser_desc); }
	else{$cmd_passive_checks = core_function_link('ENABLE_PASSIVE_SVC_CHECKS',  $hostname, $ser_desc); }
	
	$notifications = return_enabled($sd['notifications_enabled']);
	if($notifications == 'Enabled')
	{$cmd_notifications      = core_function_link('DISABLE_SVC_NOTIFICATIONS', $hostname, $ser_desc); }
	else {$cmd_notifications = core_function_link('ENABLE_SVC_NOTIFICATIONS',  $hostname, $ser_desc); }
	
	$flap_detection = return_enabled($sd['flap_detection_enabled']);
	if($flap_detection == 'Enabled')
	{$cmd_flap_detection     = core_function_link('DISABLE_SVC_FLAP_DETECTION', $hostname, $ser_desc); }
	else{$cmd_flap_detection = core_function_link('ENABLE_SVC_FLAP_DETECTION',  $hostname, $ser_desc); }
	
	$process_perf_data = return_enabled($sd['process_performance_data']);
	$obsession = return_enabled($sd['obsess_over_service']);
	if($obsession == 'Enabled')
	{$cmd_obsession     = core_function_link('STOP_OBSESSING_OVER_SVC',  $hostname, $ser_desc); }
	else{$cmd_obsession = core_function_link('START_OBSESSING_OVER_SVC', $hostname, $ser_desc); }
	
	$add_comment = core_function_link('ADD_SVC_COMMENT', $hostname, $ser_desc);
	
	if($sd['problem_has_been_acknowledged'] == 0)
	{
		$cmd_acknowledge = core_function_link('ACKNOWLEDGE_SVC_PROBLEM', $hostname, $ser_desc);
		$ack_title = 'Acknowledge Problem';
	} 
	else
	{
		$cmd_acknowledge = core_function_link('REMOVE_SVC_ACKNOWLEDGEMENT', $hostname, $ser_desc);
		$ack_title = 'Remove Acknowledgement';	
	}  
	
	$cmd_custom_notification = core_function_link('SEND_CUSTOM_SVC_NOTIFICATION', $hostname, $ser_desc);
	$cmd_schedule_downtime   = core_function_link('SCHEDULE_SVC_DOWNTIME',        $hostname, $ser_desc);
	$cmd_schedule_checks     = core_function_link('SCHEDULE_SVC_CHECK',           $hostname, $ser_desc, '&force_check');
	//core commands
	//$cmd_map = CORECGI.'statusmap.cgi?host='.$hostname.'&service='.$ser_desc;
	$core_link = CORECGI.'extinfo.cgi?type=2&host='.$hostname.'&service='.$ser_desc;
	
											
	//service details array -> Use for displaying page 
	$details = array( 
					'Service' => $sd['service_description'],
					'Host'	  => $hostname,
					'Output'  => $sd['plugin_output'],
					'MemberOf' => $membership,
					'CheckCommand' => $sd['check_command'],
					'State' => $current_state,
					'Duration' => $duration,
					'StateType' => $statetype,
					'CurrentCheck' => $current_check,
					'LastCheck' => $last_check,
					'NextCheck' => $next_check,
					'LastStateChange'  => $last_state_change,
					'LastNotification' => $last_notification,
					'CheckType' => $check_type,
					'CheckLatency'  => $check_latency,
					'ExecutionTime' => $execution_time,
					'StateChange'   => $state_change,
					'PerformanceData' => $sd['performance_data'],
					'ActiveChecks'  => $active_checks,
					'PassiveChecks' => $passive_checks,
					'Notifications' => $notifications,
					'FlapDetection' => $flap_detection,
					'ProcessPerformanceData' => $process_perf_data,
					'Obsession'		=> $obsession,
					'AddComment'	=> htmlentities($add_comment),
					'CmdActiveChecks'  => htmlentities($cmd_active_checks),
					'CmdPassiveChecks' => htmlentities($cmd_passive_checks),
					'CmdNotifications' => htmlentities($cmd_notifications),
					'CmdFlapDetection' => htmlentities($cmd_flap_detection),
					'CmdObsession'     => htmlentities($cmd_obsession),
					'CmdAcknowledge'   => htmlentities($cmd_acknowledge),
					'CmdCustomNotification'	=> htmlentities($cmd_custom_notification),
					'CmdScheduleDowntime'	=> htmlentities($cmd_schedule_downtime),
					'CmdScheduleChecks'		=> htmlentities($cmd_schedule_checks),
					'AckTitle'				=> $ack_title,
					'CoreLink'			    => htmlentities($core_link),
					);
								
	return $details;
}

/** expecting host name as $in_hostname
 *
 * processes a host array and sends to the hostdetails.php page
 */
function process_host_detail($in_hostname)
{
	global $NagiosData; 
	global $NagiosUser; 
	$hd = $NagiosData->get_details_by('host', $in_hostname); 
	$hostname = $hd['host_name'];
	if(!$NagiosUser->is_authorized_for_host($hostname)) return false; //bail if not authorized 

	$duration = calculate_duration($hd['last_state_change']);
	$current_state = return_host_state($hd['current_state']);
	$statetype = return_state_type($hd['state_type']); //calculate state 
	$current_check = $hd['current_attempt'].' of '.$hd['max_attempts'];

	$date_format = 'M d H:i\:s\s Y';
	$last_check = date($date_format, $hd['last_check']);
	$next_check = date($date_format, $hd['next_check']);
	$last_state_change = date($date_format, $hd['last_state_change']);
	
	if($hd['last_notification']==0){$last_notification='Never';} 
	else {$last_notification = date($date_format, $hd['last_notification']);}
	
	$check_type = ($hd['check_type'] == 0) ? 'Active' : 'Passive';

	$check_latency  = $hd['check_latency'].' seconds';
	$execution_time = $hd['check_execution_time'].' seconds';
	$state_change   = $hd['percent_state_change'].'%';
	
	$membership = check_membership($hostname);
	$state = return_host_state($hd['current_state']);

	//host attributes 
	//if else statements generate links for Nagios core commands based on status 
	$active_checks = return_enabled($hd['active_checks_enabled']);

	if($active_checks == 'Enabled') 
	{ $cmd_active_checks     = core_function_link('DISABLE_HOST_CHECK', $hostname); }
	else{ $cmd_active_checks = core_function_link('ENABLE_HOST_CHECK',  $hostname); }
	
	$passive_checks = return_enabled($hd['passive_checks_enabled']);
	if($passive_checks == 'Enabled')
	{$cmd_passive_checks     = core_function_link('DISABLE_PASSIVE_HOST_CHECKS', $hostname); }
	else{$cmd_passive_checks = core_function_link('ENABLE_PASSIVE_HOST_CHECKS',  $hostname); }
	
	$notifications = return_enabled($hd['notifications_enabled']);
	if($notifications == 'Enabled')
	{$cmd_notifications      = core_function_link('DISABLE_HOST_NOTIFICATIONS', $hostname); }
	else {$cmd_notifications = core_function_link('ENABLE_HOST_NOTIFICATIONS',  $hostname); }
	
	$flap_detection = return_enabled($hd['flap_detection_enabled']);
	if($flap_detection == 'Enabled')
	{$cmd_flap_detection     = core_function_link('DISABLE_HOST_FLAP_DETECTION', $hostname); }
	else{$cmd_flap_detection = core_function_link('ENABLE_HOST_FLAP_DETECTION',  $hostname); }
	
	$process_perf_data = return_enabled($hd['process_performance_data']);

	$obsession = return_enabled($hd['obsess_over_host']);
	if($obsession == 'Enabled')
	{$cmd_obsession     = core_function_link('STOP_OBSESSING_OVER_HOST',  $hostname); }
	else{$cmd_obsession = core_function_link('START_OBSESSING_OVER_HOST', $hostname); }
	
	$add_comment = core_function_link('ADD_HOST_COMMENT', $hostname);
	
	if($hd['problem_has_been_acknowledged'] == 0)
	{
		$cmd_acknowledge = core_function_link('ACKNOWLEDGE_HOST_PROBLEM', $hostname);
		$ack_title = 'Acknowledge Problem';
	} 
	else
	{
		$cmd_acknowledge = core_function_link('REMOVE_HOST_ACKNOWLEDGEMENT', $hostname);
		$ack_title = 'Remove Acknowledgement';	
	}  
	
	$cmd_custom_notification = core_function_link('SEND_CUSTOM_HOST_NOTIFICATION', $hostname);
	$cmd_schedule_downtime   = core_function_link('SCHEDULE_HOST_DOWNTIME',        $hostname);
	$cmd_schedule_dt_all     = core_function_link('SCHEDULE_HOST_SVC_DOWNTIME',    $hostname);
	$cmd_schedule_checks     = core_function_link('SCHEDULE_HOST_SVC_CHECKS',      $hostname);

	$cmd_map   = CORECGI.'statusmap.cgi?host='.$hostname;
	$core_link = CORECGI.'extinfo.cgi?type=1&host='.$hostname;

	$details = array( 
					'Host'	=> $hostname,
					'MemberOf' => $membership,
					'StatusInformation' => $hd['plugin_output'],
					'State' => $current_state,
					'Duration' => $duration,
					'StateType' => $statetype,
					'CurrentCheck' => $current_check,
					'LastCheck' => $last_check,
					'NextCheck' => $next_check,
					'LastStateChange' => $last_state_change,
					'LastNotification' => $last_notification,
					'CheckType' => $check_type,
					'CheckLatency' => $check_latency,
					'ExecutionTime' => $execution_time,
					'StateChange' => $state_change,
					'PerformanceData' => $hd['performance_data'],
					'ActiveChecks' => $active_checks,
					'PassiveChecks' => $passive_checks,
					'Notifications' => $notifications,
					'FlapDetection' => $flap_detection,
					'ProcessPerformanceData' => $process_perf_data,
					'Obsession'		=> $obsession,
					'AddComment'	=> htmlentities($add_comment),
					'MapHost'		=> htmlentities($cmd_map),
					'CmdActiveChecks'	=> htmlentities($cmd_active_checks),
					'CmdPassiveChecks' => htmlentities($cmd_passive_checks),
					'CmdNotifications' => htmlentities($cmd_notifications),
					'CmdFlapDetection' => htmlentities($cmd_flap_detection),
					'CmdObsession'		=> htmlentities($cmd_obsession),
					'CmdAcknowledge' => htmlentities($cmd_acknowledge),
					'CmdCustomNotification'	=> htmlentities($cmd_custom_notification),
					'CmdScheduleDowntime'	=> htmlentities($cmd_schedule_downtime),
					'CmdScheduleDowntimeAll' => htmlentities($cmd_schedule_dt_all),
					'CmdScheduleChecks'		=> htmlentities($cmd_schedule_checks),
					'AckTitle'				=> $ack_title,
					'CoreLink'				=> htmlentities($core_link),
					);
							
	return $details;
}

function core_function_link($function_name, $hostname, $service ='',$options='')
{
	$core_commands = get_commands();
	$function_num = $core_commands['CMD_'.$function_name];

	$cmd = CORECMD."cmd_typ=$function_num&host=$hostname";
	if (isset($service) && $service != '') {
		$cmd .= "&service=$service";
	}
	if (isset($options) && $options != '') {
		$cmd .= $options;
	}
	return $cmd;
}

?>