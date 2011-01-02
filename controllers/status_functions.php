<?php //this script defines functions for sorting arrays

// Nagios V-Shell
// Copyright (c) 2010 Nagios Enterprises, LLC.
// Written by Mike Guthrie <mguthrie@nagios.com>
//
// LICENSE:
//
// This work is made available to you under the terms of Version 2 of
// the GNU General Public License. A copy of that license should have
// been provided with this software, but in any event can be obtained
// from http://www.fsf.org.
// 
// This work is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
// General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
// 02110-1301 or visit their web page on the internet at
// http://www.fsf.org.
//
//
// CONTRIBUTION POLICY:
//
// (The following paragraph is not intended to limit the rights granted
// to you to modify and distribute this software under the terms of
// licenses that may apply to the software.)
//
// Contributions to this software are subject to your understanding and acceptance of
// the terms and conditions of the Nagios Contributor Agreement, which can be found 
// online at:
//
// http://www.nagios.com/legal/contributoragreement/
//
//
// DISCLAIMER:
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
// INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A 
// PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT 
// HOLDERS BE LIABLE FOR ANY CLAIM FOR DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
// OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE 
// GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, STRICT LIABILITY, TORT (INCLUDING 
// NEGLIGENCE OR OTHERWISE) OR OTHER ACTION, ARISING FROM, OUT OF OR IN CONNECTION 
// WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.



//////////////////////////////////////////
//
//FUNCTION: get_state_of()
//
//DESC: return array of status codes for hosts OR services 
//
//ARGS: $type = 'hosts' or 'services', $array = array of hosts or services 
//
//USAGE: $stats = get_state_of('services');
//        print $stats['OK']." OK Services";
//
//used for tactical overview tables 
//
function get_state_of($type, $array=NULL) //create host or service arrays by status 
{
	global $NagiosData;

	if($type == 'services')
	{
		$state_counts = array('OK'=>0, 'WARNING'=>0, 'CRITICAL'=>0, 'UNKNOWN'=>0);	
		if (is_null($array)) {
			$array = $NagiosData->getProperty('services');
		}
	}
	elseif($type == 'hosts')
	{
		$state_counts = array('UP'=>0, 'DOWN'=>0, 'UNREACHABLE'=>0, 'UNKNOWN'=>0);
		if (is_null($array)) {
			$array = $NagiosData->getProperty('hosts');
		}
	}
	else {
		// XXX handle better
		die("Unknown type for this function");
	}

	foreach($array as $a)
	{
		$state_counts[$a['current_state']]++;
	}
	
	return $state_counts;					  
}
//testing... 
//$stats = get_state_of($hosts, 'host');
//$stats = get_state_of($services, 'service');
//print $stats['OK']." OK Services";



/////////////////////////////////////////////////
//
//FUNCTION: get_hosts_by_state($state)
//
//DESC: returns host array by status code 
//
//ARG: $state = expecting a status state
//					[hosts: UP, DOWN, UNREACHABLE, UNKNOWN] 
//					[services: OK, WARNING, CRITICAL, UNKNOWN]
//		 $array = expecting array with status details
//
//USAGE: $up_hosts = get_host_by_state('UP');
//
function get_hosts_by_state($state, $host_data)
{
	return get_by_state($state, $host_data);
}



/////////////////////////////////////////////////
//
//FUNCTION: get_service_by_state($state)
//
//DESC: returns host array by status code 
//
//ARG: $state = expecting a status code[ 0, 1, 2, 3]
//
//USAGE: $s = get_services_by_state(2); 
//
function get_services_by_state($state, $service_data)
{
	return get_by_state($state, $service_data);
}

function get_by_state($state, $data)
{

	return array_filter($data, create_function('$d', 'return $d[\'current_state\'] == \''.$state.'\';'));

}

////////////////////////////////////////////
//
//FUNCTION: check_boolean 
//
//DESC:
//
//$type - expecting 'host' 'service' or 'program'
//$arg - expecting config def like 'is_flapping' or 'notifications_enabled' 
//$int - checking value of $arg, expecting (integer) 1 or 0
//
function check_boolean($type,$arg,$int) 
//arg is a host/service detail like "notifications_enabled"
{
	$retval = false;

	global $NagiosData;
	$details = $NagiosData->grab_details($type); //grab full status details for host/service
	$count = 0;

	foreach($details as $object)
	{
		if($object[$arg] == $int)
		{
			$count++;
		}
	}
	if($count>0)
	{
		$retval = $count;
	}

	return $retval;
}
////////////////////////////////////////////////
//
//RETURNS: integer of hosts or services with 'current_state' matching $state 
function count_by_state($state, $array)
{
	$count = 0;
	foreach($array as $a)
	{
		if($a['current_state'] == $state)
		{
			$count++;
		}
	}
	return $count;
}

