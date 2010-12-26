<?php  //this is the controller script to process commands sent from the browser  



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








//////////////////////////////////////////////////////////
//
//expecting $cmd = 'getservicedetail' 
//				$arg = 'serviceID' 
//
//processes command and displays page details based on arguments 
//
function command_router($cmd, $arg)
{
	global $authorizations;
	global $NagiosData;

	switch($cmd)
	{
		case 'getservicedetail':
		if($authorizations['services']==1)
		{
			$dets = process_service_detail($arg);
			include(DIRBASE.'/views/servicedetails.php');
		}	  
		break;
		
		case 'gethostdetail':
		if($authorizations['hosts']==1)
		{
			$dets = process_host_detail($arg);
			include(DIRBASE.'/views/hostdetails.php'); 
		}	
		break;
		
		case 'filterservices':
		if($authorizations['services']==1)
		{
			//global $services;
			$services = $NagiosData->getProperty('services');

			$servs = get_services_by_state($arg,$services); 
			//include(DIRBASE.'/views/services.php');
			//see views/services.php 
			list($start, $limit) = get_pagination_values();
			display_services($servs, $start, $limit);
		}
		break;
		
		case 'filterhosts':
		if($authorizations['hosts']==1)
		{
			//global $hosts;
			$hosts = $NagiosData->getProperty('hosts');

			$f_hosts = get_hosts_by_state($arg,$hosts); 
			//include(DIRBASE.'/views/services.php');
			list($start, $limit) = get_pagination_values();
			display_hosts($f_hosts, $start, $limit);
		}
		break;
		
		default:
		include_once(DIRBASE.'/views/tac.php');
		break;
	}
}




