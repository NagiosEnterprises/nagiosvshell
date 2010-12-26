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

function get_by_state($state, $data) {

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

	//$array_by_state = array();
	$count = 0;
	
	foreach($array as $a)
	{
		if($a['current_state'] == $state)
		{
			//echo $a['host_name']." is $state<br />";
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

function index_or_default($arg, $vals, $default) {
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

	$servicename = trim($servicename);
	$hostname = trim($hostname);

	$count = 0;

	if ($hostname != '') {
		if (isset($hosts[$hostname]) && isset($hosts[$hostname]['comments'])) {

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

	return trim($host['scheduled_downtime_depth']); //returns integer 

}

?>
