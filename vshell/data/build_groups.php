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
	//print "<p>$index</p>";
	if($type=='host' || $type =='contact')
	{
		foreach($objectarray as $object)
		{
			$group = $object[$index];
			//print "<p>$group</p>";
			$members = $object['members'];
			$membersArray[$group] = explode(',', trim($members));			
		}
	}

	
	if($type=='service')
	{
		foreach($objectarray as $object)
		{
			$group = $object[$index];
			//print "<p>group is: $group</p>";
			//print_r($group);
			//get line from cfg that displays: host,service
			if(isset($object['members']))
			{ 
				$members = $object['members'];
				//explode line items into an array 
				$lineitems = explode(',', trim($members));
				
				//convert lineitems into associative array so the hostname is paired with service_desc
				$sg_members = array();
				$i=0;	
				$c=0;		
				foreach($lineitems as $items)
				{
					$h = $lineitems[$i];
					$s = $lineitems[$i+1];
					$sg_members[$c]['host_name'] = $h;
					$sg_members[$c]['service_description'] = $s;
					$c++;
				}
				
				//print "<h4>$members</h4>";
				$membersArray[$group] = $sg_members;	
			}//end if(isset)		
		}//end foreach 
	}//end main IF 
	
	//ARRAY MEMBERS NEED SPACES TRIMMED!!!!!!!! 
	return $membersArray;
}

//////////////////////////////////////////////////
//returns group status details array 
//
//$groups = $hostsgroups or $servicegroups 
//
function build_hostgroup_details($groups) //make this return the totals array for hosts and services 
{
	global $hosts;
	
	$new_array = array();
	foreach($groups as $member)
	{
		//print $member."<br />"; //member = host name 
		$member = trim($member); //trim whitespace
			foreach($hosts	as $host)
			{
				if(trim($host['host_name']) == $member)
				{
					//print "adding new host:";
					$new_array[] = $host;					
				}
			}									
	}
	return $new_array;
}

//returns group details array.  This function is used on the hostgroups page for the grid view 
// 
//$groups = $hostsgroups or $servicegroups 
//
function build_host_servicegroup_details($groups)  
{
	global $services;
	
	$new_array = array();
	foreach($groups as $member)
	{
		//print $member."<br />"; //member = host name 
		$member = trim($member); //trim whitespace
			foreach($services	as $service)
			{
				if(trim($service['host_name']) == $member)
				{
					//print "adding new host:";
					$new_array[] = $service;					
				}
			}									
	}
	return $new_array;
}


//used on host and service details pages 
//
//Expecting host name and/or service name 
//checks against list of groups members 
//
//returns a list of groups separated by spaces  
// 
function check_membership($hostname='', $servicename='')
{
	global $hostgroups_objs;
	global $servicegroups_objs;
	//print_r($servicegroups_objs);	
	$memberships = ''; 
	if($servicename!='' && $hostname!='')
	{
		//search servicegroups array 
		
		//create reg expression string for 'host,service'
		//$string = '/'.trim($hostname).','.trim($servicename).'/';
		$string = trim($hostname).','.trim($servicename);
		//check regExpr against servicegroup 'members' index 
		foreach($servicegroups_objs as $group)
		{
			//print '<p>Members: '.$group['members'].'</p>';
			//print '<p>Alias: '.$group['alias'].'</p>';
			//print '<p>String: '.$string.'</p>';
			if(ereg($string, $group['members']))
			{
				//print "<h4>$string is a member of ".$group['alias']."</h4>";
				$memberships .= $group['alias'].' ';
			}
		}//end FOREACH 
	}//end services IF 
	
	//check for host membership 
	elseif($hostname!='' && $servicename=='')
	{
		
		foreach($hostgroups_objs as $group)
		{
			if(ereg($hostname, $group['members']))
			{
				$memberships .= $group['alias'].' ';
			}
		
		}
	}
	if($memberships!='')
	{
		return $memberships;
	}
	else 
	{
		return;
	}
	 
}


//used on servicegroups page 
//
//creates arrays of service details organized by groupnames 
//Returns 3-dimensional array:groupnames->members->details
//
function build_servicegroups_array()
{
	global $servicegroups; //global array of servicegroup members 
	global $services;
	//print_r($servicegroups);
	
	$servicegroups_details = array(); //multi-dim array to hold servicegroups 	
	foreach($servicegroups as $groupname=>$member)
	{
		//print $groupname; //is title of group 
		$servicegroups_details[$groupname] = array();
		
		foreach($services as $service)
		{
			$membership = check_membership($service['host_name'], $service['service_description']);
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



//create group arrays for global use 
///////////////////////////////////////////////////GLOBAL ARRAYS//////////////////   
if(isset($hostgroups_objs, $servicegroups_objs))
{
	//print "blah!";
	$hostgroups = build_group_array($hostgroups_objs, 'host');
	$servicegroups = build_group_array($servicegroups_objs, 'service');
}
else 
{
	print "Host and/or Servicegroups objects are not defined. ";
}


?>
