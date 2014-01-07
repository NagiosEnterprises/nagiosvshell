<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Status extends CI_Controller {

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

	var $state_filter; 
	var $name_filter;
	var $type;
	var $mode;
	var $host_filter;
	var $objtype_filter;
	
	
	public function __construct() {
        parent::__construct();
        //require_install();
        //require_auth();
        //require_agreement();
        //$this->load->model('cache_or_disk');
        $this->load->model('nagios_user');
        $this->load->model('nagios_data');
        $this->load->model('tac_data');
        $this->load->helper('data_utils');
        $this->load->helper('display_functions');
		$this->load->helper('output_functions');
		$this->load->helper('status_functions');
		$this->load->helper('process_details');
		$this->load->helper('output_functions');

    }

	
	public function index()	{
		
		$data = $this->tac_data->get_tac_data();
		$this->load->view('header');
		$this->load->view('tac', $data);
		$this->load->view('footer');
	}
	
	
	public function overview() {
					//create function to return tac data as an array 
				$html_output_function = 'get_tac_html';	
		
	}	
	
	public function hosts() {
		$state_filter    = process_state_filter(htmlentities($_GET['state_filter']));
			$name_filter     = process_name_filter(htmlentities($_GET['name_filter']),ENT_QUOTES); 
					$data = hosts_and_services_data($type, $state_filter, $name_filter, $host_filter);
					$html_output_function = 'hosts_and_services_output';		
	}
	
	public function services() {
		$state_filter    = process_state_filter(htmlentities($_GET['state_filter']));
		$name_filter     = process_name_filter(htmlentities($_GET['name_filter']),ENT_QUOTES); 
		$host_filter = htmlentities($_GET['host_filter'],ENT_QUOTES);  
						$data = hosts_and_services_data($type, $state_filter, $name_filter, $host_filter);
					$html_output_function = 'hosts_and_services_output';	
					
		
	}
	
	
	public function hostdetail() {
						$data = host_and_service_detail_data($type, $name_filter);
					if(!$data) send_home(); //bail if not authorized  
					$html_output_function = 'host_and_service_detail_output';		
	}
	
	
	public function servicedetail() {
						$data = host_and_service_detail_data($type, $name_filter);
					if(!$data) send_home(); //bail if not authorized  
					$html_output_function = 'host_and_service_detail_output';		
	}
	
	public function hostgroups() {
						$data = hostgroups_and_servicegroups_data($type, $name_filter);
					$html_output_function = 'hostgroups_and_servicegroups_output';	
	}
	
	public function servicegroups() {
						$data = hostgroups_and_servicegroups_data($type, $name_filter);
					$html_output_function = 'hostgroups_and_servicegroups_output';	
		
	}	
	
	public function objectdetail() {
			$objtype_filter  = process_objtype_filter(htmlentities($_GET['objtype_filter']));
			if($NagiosUser->if_has_authKey('authorized_for_configuration_information')) //only administrative users should be able to see config info 
			{
				$data = object_data($objtype_filter, $name_filter);
				$type = $objtype_filter;
				$html_output_function = 'object_output';
			}			
	}	
	
	public function backend() {
		
		
		$xmlout = tac_xml(get_tac_data());
		
		
		
		
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
	
	                case 'jsonp':
	                        if (isset($_GET['callback']) && is_valid_callback($_GET['callback'])) { $callback_name = $_GET['callback']; }
	                        if (isset($_GET['jsonp']) && is_valid_callback($_GET['jsonp']) ) { $callback_name = $_GET['jsonp']; }
	                        if (!isset($callback_name)) { $callback_name = 'callback'; }
	
	                        header('Content-type: application/json-p');
	                        $output = $callback_name . '(' . json_encode($data) . ');';
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
	}
	
	
	
	
	
	public function mode_header($mode)
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
	
	public function mode_footer($mode)
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
	
	
	
	public function get_pagination_values()
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
	
	public function is_valid_callback($subject)
	{
	    $identifier_syntax
	      = '/^[$_\p{L}][$_\p{L}\p{Mn}\p{Mc}\p{Nd}\p{Pc}\x{200C}\x{200D}]*+$/u';
	
	    $reserved_words = array('break', 'do', 'instanceof', 'typeof', 'case',
	      'else', 'new', 'var', 'catch', 'finally', 'return', 'void', 'continue', 
	      'for', 'switch', 'while', 'debugger', 'function', 'this', 'with', 
	      'default', 'if', 'throw', 'delete', 'in', 'try', 'class', 'enum', 
	      'extends', 'super', 'const', 'export', 'import', 'implements', 'let', 
	      'private', 'public', 'yield', 'interface', 'package', 'protected', 
	      'static', 'null', 'true', 'false');
	
	    return preg_match($identifier_syntax, $subject)
	        && ! in_array(mb_strtolower($subject, 'UTF-8'), $reserved_words);
	}
}

/* End of file status.php */
/* Location: ./application/controllers/status.php */
