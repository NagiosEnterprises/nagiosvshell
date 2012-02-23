<?php  //build_groups.php  this page contains all of the functions that process group information 


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


//creates group array based on type 
//$objectarray - expecting an object group array -> $hostgroups_objs $servicegroups_objs $contactgroups
//				-these groups are read from objects.cache file  
//$type - expecting 'host' 'service' or 'contact'  
function build_group_array($objectarray, $type)
{
	$membersArray = array(); 
	$index = $type.'group_name';

	foreach ($objectarray as $object)
	{
		$group = $object[$index];
		if (isset($object['members']))
		{
			$members = $object['members'];
			$lineitems = explode(',', trim($members));
			
			//array_walk($lineitems, create_function('$v', '$v = trim($v);'));  //XXX BAD to use create_function 
			array_walk($lineitems, 'trim'); 
			
			$group_members = NULL;
			if ($type == 'host' || $type == 'contact')
			{
				$group_members = $lineitems;
			}
			elseif ($type == 'service')
			{
				for ($i = 0; $i < count($lineitems); $i+=2)
				{
					$host = $lineitems[$i];
					$service = $lineitems[$i+1];
					$group_members[$host][] = $service; 
					/* (
						'host_name' => $host,
						'service_description' => $service); */

				}
			}

			$membersArray[$group] = $group_members;
		}
	}

	return $membersArray;
}



/* returns group status details array 
 *
 * $groups = $hostsgroups or $servicegroups 
 */ 
function build_hostgroup_details($group_members) //make this return the totals array for hosts and services 
{
	global $NagiosData;
	global $NagiosUser; 
	
	$hosts = $NagiosData->getProperty('hosts');
	
//	//add filter for user-level filtering 
//	if(!$NagiosUser->is_admin()) {
		//print $type; 
//		$hosts = user_filtering($hosts,'hosts'); 	
//	}

	$hostgroup_details = array();
	foreach($group_members as $member)
	{
		if($NagiosUser->is_authorized_for_host($member)) //user-level filtering 
			$hostgroup_details[] = $hosts[$member];
	}

	return $hostgroup_details;
}

/* returns group details array.  This function is used on the hostgroups page for the grid view 
 *  
 * $groups = $hostsgroups or $servicegroups 
 */
function build_host_servicegroup_details($group_members)  
{
	global $NagiosData;
	global $NagiosUser; 
	$hosts = $NagiosData->getProperty('hosts');
	

	$servicegroup_details = array();
	foreach($group_members as $member)
	{
		if($NagiosUser->is_authorized_for_host($member)) //user-level filtering 
		{
			if (isset($hosts[$member]['services']))
				foreach ($hosts[$member]['services'] as $service) 
				{
					if($NagiosUser->is_authorized_for_service($member,$service)) //user-level filtering 
						$servicegroup_details[] = $service;	
				}						
		}
	}
	return $servicegroup_details;
}


/* used on host and service details pages 
 *
 * Expecting host name and/or service name 
 * checks against list of groups members 
 *
 * returns a list of groups separated by spaces  
 */ 
function check_membership($hostname='', $servicename='', $servicegroup_name='')
{
	global $NagiosData;
	$hostgroups_objs = $NagiosData->getProperty('hostgroups_objs');
	$servicegroups_objs = $NagiosData->getProperty('servicegroups_objs');

	$hostname = trim($hostname);
	$servicename = trim($servicename);
	$servicegroup_name = trim($servicegroup_name);

	$memberships = array();
	if($hostname!='' && $servicename!='')
	{
		//search servicegroups array 
		
		//create regexp string for 'host,service'
		$hostservice = "$hostname,$servicename";
		$hostservice_regex = preg_quote($hostservice, '/');

		//check regex against servicegroup 'members' index 
		if ($servicegroup_name!='' && isset($servicegroups_objs[$servicegroup_name])) {
			$group = $servicegroups_objs[$servicegroup_name];
			if (preg_match("/$hostservice_regex/", $group['members'])) {
				$str = isset($group['alias']) ? $group['alias'] : $group['servicegroup_name']; 
				$memberships[] = $str;
			}

		} else {
			foreach($servicegroups_objs as $group)
			{
				if(isset($group['members']) && preg_match("/$hostservice_regex/", $group['members']))
				{
					//use alias as default display name, else use groupname 
					$str = isset($group['alias']) ? $group['alias'] : $group['servicegroup_name']; 
					$memberships[] = $str;
				}
			}
		}
	}
	
	//check for host membership 
	elseif($hostname!='' && $servicename=='')
	{
	
		$hostname_regex = preg_quote($hostname, '/');
		foreach($hostgroups_objs as $group)
		{
			if(isset($group['members']) && preg_match("/$hostname_regex/", $group['members']))
			{
				//use alias as default display name, else use groupname 
				$str = isset($group['alias']) ? $group['alias'] : $group['hostgroup_name'];
				$memberships[] = $str;
			}
		}
	}
	 
  return empty($memberships) ? NULL : join(' ', $memberships);
}


