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
	global $NagiosUser; 

	if($type == 'services')
	{
		$state_counts = array('OK'=>0, 'WARNING'=>0, 'CRITICAL'=>0, 'UNKNOWN'=>0, 'PENDING'=>0);
		//$state_counts = array(0 =>0, 1 =>0, 2 =>0, 3=>0, 'PENDING'=>0);	
		if (is_null($array)) {
			$array = $NagiosData->getProperty('services');
			
		}
	}
	elseif($type == 'hosts')
	{
		$state_counts = array('UP'=>0, 'DOWN'=>0, 'UNREACHABLE'=>0, 'UNKNOWN'=>0, 'PENDING'=>0);
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
		 
		if($type=='services')
		{
			//process_service_status_keys($a);
			if($NagiosUser->is_authorized_for_service($a['host_name'],$a['service_description']))  
					$state_counts[return_service_state($a['current_state'])]++;							
		}	
		if($type=='hosts')
		{
			//process_host_status_keys($a);
			if(isset($a['host_name']) && $NagiosUser->is_authorized_for_host($a['host_name'])) 
					$state_counts[return_host_state($a['current_state'])]++;	
		}
	}
		
	
	return $state_counts;					  
}

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
	return get_by_state($state, $service_data,true);
}

function get_by_state($state, $data,$service=false)
{
	$newdata = array(); 
				
	if($service) {
		foreach($data as $d)
			if(return_service_state($d['current_state']) == $state)  $newdata[] = $d;  	
	}
	else 	{
		foreach($data as $d)
			if(return_host_state($d['current_state']) == $state)  $newdata[] = $d;   		
	}	

	return $newdata;
//		return array_filter($data, create_function('$d', 'return return_service_state($d[\'current_state\']) == \''.$state.'\';'));
//	else 
//		return array_filter($data, create_function('$d', 'return return_host_state($d[\'current_state\']) == \''.$state.'\';'));	
	
}



function get_by_name($name, $data, $field='host_name',$host_filter=false) {
	//echo $field; 
//	$name = preg_quote($name, '/');
   $newarray = array();
	//bug fix for hosts that don't have any services -MG 
	if(!array_key_exists($field, $data) && htmlentities($_GET['type'],ENT_QUOTES) == 'hosts') 
	   $field = 'host_name'; 
 
	if($host_filter) {//match exact hostname 
	   foreach($data as $d) {
	      if($host_filter == $d[$field])
	         $newarray[]=$d;
	   } 
	}   
	else { //match for search 	   
	   foreach($data as $d) {
	      if(preg_match("/$name/i", $d[$field]))
	      $newarray[] = $d;	   
	   }
	 } 
	   //return array_filter($data, create_function('$d', 'return preg_match("/'.$name.'/i", $d[\''.$field.'\']);'));
	 return $newarray;  
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

////////////////////////////////////////////////
//
//RETURNS: integer of hosts or services with 'current_state' matching $state filtered by arg 
function count_by_state_with_args($state, $array, $arg1, $arg2)
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
function check_comments($hostname,$servicename='')
{
	global $NagiosData;	
	$count = 0;
   //count service 
	if ($servicename != '') {
	   $servicecomments = $NagiosData->getProperty('servicecomments');
		foreach($servicecomments as $comment) {
			if ($comment['host_name'] == $hostname && $comment['service_description'] == $servicename)  $count++;
			
		}//end foreach 
	} //end IF 
	else { //count host comments
	   $hostcomments = $NagiosData->getProperty('hostcomments');
		foreach($hostcomments as $comment)
		   if($comment['host_name']==$hostname) $count++;
   } //end else 


	return ($count == 0 ? false : $count);
}

function get_host_downtime($hostname)
{
	global $NagiosData;
	$host = $NagiosData->get_details_by('host', $hostname);

	return $host['scheduled_downtime_depth']; //returns integer 

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