//////////////////////////////////////////////////
//
//expecting a service ID such as: 
//$arg = 'service15';
function process_service_detail($arg)
{
	global $NagiosData;
	$sd = $NagiosData->get_details_by('service', $arg);
	
	//make necessary calculations with array elements 
	$now = time();
	$duration =  $now-$sd['last_state_change']; //calculate duration
	$duration = date('d\d-H\h-i\m-s\s', $duration); 
	
	$current_state = return_service_state($sd['current_state']);	
	$statetype = return_state_type($sd['state_type']); //calculate state 
	$current_check = $sd['current_attempt'].' of '.$sd['max_attempts'];
	$last_check = date('M d H:i\:s\s Y', $sd['last_check']);
	$next_check = date('M d H:i\:s\s Y', $sd['next_check']);	
	$last_state_change = date('M d H:i\:s\s Y', $sd['last_state_change']);	
	
	if($sd['last_notification']==0){$last_notification='Never';} 
	else {$last_notification = date('M d H:i\:s\s Y', $sd['last_notification']);}
	
	if($sd['check_type']==0) {$check_type = 'Active';} else {$check_type='Passive';}	

	$check_latency = $sd['check_latency'].' seconds';
	$execution_time = $sd['check_execution_time'].' seconds';
	$state_change = $sd['percent_state_change'].'%';
	
	$membership = check_membership($sd['host_name'],$sd['service_description']);
	$state = return_service_state($sd['current_state']);
	
	//service attributes
	$ser_desc = preg_replace('/\ /', '+', trim($sd['service_description']));    //replacing spaces with pluses for cgi URL 
	//if else statements generate links for Nagios core commands based on status 
	$active_checks = return_enabled($sd['active_checks_enabled']);
	if($active_checks == 'Enabled') 
	{ $cmd_active_checks = CORECMD.'cmd_typ=6&host='.$sd['host_name'].'&service='.$ser_desc; } //6 is disable ac
	else{ $cmd_active_checks = CORECMD.'cmd_typ=5&host='.$sd['host_name'].'&service='.$ser_desc; } //47 is enable ac
	
	$passive_checks = return_enabled($sd['passive_checks_enabled']);
	if($passive_checks == 'Enabled')
	{$cmd_passive_checks = CORECMD.'cmd_typ=40&host='.$sd['host_name'].'&service='.$ser_desc;} //93 is stop pc 
	else{$cmd_passive_checks = CORECMD.'cmd_typ=92&host='.$sd['host_name'].'&service='.$ser_desc;} //92 is start pc
	
	$notifications = return_enabled($sd['notifications_enabled']);
	if($notifications == 'Enabled')
	{$cmd_notifications = CORECMD.'cmd_typ=23&host='.$sd['host_name'].'&service='.$ser_desc;} //disable notifications is 25
	else {$cmd_notifications = CORECMD.'cmd_typ=22&host='.$sd['host_name'].'&service='.$ser_desc;} //enabled notifications is 24 
	
	$flap_detection = return_enabled($sd['flap_detection_enabled']);
	if($flap_detection == 'Enabled')
	{$cmd_flap_detection = CORECMD.'cmd_typ=58&host='.$sd['host_name'].'&service='.$ser_desc;} //disable fd is 58 
	else{$cmd_flap_detection = CORECMD.'cmd_typ=57&host='.$sd['host_name'].'&service='.$ser_desc;} //enable fd is 57 
	
	$process_perf_data = return_enabled($sd['process_performance_data']);
	$obsession = return_enabled($sd['obsess_over_service']);
	if($obsession == 'Enabled')
	{$cmd_obsession = CORECMD.'cmd_typ=100&host='.$sd['host_name'].'&service='.$ser_desc;} // 102 stop obsession 
	else{$cmd_obsession = CORECMD.'cmd_typ=99&host='.$sd['host_name'].'&service='.$ser_desc;}// 101 stat obsession 
	
	$add_comment = CORECMD.'cmd_typ=3&host='.$sd['host_name'].'&service='.$ser_desc;
	
	if(trim($sd['problem_has_been_acknowledged']) == 0)
	{
		$cmd_acknowledge = CORECMD.'cmd_typ=34&host='.$sd['host_name'].'&service='.$ser_desc;//acknowledge problem 33 
		$ack_title = 'Ackowledge Problem';
	} 
	else
	{
		$cmd_acknowledge = CORECMD.'cmd_typ=52&host='.$sd['host_name'].'&service='.$ser_desc;  //51 removes ack
		$ack_title = 'Remove Ackowledgement';	
	}  
	
	//$cmd_map = CORECGI.'statusmap.cgi?host='.$sd['host_name'].'&service='.$ser_desc;	
	$cmd_custom_notification = CORECMD.'cmd_typ=160&host='.$sd['host_name'].'&service='.$ser_desc;
	$cmd_schedule_downtime = CORECMD.'cmd_typ=56&host='.$sd['host_name'].'&service='.$ser_desc;
	//$cmd_schedule_dt_all = CORECMD.'cmd_typ=86&host='.$sd['host_name'].'&service='.$ser_desc;
	$cmd_schedule_checks = CORECMD.'cmd_typ=7&host='.$sd['host_name'].'&service='.$ser_desc.'&force_check';
	//core commands
	$core_link = CORECGI.'extinfo.cgi?type=2&host='.$sd['host_name'].'&service='.$ser_desc;
	
											
	//service details array -> Use for displaying page 
	$details = array( 
									'Service' => $sd['service_description'],
									'Host'	=> $sd['host_name'],
									'Output' => $sd['plugin_output'],
									'MemberOf' => $membership,
									'CheckCommand' => $sd['check_command'],
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
									'PerformanceData' => $sd['performance_data'],
									'ActiveChecks' => $active_checks,
									'PassiveChecks' => $passive_checks,
									'Notifications' => $notifications,
									'FlapDetection' => $flap_detection,
									'ProcessPerformanceData' => $process_perf_data,
									'Obsession'		=> $obsession,
									'AddComment'	=> htmlentities($add_comment),
									'CmdActiveChecks'	=> htmlentities($cmd_active_checks),
									'CmdPassiveChecks' => htmlentities($cmd_passive_checks),
									'CmdNotifications' => htmlentities($cmd_notifications),
									'CmdFlapDetection' => htmlentities($cmd_flap_detection),
									'CmdObsession'		=> htmlentities($cmd_obsession),
									'CmdAcknowledge' => htmlentities($cmd_acknowledge),
									'CmdCustomNotification'	=> htmlentities($cmd_custom_notification),
									'CmdScheduleDowntime'	=> htmlentities($cmd_schedule_downtime),
									'CmdScheduleChecks'		=> htmlentities($cmd_schedule_checks),
									'AckTitle'				=> $ack_title,	
									'CoreLink'			=> htmlentities($core_link),								
								);
								
	return $details;
	

}

//$dets = process_service_detail($arg);
//include('../views/servicedetails.php');

