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

// *OLD*
// view=<hosts,services,hostgroups,servicegroups>
// cmd=filter<hosts,services>,arg=<UP,DOWN,WARNING,UNREACHABLE,UNKNOWN>

// *NEW*
// mode=<view,filter,xml,json>
// type=<hosts,services,hostgroups,servicegroups>
// arg=<UP,DOWN,WARNING,UNREACHABLE,UNKNOWN,hostname>

// *IDEA*
// mode=<html,json,xml>
// type=<overview (default), hosts,services,hostgroups,servicegroups,hostdetail,servicedetail,object>
// * state_filter=UP,DOWN,WARNING,UNREACHABLE,UNKNOWN,critical
// * name_filter=<string>
// * objtype_filter=<string>

function page_router()
{

	global $authorizations;
	global $NagiosUser; 

	list($mode, $type) = array(NULL, NULL);
	list($state_filter, $name_filter, $objtype_filter, $host_filter) = array(NULL, NULL, NULL, NULL);

	if (isset($_GET['type'])) { $type = strtolower($_GET['type']); } else { $type = 'overview'; }
	if (isset($_GET['mode'])) { $mode = strtolower($_GET['mode']); } else { $mode = 'html'; }

	if (isset($_GET['state_filter'])   && trim($_GET['state_filter'])   != '') 
	   $state_filter    = process_state_filter(htmlentities($_GET['state_filter']));     
	if (isset($_GET['name_filter'])    && trim($_GET['name_filter'])    != '')
	   $name_filter     = process_name_filter(htmlentities($_GET['name_filter']),ENT_QUOTES);  
	if (isset($_GET['host_filter'])    && trim($_GET['host_filter'])    != '')  
	   $host_filter = htmlentities($_GET['host_filter'],ENT_QUOTES);       
	if (isset($_GET['objtype_filter']) && trim($_GET['objtype_filter']) != '') 
	   $objtype_filter  = process_objtype_filter(htmlentities($_GET['objtype_filter'])); 

	list($data, $html_output_function) = array(NULL, NULL);

	switch($type) {
		case 'services':
		case 'hosts':
				$data = hosts_and_services_data($type, $state_filter, $name_filter, $host_filter);
				$html_output_function = 'hosts_and_services_output';
		break;

		case 'hostgroups':
		case 'servicegroups':
			if ($type == 'hostgroups' || $type == 'servicegroups') 
			{
				$data = hostgroups_and_servicegroups_data($type, $name_filter);
				$html_output_function = 'hostgroups_and_servicegroups_output';
			}	
		break;

		case 'hostdetail':
		case 'servicedetail':
				$data = host_and_service_detail_data($type, $name_filter);
				if(!$data) send_home(); //bail if not authorized  
				$html_output_function = 'host_and_service_detail_output';			
		break;

		case 'object':
			if ($objtype_filter)
			{
				if($NagiosUser->if_has_authKey('authorized_for_configuration_information')) //only administrative users should be able to see config info 
				{
					$data = object_data($objtype_filter, $name_filter);
					$type = $objtype_filter;
					$html_output_function = 'object_output';
				}
				else send_home(); 
			}	
		break;
		
		case 'backend':
			$xmlout = tac_xml(get_tac_data());
		break;

		case 'overview':
		default:
			//create function to return tac data as an array 
			$html_output_function = 'get_tac_html';
		break;
	}

	$output = NULL;
	switch($mode) {
		case 'html':
		default:
			$output =  mode_header($mode);
			$output .= $html_output_function($type, $data, $mode);
			$output .= mode_footer($mode);
		break;

		case 'json':
			header('Content-type: application/json');
			$output = json_encode($data);
		break;

		case 'xml':
			if($type!='backend')
			{
				require_once(DIRBASE.'/views/xml.php');
				$title = ucwords($type);
				build_xml_page($data, $title);		
				header('Location: '.BASEURL.'tmp/'.$title.'.xml');
			}
			header('Content-type: text/xml');
			if($type=='backend') echo $xmlout; //xml backend access for nagios fusion 
				#$output = build_xml_data($data, $title);
		break;

	}
	print $output;

}




function mode_header($mode)
{
	$retval = '';
	switch($mode)
	{
		case 'html':
		default:
			$page_title = 'Nagios Visual Shell';
			include(DIRBASE.'/views/header.php');  //html head 
			$retval = display_header($page_title);
		break;
	}
	return $retval;
}

function mode_footer($mode)
{
	$retval = '';
	switch($mode)
	{
		case 'html':
		default:
			include(DIRBASE.'/views/footer.php');  //html head 
			$retval = display_footer();
		break;
	}
	return $retval;
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







?>