///////////////////////////////////////////////////////
//expecting a status code, 0-4 
//returns status as a string 
function return_service_state($arg)
{
	return index_or_default($arg, array('OK', 'WARNING', 'CRITICAL'), 'UNKNOWN');
}


///////////////////////////////////////////////////////
//expecting a status code, 0-4 
//returns status as a string 
function return_host_state($arg)
{
	return index_or_default($arg, array('UP', 'DOWN', 'UNREACHABLE'), 'UNKNOWN');
}


///////////////////////////////////////////////////////
//expecting a status code, 0-1 
//returns status as a string 
function return_state_type($arg)
{
	return index_or_default($arg, array('Soft', 'Hard'), 'Unknown');
}

///////////////////////////////////////////////////////
//expecting a status code, 0-1 
//returns status as a string 
function return_enabled($arg)
{
	return index_or_default($arg, array('Disabled', 'Enabled'), 'Unknown');
}

function index_or_default($arg, $vals, $default)
{
	return (isset($vals[$arg]) ? $vals[$arg] : $default);
}

///////////////////////////////////
//Expecting(requires): $hostname
//				(optional) $servicename = 'service_description'  
//returns count of comments for a host or service 
//
function check_comments($hostname='',$servicename='')
{
	global $NagiosData;
	$hosts = $NagiosData->getProperty('hosts');

	$hostname = trim($hostname);

	$count = 0;

	if ($hostname != '') {
		if (isset($hosts[$hostname]) && isset($hosts[$hostname]['comments'])) {

			$servicename = trim($servicename);
			if ($servicename != '') {
				foreach($hosts[$hostname]['comments'] as $comment) {
					if ($comment['service_description'] == $servicename) {
						$count++;
					}
				}
			} else {
				$count = count($hosts[$hostname]['comments']);
			}
		}
	}

	return ($count == 0 ? false : $count);
}

function get_host_downtime($hostname)
{
	global $NagiosData;
	$host = $NagiosData->get_details_by('host', $hostname);

	return $host['scheduled_downtime_depth']; //returns integer 

}

