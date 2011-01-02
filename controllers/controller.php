<?php //controller.php		  access controls functions 


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



function send_home() //redirects user to index page 
{
	header('Location: '.BASEURL);
}

/////////////////////////////////////////
//
//page_router is main controller function for the interface.  
//It processes all requests sent from the browser 
//
//

// *OLD*
// view=<hosts,services,hostgroups,servicegroups>
// cmd=filter<hosts,services>,arg=<UP,DOWN,WARNING,UNREACHABLE,UNKNOWN>

// *NEW*
// mode=<view,filter,xml,json>
// type=<hosts,services,hostgroups,servicegroups>
// arg=<UP,DOWN,WARNING,UNREACHABLE,UNKNOWN,hostname>

// *IDEA*
// mode=<view,data>
// type=<overview (default), hosts,services,hostgroups,servicegroups,hostdetail,servicedetail,object>
// filter=<filter_arg>
// * filter_arg=<UP,DOWN,WARNING,UNREACHABLE,UNKNOWN,critical|object type>

function router()
{

	$mode = NULL;
	$type = NULL;
	$filter = NULL;

	if (isset($_GET['mode'])) { $mode = strtolower($_GET['mode']); }
	else { $mode = 'view'; }
	if (isset($_GET['type'])) { $type = strtolower($_GET['type']); }
	if (isset($_GET['filter'])) { $filter = $_GET['filter']; }

	global $NagiosData;
	switch($type) {
		case 'services':
		case 'hosts':
		case 'hostgroups':
		case 'servicegroups':
		case 'hostdetail':
		case 'servicedetail':
			$data = $NagiosData->getProperty($type);
			if ($filter && ($type == 'hosts' || $type == 'services')) {
			}

			$title = ucwords(preg_replace('/objs/', 'Objects', preg_replace('/_/', ' ', $type)));
		break;

		case 'object':
			$obj_filters = array('hosts_objs', 'services_objs', 'hostgroups_objs', 'servicegroups_objs',
				'timeperiods', 'contacts', 'contactgroups', 'commands');

			if (in_array($filter, $obj_filters)) {
				$data = $NagiosData->getProperty($filter);
			}
		break;

		default:
		break;
	}
}

function page_router()
{

	$mode = NULL;
	$type = NULL;
	$filter = NULL;

	if (isset($_GET['mode'])) { $mode = strtolower($_GET['mode']); }
	if (isset($_GET['type'])) { $type = strtolower($_GET['type']); }

	if ($mode == 'filter') {
		if (isset($_GET['arg'])) { $state = $_GET['arg']; }
		else { $mode = NULL; }
	}

	switch($mode) {
		case 'view':
		case 'object':
		case 'filter':
		default:
			
			$page_title = 'Nagios Visual Shell';
			include(DIRBASE.'/views/header.php');  //html head 
			print display_header($page_title);

			// Displayed stuff
			if ($mode == 'filter') {
				command_router($type, $state);
			} else {
				switchboard($mode, $type);
			}

			include(DIRBASE.'/views/footer.php');  //html head 
			print display_footer();
			break;

		case 'xml':
			build_xml_page($array,$title);
			header('Location: '.BASEURL.'tmp/'.$title.'.xml');
			break;

		case 'json':
			break;
	}

}