/* used on servicegroups page 
 *
 * creates arrays of service details organized by groupnames 
 *	@TODO This function is not very efficient, can definitely be refactored for faster load times 
 * Returns 3-dimensional array:groupnames->members->details
 */ 
function build_servicegroups_array()
{
	global $NagiosData;
	global $NagiosUser; 
	
	$servicegroups = $NagiosData->getProperty('servicegroups');			
	$services = $NagiosData->getProperty('services');		
	$services = user_filtering($services,'services'); 
	
	$servicegroups_details = array(); //multi-dim array to hold servicegroups 	
	foreach($servicegroups as $groupname => $members)
	{
		$servicegroups_details[$groupname] = array();
  		//array_dump($members); 
		foreach($services as $service)
		{	
			if(isset($members[$service['host_name']]) && in_array($service['service_description'],$members[$service['host_name']]))	{
				process_service_status_keys($service);		
				$servicegroups_details[$groupname][] = $service;			
			}						
		}
	}

	return $servicegroups_details; 
}


/* Preprocess all of the data for hostgroups display/output
 */
function get_hostgroup_data()
{
	global $NagiosData;
	global $NagiosUser; 

	$hostgroups = $NagiosData->getProperty('hostgroups');
	$hosts = $NagiosData->getProperty('hosts');

	$hostgroup_data = array();
	foreach ($hostgroups as $group => $members) 
	{
		
		
		$hostgroup_data[$group] = array(
			'member_data' => array(),
			'host_counts'    => get_state_of('hosts', build_hostgroup_details($members)),
			'service_counts' => get_state_of('services', build_host_servicegroup_details($members))
			);
		
		//skip ahead if there are no authorized hosts			
		if(array_sum($hostgroup_data[$group]['host_counts'])==0) continue; //skip empty groups 
			
		foreach ($members as $member) 
		{
		
			if(!$NagiosUser->is_authorized_for_host($member)) continue; //user-level filtering 		
		
			$host = $hosts[$member];
			process_host_status_keys($host); 
			$hostgroup_data[$group]['member_data'][$member]['host_name'] = $host['host_name'];
			$hostgroup_data[$group]['member_data'][$member]['host_state'] = $host['current_state'];
			$hostgroup_data[$group]['member_data'][$member]['state_class'] = get_color_code($host);
			$hostgroup_data[$group]['member_data'][$member]['services'] = array();
			$hostgroup_data[$group]['member_data'][$member]['host_url'] = 
				BASEURL.'index.php?type=hostdetail&name_filter='.urlencode($host['host_name']);

			
			if (isset($host['services'])) {
				foreach($host['services'] as $service) {
				
					if(!$NagiosUser->is_authorized_for_service($member,$service['service_description'])) continue; //user-level filtering 					
				
					process_service_status_keys($service);
					$service_data = array(
						'state_class' => get_color_code($service),
						'description' => $service['service_description'],
						'service_url' => htmlentities(BASEURL.'index.php?type=servicedetail&name_filter='.$service['service_id']),
					);
					$hostgroup_data[$group]['member_data'][$member]['services'][] = $service_data;
				}
			}
			
			
		}
		
	}
	return $hostgroup_data;
}




function get_servicegroup_data()
{
	$servicegroup_data = array();

	$sg_details = build_servicegroups_array();
	foreach($sg_details as $group => $members)
	{
		if(empty($sg_details[$group])) continue; //skip unauthorized groups 
		$servicegroup_data[$group]['state_counts'] = array();
		foreach(array('OK', 'WARNING', 'CRITICAL', 'UNKNOWN') as $state)
		{
			$servicegroup_data[$group]['state_counts'][$state] = count_by_state($state, $members);
		}
		$servicegroup_data[$group]['services'] = array();
		foreach ($members as $serv) {
			$service_data = array(
				'host_name'   => $serv['host_name'],
				'host_url'    => htmlentities(BASEURL.'index.php?type=hostdetail&name_filter='.$serv['host_name']),
				'service_url' => htmlentities(BASEURL.'index.php?type=servicedetail&name_filter='.$serv['service_id']),
				'plugin_output' => $serv['plugin_output'],
				'description'   => $serv['service_description'],
				'current_state' => $serv['current_state'],
			);
			$servicegroup_data[$group]['services'][] = $service_data;
		}
	}

	return $servicegroup_data;
}

?>