///////////////////////////////////////
//
//expecting host name as $arg 
//
//processes a host array and sends to the hostdetails.php page 
//
function process_host_detail($arg)
{
	global $NagiosData;
	$hd = $NagiosData->get_details_by('host', $arg);
	$now = time();
	$duration =  $now-$hd['last_state_change']; //calculate duration
	$duration = date('d\d-H\h-i\m-s\s', $duration); 
	$current_state = return_host_state($hd['current_state']);	
	$statetype = return_state_type($hd['state_type']); //calculate state 
	$current_check = $hd['current_attempt'].' of '.$hd['max_attempts'];
	$last_check = date('M d H:i\:s\s Y', $hd['last_check']);
	$next_check = date('M d H:i\:s\s Y', $hd['next_check']);	
	$last_state_change = date('M d H:i\:s\s Y', $hd['last_state_change']);	
	
	if($hd['last_notification']==0){$last_notification='Never';} 
	else {$last_notification = date('M d H:i\:s\s Y', $hd['last_notification']);}
	
	if($hd['check_type']==0) {$check_type = 'Active';} else {$check_type='Passive';}	

	$check_latency = $hd['check_latency'].' seconds';
	$execution_time = $hd['check_execution_time'].' seconds';
	$state_change = $hd['percent_state_change'].'%';
	
	$membership = check_membership($hd['host_name']);
	$state = return_host_state($hd['current_state']);

	//host attributes 
	//if else statements generate links for Nagios core commands based on status 
	$active_checks = return_enabled($hd['active_checks_enabled']);
	if($active_checks == 'Enabled') 
	{ $cmd_active_checks = CORECMD.'cmd_typ=48&host='.$hd['host_name']; } //48 is disable ac
	else{ $cmd_active_checks = CORECMD.'cmd_typ=47&host='.$hd['host_name']; } //47 is enable ac
	
	$passive_checks = return_enabled($hd['passive_checks_enabled']);
	if($passive_checks == 'Enabled')
	{$cmd_passive_checks = CORECMD.'cmd_typ=93&host='.$hd['host_name'];} //93 is stop pc 
	else{$cmd_passive_checks = CORECMD.'cmd_typ=92&host='.$hd['host_name'];} //92 is start pc
	
	$notifications = return_enabled($hd['notifications_enabled']);
	if($notifications == 'Enabled')
	{$cmd_notifications = CORECMD.'cmd_typ=25&host='.$hd['host_name'];} //disable notifications is 25
	else {$cmd_notifications = CORECMD.'cmd_typ=24&host='.$hd['host_name'];} //enabled notifications is 24 
	
	$flap_detection = return_enabled($hd['flap_detection_enabled']);
	if($flap_detection == 'Enabled')
	{$cmd_flap_detection = CORECMD.'cmd_typ=58&host='.$hd['host_name'];} //disable fd is 58 
	else{$cmd_flap_detection = CORECMD.'cmd_typ=57&host='.$hd['host_name'];} //enable fd is 57 
	
	$process_perf_data = return_enabled($hd['process_performance_data']);
	$obsession = return_enabled($hd['obsess_over_host']);
	if($obsession == 'Enabled')
	{$cmd_obsession = CORECMD.'cmd_typ=102&host='.$hd['host_name'];} // 102 stop obsession 
	else{$cmd_obsession = CORECMD.'cmd_typ=101&host='.$hd['host_name'];}// 101 stat obsession 
	
	$add_comment = CORECMD.'cmd_typ=1&host='.$hd['host_name'];
	
	if(trim($hd['problem_has_been_acknowledged']) == 0)
	{
		$cmd_acknowledge = CORECMD.'cmd_typ=33&host='.$hd['host_name'];//acknowledge problem 33 
		$ack_title = 'Ackowledge Problem';
	} 
	else
	{
		$cmd_acknowledge = CORECMD.'cmd_typ=51&host='.$hd['host_name'];  //51 removes ack
		$ack_title = 'Remove Ackowledgement';	
	}  
	
	$cmd_map = CORECGI.'statusmap.cgi?host='.$hd['host_name'];	
	$cmd_custom_notification = CORECMD.'cmd_typ=159&host='.$hd['host_name'];
	$cmd_schedule_downtime = CORECMD.'cmd_typ=55&host='.$hd['host_name'];
	$cmd_schedule_dt_all = CORECMD.'cmd_typ=86&host='.$hd['host_name'];
	$cmd_schedule_checks = CORECMD.'cmd_typ=17&host='.$hd['host_name'];
	$core_link = CORECGI.'extinfo.cgi?type=1&host='.$hd['host_name'];

	$details = array( 
								
								'Host'	=> $hd['host_name'],
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




					
?>
