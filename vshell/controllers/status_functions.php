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
function get_state_of($type) //create host or service arrays by status 
{
	global $hosts;
	global $services;
	$s0 = 0;
	$s1 = 0;
	$s2 = 0;
	$s3 = 0;
	if($type == 'services')
	{
		$zero = 'OK';
		$one = 'WARNING';
		$two = 'CRITICAL';
		$three = 'UNKNOWN';
		$array = $services;	
	}
	else
	{
		$zero = 'UP';
		$one = 'DOWN';
		$two = 'UNREACHABLE';
		$three = 'UNKNOWN';
		$array = $hosts;
	}
	foreach($array as $a)
	{
		//print "State is :".$a['current_state'];
		switch($a['current_state'])
		{
			case $zero:
			//print "$type: ".$a['host_name']." is UP.<br />";
			$s0++;
			break;
			
			case $one:
			//print "$type: ".$a['host_name']." is DOWN.<br />";
			$s1++;
			break;
			
			case $two:
			//print "$type: ".$a['host_name']." is UNREACHABLE.<br />";
			$s2++;
			break;
			
			case $three:
			//print "$type: ".$a['host_name']." is UNNOWN.<br />";
			$s3++;
			break;
			
			
		}
	}
	
	//creates count array for service/host status 
	$states = array( $zero => $s0,
						  $one => $s1,
						  $two => $s2,
						  $three => $s3 );	
	return $states;					  
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
function get_hosts_by_state($state, $array)
{
	$hosts_by_state = array();
	
	foreach($array as $a)
	{
		if($a['current_state'] == $state)
		{
			//echo $host['host_name']." is $state<br />";
			$hosts_by_state[] = $a;
		}
	}
	return $hosts_by_state;
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
function get_services_by_state($state, $array)
{

	$services_by_state = array();
	
	foreach($array as $a)
	{
		if(trim($a['current_state']) == trim($state))
		{
			//echo $service['host_name']." is $state<br />";
			$services_by_state[] = $a;
		}
	}
	return $services_by_state;
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

	$details = grab_details($type); //grab full status details for host/service 
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
		switch($arg)
		{
			case 0:
			return 'OK';
						
			case 1:
			return 'WARNING';

			case 2:
			return 'CRITICAL';

			case 3:
			return 'UNKNOWN';
	
			default:
			return 'UNKNOWN';

		}	
}


///////////////////////////////////////////////////////
//expecting a status code, 0-4 
//returns status as a string 
function return_host_state($arg)
{
		switch($arg)
		{		
			case 0:
			return 'UP';
						
			case 1:
			return 'DOWN';
	
			case 2:
			return 'UNREACHABLE';
	
			default:
			return 'UNKNOWN';
		}
}

///////////////////////////////////////////////////////
//expecting a status code, 0-1 
//returns status as a string 
function return_state_type($arg)
{
		switch($arg)
		{		
			case 0:
			return 'Soft';
						
			case 1:			
			return 'Hard';
	
			default:
			return 'Unknown';
		}
}

///////////////////////////////////////////////////////
//expecting a status code, 0-1 
//returns status as a string 
function return_enabled($arg)
{
		switch($arg)
		{		
			case 0:
			return 'Disabled';
						
			case 1:			
			return 'Enabled';
	
			default:
			return 'Unknown';
		}
}

///////////////////////////////////
//Expecting(requires): $hostname
//				(optional) $servicename = 'service_description'  
//returns count of comments for a host or service 
//
function check_comments($hostname='',$servicename='')
{
	global $comments;
	$count = 0;
	if($hostname!='' && $servicename!='') //checking for service comment 
	{
		foreach($comments as $comment)
		{
			if(isset($comment['service_description']))
			{
				if(trim($comment['host_name']) == trim($hostname) && 
				   trim($comment['service_description']) == trim($servicename) )
				{
					$count++;
				}//end matching IF 
			}//end IF 
		}//end FOREACH 
	}//end main IF 
	elseif($hostname!='') //checking for host comment 
	{
		foreach($comments as $comment)
		{
			if(trim($comment['host_name']) == trim($hostname) )
			{
				//echo 'host comment'; 
				$count++;
			}//end matching IF 
		}//end FOREACH 
	}//end main IF 	
	else { return false; }
	if($count == 0){ return false; }	
	return $count;
}

function get_host_downtime($hostname)
{
	$host = get_details_by('host', $hostname);
	return trim($host['scheduled_downtime_depth']); //returns integer 

}

?>