//expecting a service ID such as: 
//$arg = 'service15';
function process_service_detail($serviceid)
{
	global $NagiosData;
	$sd = $NagiosData->get_details_by('service', $serviceid);
	$hostname = $sd['host_name'];
	
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
		$ack_title = 'Ackowledge Problem';
	} 
	else
	{
		$cmd_acknowledge = core_function_link('REMOVE_SVC_ACKNOWLEDGEMENT', $hostname, $ser_desc);
		$ack_title = 'Remove Ackowledgement';	
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
	$hd = $NagiosData->get_details_by('host', $in_hostname);
	$hostname = $hd['host_name'];

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
		$ack_title = 'Ackowledge Problem';
	} 
	else
	{
		$cmd_acknowledge = core_function_link('REMOVE_HOST_ACKNOWLEDGEMENT', $hostname);
		$ack_title = 'Remove Ackowledgement';	
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

/** Command mapping from Nagios core's common.h
 */
function get_commands()
{
	$core_commands = array(
		'CMD_NONE' => 0,

		'CMD_ADD_HOST_COMMENT' => 1,
		'CMD_DEL_HOST_COMMENT' => 2,

		'CMD_ADD_SVC_COMMENT' => 3,
		'CMD_DEL_SVC_COMMENT' => 4,

		'CMD_ENABLE_SVC_CHECK' => 5,
		'CMD_DISABLE_SVC_CHECK' => 6,

		'CMD_SCHEDULE_SVC_CHECK' => 7,

		'CMD_DELAY_SVC_NOTIFICATION' => 9,

		'CMD_DELAY_HOST_NOTIFICATION' => 10,

		'CMD_DISABLE_NOTIFICATIONS' => 11,
		'CMD_ENABLE_NOTIFICATIONS' => 12,

		'CMD_RESTART_PROCESS' => 13,
		'CMD_SHUTDOWN_PROCESS' => 14,

		'CMD_ENABLE_HOST_SVC_CHECKS' => 15,
		'CMD_DISABLE_HOST_SVC_CHECKS' => 16,

		'CMD_SCHEDULE_HOST_SVC_CHECKS' => 17,

		'CMD_DELAY_HOST_SVC_NOTIFICATIONS' => 19,  /* currently unimplemented */

		'CMD_DEL_ALL_HOST_COMMENTS' => 20,
		'CMD_DEL_ALL_SVC_COMMENTS' => 21,

		'CMD_ENABLE_SVC_NOTIFICATIONS' => 22,
		'CMD_DISABLE_SVC_NOTIFICATIONS' => 23,
		'CMD_ENABLE_HOST_NOTIFICATIONS' => 24,
		'CMD_DISABLE_HOST_NOTIFICATIONS' => 25,
		'CMD_ENABLE_ALL_NOTIFICATIONS_BEYOND_HOST' => 26,
		'CMD_DISABLE_ALL_NOTIFICATIONS_BEYOND_HOST' => 27,
		'CMD_ENABLE_HOST_SVC_NOTIFICATIONS' => 28,
		'CMD_DISABLE_HOST_SVC_NOTIFICATIONS' => 29,

		'CMD_PROCESS_SERVICE_CHECK_RESULT' => 30,

		'CMD_SAVE_STATE_INFORMATION' => 31,
		'CMD_READ_STATE_INFORMATION' => 32,

		'CMD_ACKNOWLEDGE_HOST_PROBLEM' => 33,
		'CMD_ACKNOWLEDGE_SVC_PROBLEM' => 34,

		'CMD_START_EXECUTING_SVC_CHECKS' => 35,
		'CMD_STOP_EXECUTING_SVC_CHECKS' => 36,

		'CMD_START_ACCEPTING_PASSIVE_SVC_CHECKS' => 37,
		'CMD_STOP_ACCEPTING_PASSIVE_SVC_CHECKS' => 38,

		'CMD_ENABLE_PASSIVE_SVC_CHECKS' => 39,
		'CMD_DISABLE_PASSIVE_SVC_CHECKS' => 40,

		'CMD_ENABLE_EVENT_HANDLERS' => 41,
		'CMD_DISABLE_EVENT_HANDLERS' => 42,

		'CMD_ENABLE_HOST_EVENT_HANDLER' => 43,
		'CMD_DISABLE_HOST_EVENT_HANDLER' => 44,

		'CMD_ENABLE_SVC_EVENT_HANDLER' => 45,
		'CMD_DISABLE_SVC_EVENT_HANDLER' => 46,

		'CMD_ENABLE_HOST_CHECK' => 47,
		'CMD_DISABLE_HOST_CHECK' => 48,

		'CMD_START_OBSESSING_OVER_SVC_CHECKS' => 49,
		'CMD_STOP_OBSESSING_OVER_SVC_CHECKS' => 50,

		'CMD_REMOVE_HOST_ACKNOWLEDGEMENT' => 51,
		'CMD_REMOVE_SVC_ACKNOWLEDGEMENT' => 52,

		'CMD_SCHEDULE_FORCED_HOST_SVC_CHECKS' => 53,
		'CMD_SCHEDULE_FORCED_SVC_CHECK' => 54,

		'CMD_SCHEDULE_HOST_DOWNTIME' => 55,
		'CMD_SCHEDULE_SVC_DOWNTIME' => 56,

		'CMD_ENABLE_HOST_FLAP_DETECTION' => 57,
		'CMD_DISABLE_HOST_FLAP_DETECTION' => 58,

		'CMD_ENABLE_SVC_FLAP_DETECTION' => 59,
		'CMD_DISABLE_SVC_FLAP_DETECTION' => 60,

		'CMD_ENABLE_FLAP_DETECTION' => 61,
		'CMD_DISABLE_FLAP_DETECTION' => 62,

		'CMD_ENABLE_HOSTGROUP_SVC_NOTIFICATIONS' => 63,
		'CMD_DISABLE_HOSTGROUP_SVC_NOTIFICATIONS' => 64,

		'CMD_ENABLE_HOSTGROUP_HOST_NOTIFICATIONS' => 65,
		'CMD_DISABLE_HOSTGROUP_HOST_NOTIFICATIONS' => 66,

		'CMD_ENABLE_HOSTGROUP_SVC_CHECKS' => 67,
		'CMD_DISABLE_HOSTGROUP_SVC_CHECKS' => 68,

		'CMD_CANCEL_HOST_DOWNTIME' => 69, /* not internally implemented */
		'CMD_CANCEL_SVC_DOWNTIME' => 70, /* not internally implemented */

		'CMD_CANCEL_ACTIVE_HOST_DOWNTIME' => 71, /* old - no longer used */
		'CMD_CANCEL_PENDING_HOST_DOWNTIME' => 72, /* old - no longer used */

		'CMD_CANCEL_ACTIVE_SVC_DOWNTIME' => 73, /* old - no longer used */
		'CMD_CANCEL_PENDING_SVC_DOWNTIME' => 74, /* old - no longer used */

		'CMD_CANCEL_ACTIVE_HOST_SVC_DOWNTIME' => 75, /* unimplemented */
		'CMD_CANCEL_PENDING_HOST_SVC_DOWNTIME' => 76, /* unimplemented */

		'CMD_FLUSH_PENDING_COMMANDS' => 77,

		'CMD_DEL_HOST_DOWNTIME' => 78,
		'CMD_DEL_SVC_DOWNTIME' => 79,

		'CMD_ENABLE_FAILURE_PREDICTION' => 80,
		'CMD_DISABLE_FAILURE_PREDICTION' => 81,

		'CMD_ENABLE_PERFORMANCE_DATA' => 82,
		'CMD_DISABLE_PERFORMANCE_DATA' => 83,

		'CMD_SCHEDULE_HOSTGROUP_HOST_DOWNTIME' => 84,
		'CMD_SCHEDULE_HOSTGROUP_SVC_DOWNTIME' => 85,
		'CMD_SCHEDULE_HOST_SVC_DOWNTIME' => 86,

		/* new commands in Nagios 2.x found below... */
		'CMD_PROCESS_HOST_CHECK_RESULT' => 87,

		'CMD_START_EXECUTING_HOST_CHECKS' => 88,
		'CMD_STOP_EXECUTING_HOST_CHECKS' => 89,

		'CMD_START_ACCEPTING_PASSIVE_HOST_CHECKS' => 90,
		'CMD_STOP_ACCEPTING_PASSIVE_HOST_CHECKS' => 91,

		'CMD_ENABLE_PASSIVE_HOST_CHECKS' => 92,
		'CMD_DISABLE_PASSIVE_HOST_CHECKS' => 93,

		'CMD_START_OBSESSING_OVER_HOST_CHECKS' => 94,
		'CMD_STOP_OBSESSING_OVER_HOST_CHECKS' => 95,

		'CMD_SCHEDULE_HOST_CHECK' => 96,
		'CMD_SCHEDULE_FORCED_HOST_CHECK' => 98,

		'CMD_START_OBSESSING_OVER_SVC' => 99,
		'CMD_STOP_OBSESSING_OVER_SVC' => 100,

		'CMD_START_OBSESSING_OVER_HOST' => 101,
		'CMD_STOP_OBSESSING_OVER_HOST' => 102,

		'CMD_ENABLE_HOSTGROUP_HOST_CHECKS' => 103,
		'CMD_DISABLE_HOSTGROUP_HOST_CHECKS' => 104,

		'CMD_ENABLE_HOSTGROUP_PASSIVE_SVC_CHECKS' => 105,
		'CMD_DISABLE_HOSTGROUP_PASSIVE_SVC_CHECKS' => 106,

		'CMD_ENABLE_HOSTGROUP_PASSIVE_HOST_CHECKS' => 107,
		'CMD_DISABLE_HOSTGROUP_PASSIVE_HOST_CHECKS' => 108,

		'CMD_ENABLE_SERVICEGROUP_SVC_NOTIFICATIONS' => 109,
		'CMD_DISABLE_SERVICEGROUP_SVC_NOTIFICATIONS' => 110,

		'CMD_ENABLE_SERVICEGROUP_HOST_NOTIFICATIONS' => 111,
		'CMD_DISABLE_SERVICEGROUP_HOST_NOTIFICATIONS' => 112,

		'CMD_ENABLE_SERVICEGROUP_SVC_CHECKS' => 113,
		'CMD_DISABLE_SERVICEGROUP_SVC_CHECKS' => 114,

		'CMD_ENABLE_SERVICEGROUP_HOST_CHECKS' => 115,
		'CMD_DISABLE_SERVICEGROUP_HOST_CHECKS' => 116,

		'CMD_ENABLE_SERVICEGROUP_PASSIVE_SVC_CHECKS' => 117,
		'CMD_DISABLE_SERVICEGROUP_PASSIVE_SVC_CHECKS' => 118,

		'CMD_ENABLE_SERVICEGROUP_PASSIVE_HOST_CHECKS' => 119,
		'CMD_DISABLE_SERVICEGROUP_PASSIVE_HOST_CHECKS' => 120,

		'CMD_SCHEDULE_SERVICEGROUP_HOST_DOWNTIME' => 121,
		'CMD_SCHEDULE_SERVICEGROUP_SVC_DOWNTIME' => 122,

		'CMD_CHANGE_GLOBAL_HOST_EVENT_HANDLER' => 123,
		'CMD_CHANGE_GLOBAL_SVC_EVENT_HANDLER' => 124,

		'CMD_CHANGE_HOST_EVENT_HANDLER' => 125,
		'CMD_CHANGE_SVC_EVENT_HANDLER' => 126,

		'CMD_CHANGE_HOST_CHECK_COMMAND' => 127,
		'CMD_CHANGE_SVC_CHECK_COMMAND' => 128,

		'CMD_CHANGE_NORMAL_HOST_CHECK_INTERVAL' => 129,
		'CMD_CHANGE_NORMAL_SVC_CHECK_INTERVAL' => 130,
		'CMD_CHANGE_RETRY_SVC_CHECK_INTERVAL' => 131,

		'CMD_CHANGE_MAX_HOST_CHECK_ATTEMPTS' => 132,
		'CMD_CHANGE_MAX_SVC_CHECK_ATTEMPTS' => 133,

		'CMD_SCHEDULE_AND_PROPAGATE_TRIGGERED_HOST_DOWNTIME' => 134,

		'CMD_ENABLE_HOST_AND_CHILD_NOTIFICATIONS' => 135,
		'CMD_DISABLE_HOST_AND_CHILD_NOTIFICATIONS' => 136,

		'CMD_SCHEDULE_AND_PROPAGATE_HOST_DOWNTIME' => 137,

		'CMD_ENABLE_SERVICE_FRESHNESS_CHECKS' => 138,
		'CMD_DISABLE_SERVICE_FRESHNESS_CHECKS' => 139,

		'CMD_ENABLE_HOST_FRESHNESS_CHECKS' => 140,
		'CMD_DISABLE_HOST_FRESHNESS_CHECKS' => 141,

		'CMD_SET_HOST_NOTIFICATION_NUMBER' => 142,
		'CMD_SET_SVC_NOTIFICATION_NUMBER' => 143,

		/* new commands in Nagios 3.x found below... */
		'CMD_CHANGE_HOST_CHECK_TIMEPERIOD' => 144,  
		'CMD_CHANGE_SVC_CHECK_TIMEPERIOD' => 145,

		'CMD_PROCESS_FILE' => 146,

		'CMD_CHANGE_CUSTOM_HOST_VAR' => 147,
		'CMD_CHANGE_CUSTOM_SVC_VAR' => 148,
		'CMD_CHANGE_CUSTOM_CONTACT_VAR' => 149,

		'CMD_ENABLE_CONTACT_HOST_NOTIFICATIONS' => 150,
		'CMD_DISABLE_CONTACT_HOST_NOTIFICATIONS' => 151,
		'CMD_ENABLE_CONTACT_SVC_NOTIFICATIONS' => 152,
		'CMD_DISABLE_CONTACT_SVC_NOTIFICATIONS' => 153,

		'CMD_ENABLE_CONTACTGROUP_HOST_NOTIFICATIONS' => 154,
		'CMD_DISABLE_CONTACTGROUP_HOST_NOTIFICATIONS' => 155,
		'CMD_ENABLE_CONTACTGROUP_SVC_NOTIFICATIONS' => 156,
		'CMD_DISABLE_CONTACTGROUP_SVC_NOTIFICATIONS' => 157,

		'CMD_CHANGE_RETRY_HOST_CHECK_INTERVAL' => 158,

		'CMD_SEND_CUSTOM_HOST_NOTIFICATION' => 159,
		'CMD_SEND_CUSTOM_SVC_NOTIFICATION' => 160,

		'CMD_CHANGE_HOST_NOTIFICATION_TIMEPERIOD' => 161,
		'CMD_CHANGE_SVC_NOTIFICATION_TIMEPERIOD' => 162,
		'CMD_CHANGE_CONTACT_HOST_NOTIFICATION_TIMEPERIOD' => 163,
		'CMD_CHANGE_CONTACT_SVC_NOTIFICATION_TIMEPERIOD' => 164,

		'CMD_CHANGE_HOST_MODATTR' => 165,
		'CMD_CHANGE_SVC_MODATTR' => 166,
		'CMD_CHANGE_CONTACT_MODATTR' => 167,
		'CMD_CHANGE_CONTACT_MODHATTR' => 168,
		'CMD_CHANGE_CONTACT_MODSATTR' => 169,

		/* custom command introduced in Nagios 3.x */
		'CMD_CUSTOM_COMMAND' => 999,
	);
	return $core_commands;
}

?>
