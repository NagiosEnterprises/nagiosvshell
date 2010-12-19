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

//////////////////////////////////////////////////
//returns group status details array 
//
//$groups = $hostsgroups or $servicegroups 
//
function build_hostgroup_details($group_members) //make this return the totals array for hosts and services 
{
	global $NagiosData;
	$hosts = $NagiosData->getProperty('hosts');

	$hostgroup_details = array();
	foreach($group_members as $member)
	{
		$hostgroup_details[] = $hosts[$member];
	}

	return $hostgroup_details;
}

//returns group details array.  This function is used on the hostgroups page for the grid view 
// 
//$groups = $hostsgroups or $servicegroups 
//
function build_host_servicegroup_details($group_members)  
{
	global $NagiosData;
	$hosts = $NagiosData->getProperty('hosts');

	$servicegroup_details = array();
	foreach($group_members as $member)
	{
		if (isset($hosts[$member]['services']))
		{
			foreach ($hosts[$member]['services'] as $service)
			{
				$servicegroup_details[] = $service;
			}
		}
	}
	return $servicegroup_details;
}


//used on host and service details pages 
//
//Expecting host name and/or service name 
//checks against list of groups members 
//
//returns a list of groups separated by spaces  
// 
function check_membership($hostname='', $servicename='', $servicegroup_name='')
{
	//global $hostgroups_objs;
	//global $servicegroups_objs;
	global $NagiosData;
	$hostgroups_objs = $NagiosData->getProperty('hostgroups_objs');
	$servicegroups_objs = $NagiosData->getProperty('servicegroups_objs');

	//print_r($servicegroups_objs);	
	$memberships = array();
	if($hostname!='' && $servicename!='')
	{
		//search servicegroups array 
		
		//create reg expression string for 'host,service'
		//$string = '/'.trim($hostname).','.trim($servicename).'/';
		$hostservice = trim($hostname).','.trim($servicename);
		$hostservice_regex = preg_quote($hostservice, '/');
		//check regExpr against servicegroup 'members' index 
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
			}//end FOREACH 
		}
	}//end services IF 
	
	//check for host membership 
	elseif($hostname!='' && $servicename=='')
	{
	
		$hostname_regex = preg_quote($hostname);
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


//used on servicegroups page 
//
//creates arrays of service details organized by groupnames 
//Returns 3-dimensional array:groupnames->members->details
//
function build_servicegroups_array()
{
	//global $servicegroups; //global array of servicegroup members 
	//global $services;
	//print_r($servicegroups);
	global $NagiosData;
	$servicegroups = $NagiosData->getProperty('servicegroups');
	$services = $NagiosData->getProperty('services');

	$servicegroups_details = array(); //multi-dim array to hold servicegroups 	
	foreach($servicegroups as $groupname => $member)
	{
		//print $groupname; //is title of group 
		$servicegroups_details[$groupname] = array();
  
		foreach($services as $service)
		{
			$membership = check_membership($service['host_name'], $service['service_description'], $groupname);
			if($membership)
			{
			
				//print $groupname.$service['host_name'].$service['service_description'].'<br />';
				//print_r($service);
				$servicegroups_details[$groupname][] = $service;
				//print "member!";
			}
			
		}
	}


	return $servicegroups_details; 
}

?>
