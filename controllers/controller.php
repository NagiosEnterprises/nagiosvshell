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

// view=<view> => mode=view&type=<view>
// cmd=<cmd>&arg=<arg> => mode=cmd&type=<cmd>&arg=<arg>

function page_router()
{

	//Page will display tactical overview by default 
	if(isset($_GET['view']))
	{
		$arg = htmlentities($_GET['view']);
		$type = 'view';
		switchboard($type, $arg); // switchboard('view', $_GET['view']) => switchboard($mode, $type);
	}
	elseif(isset($_GET['object']))
	{
		$arg = htmlentities($_GET['object']);
		$type = 'object';
		switchboard($type, $arg);
	}
	elseif(isset($_GET['xml']))
	{
		$arg = htmlentities($_GET['xml']);
		$type = 'xml';
		switchboard($type, $arg);
	}
	elseif(isset($_GET['cmd']))
	{
		$cmd = htmlentities($_GET['cmd']);
		$arg = htmlentities($_GET['arg']);
		command_router($cmd, $arg);
	}
	else
	{
		include_once(DIRBASE.'/views/tac.php');
	}	

}


//secondary controller for page redirection 
function switchboard($type, $arg) //$type = $_GET[xml, view, object]   $arg=one of the global data arrays 
{

	global $authorizations;

	//make conditional based on site permissions 
	
	//$type = valid $_GET variable 
	//calls page display functions based on get variable and displays in index page

	switch($arg)
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
			$array = $NagiosData->getProperty($arg);

			$title = ucwords(preg_replace('/objs/', 'Objects', preg_replace('/_/', ' ', $arg)));
		break;

		default:
		//initialize main data arrays 
		include_once(DIRBASE.'/views/tac.php');
		break;		
	} 	
	if(isset($array, $title))
	{	
		switch($type) //change to include files for easier maintenance 
		{
			case 'xml':
			build_xml_page($array,$title);
			header('Location: '.BASEURL.'tmp/'.$title.'.xml');
			break;
			
			case 'view':
			//build_table($array);
			list($start, $limit) = get_pagination_values();
					
			switch($arg)
			{
				case 'services':
				if($authorizations['services']==1)
				{
					display_services($array, $start, $limit);
				}	
				break;
				
				case 'hosts':
				if($authorizations['hosts']==1)
				{
					display_hosts($array,$start,$limit);
				}	  
				break;
				
				default:
				if($authorizations['hosts']==1 && $authorizations['services']==1)
				{ 
					include(DIRBASE.'/views/'.urlencode($arg).'.php');
				}	
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
				build_object_list($array, $arg);
			}	
			break;
						
			default:
			include_once(DIRBASE.'/views/tac.php');
			break;		
		}	
	}
	else
	{
		include_once(DIRBASE.'/views/tac.php');
	}	
}

function get_pagination_values() {
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
	//global $permissions;  //Array read from cgi.cfg file: permissions => users array 

	global $NagiosData;
	$permissions = $NagiosData->getProperty('permissions');
	
	foreach($permissions as $key => $array)//perms  = array('system_information'
	{
		foreach($array as $user)
		{
			if(trim($user) == $username || trim($user) == '*') 
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