//secondary controller for page redirection 
function switchboard($mode, $type) //$type = $_GET[xml, view, object]   $arg=one of the global data arrays 
{
	global $authorizations;

	//make conditional based on site permissions 
	
	//$type = valid $_GET variable 
	//calls page display functions based on get variable and displays in index page

	$data = NULL;
	$title = NULL;
	switch($type)
	{
		case 'services':
		case 'hosts':
		case 'hosts_objs':
		case 'services_objs':
		case 'timeperiods':
		case 'contacts':
		case 'servicegroups':
		case 'hostgroups':
		case 'contactgroups':
		case 'commands':
		case 'hostgroups_objs':
		case 'servicegroups_objs':
	
			global $NagiosData;
			$data = $NagiosData->getProperty($type);

			$title = ucwords(preg_replace('/objs/', 'Objects', preg_replace('/_/', ' ', $type)));
		break;

		default:
		break;		
	} 	
	if(isset($data, $title))
	{	
		switch($mode) //change to include files for easier maintenance 
		{
			
			case 'view':
			//build_table($array);
			list($start, $limit) = get_pagination_values();
					
			switch($type)
			{
				case 'services':
				if($authorizations['services']==1)
				{
					include(DIRBASE.'/views/services.php');
					print display_services($data, $start, $limit);
				}	
				break;
				
				case 'hosts':
				if($authorizations['hosts']==1)
				{
					include(DIRBASE.'/views/hosts.php');
					print display_hosts($data,$start,$limit);
				}	  
				break;
				
				case 'hostgroups':
				if ($authorizations['hosts']==1)
				{
					include(DIRBASE.'/views/hostgroups.php');
					$hostgroup_data = get_hostgroup_data();
					print display_hostgroups($hostgroup_data);
				}
				break;

				case 'servicegroups':
				if($authorizations['hosts']==1 && $authorizations['services']==1)
				{ 
					include(DIRBASE.'/views/servicegroups.php');
					$servicegroup_data = get_servicegroup_data();
					print display_servicegroups($servicegroup_data);
				}	
				break;


				default:
				show_tac();
				break;
			}
			break;
						
			case 'object':  //authorization filter depends on the object, see display_functions.php
			if($authorizations['configuration_information']==1 || 
			   $authorizations['host_commands']==1 ||
			   $authorizations['service_commands']==1 ||
			   $authorizations['system_commands']==1)
			{
				//moved build_object_list to config_viewer.php page for easier editing 
				include(DIRBASE.'/views/config_viewer.php');
				print build_object_list($data, $type);
			}	
			break;

			default:
			show_tac();
			break;		
		}	
	}
	else
	{
		show_tac();
	}	
}

function show_tac()
{
	require(DIRBASE.'/views/tac.php');
	print get_tac();
}

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
		case 'servicedetail':
		if($authorizations['services']==1)
		{
			$dets = process_service_detail($arg);
			require(DIRBASE.'/views/servicedetails.php');
			print get_service_details($dets);	

		}	  
		break;
		
		case 'hostdetail':
		if($authorizations['hosts']==1)
		{
			$dets = process_host_detail($arg);
			require(DIRBASE.'/views/hostdetails.php');
			print get_host_details($dets);
		}	
		break;
		
		case 'services':
		if($authorizations['services']==1)
		{
			//global $services;
			$services = $NagiosData->getProperty('services');

			$servs = get_services_by_state(strtoupper($arg),$services); 
			//see views/services.php 
			list($start, $limit) = get_pagination_values();
			include(DIRBASE.'/views/services.php');
			print display_services($servs, $start, $limit);
		}
		break;
		
		case 'hosts':
		if($authorizations['hosts']==1)
		{
			//global $hosts;
			$hosts = $NagiosData->getProperty('hosts');

			$f_hosts = get_hosts_by_state(strtoupper($arg),$hosts); 
			//include(DIRBASE.'/views/services.php');
			list($start, $limit) = get_pagination_values();
			include(DIRBASE.'/views/hosts.php');
			print display_hosts($f_hosts, $start, $limit);
		}
		break;
		
		default:
		#include_once(DIRBASE.'/views/tac.php');
		show_tac();
		break;
	}
}

function get_pagination_values()
{
	$start = isset($_GET['start']) ? htmlentities($_GET['start']) : 0;
	$limit = isset($_COOKIE['limit']) ? $_COOKIE['limit'] : RESULTLIMIT;
	if(isset($_POST['pagelimit']))
	{       
		//set a site-wide cookie for the display limit 
		setcookie('limit', $_POST['pagelimit']);
		$limit = $_POST['pagelimit'];
	}

	return array($start, $limit);
}

////////////////////////////////////////////////////////////
//$username is obtained from $_SERVER authorized user for nagios 
//
function set_perms($username)
{
	global $NagiosData;
	$permissions = $NagiosData->getProperty('permissions');

	foreach($permissions as $key => $array)//perms  = array('system_information'
	{
		foreach($array as $user)
		{
			if($user == $username || $user == '*') 
			{
				//print "authorizing $username";
				authorize($key);
			}
		}				
	}
}
//////////////////////////////////////////////////////
//
//activates authorization for user.  See authorizations.inc.php for auth list 
//
function authorize($auth) //sets global permission 
{
	global $authorizations; //global authorization array controller 
	$authorizations[$auth] = 1;
}

?>
