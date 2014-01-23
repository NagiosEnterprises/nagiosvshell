<?php //read_status.php     This script processes status.dat file and assigns hosts and services into arrays


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

//switch constants
define('OUTOFBLOCK',0);
define('HOSTDEF',1);
define('SERVICEDEF',2);
define('PROGRAM',3);
define('INFO',4);
define('HOSTCOMMENT',5);
define('SERVICECOMMENT',6); 


/* TODO 
 * - create status arrays for hostgroups and servicegroups 
 */

/* 	Parse STATUSFILE for status information, nagios information, as well as 
 * 	build the details array and collect comments
 *	modified and stripped down to only capture raw data for authorized objects, process values later
 */
function parse_status_file($statusfile = STATUSFILE)
{
	$file = fopen($statusfile, "r") or die("Unable to open '$statusfile' file!");
	
	if(!$file) die("File '$statusfile' not found!");
	
	$hoststatus = array(); 
	$servicestatus = array();
	$hostcomments = array();
	$servicecomments = array(); 
	$programstatus = array(); 
	$info = array(); 
	
	//counters for iteration through file 	
	$case = OUTOFBLOCK;
	$service_id=0;
	$s_comment_id = 0;
	$h_comment_id = 0;
	$hostkey = '';
	//keywords for string match 
	$hoststring = 'hoststatus {';
	$servicestring = 'servicestatus {'; 
	$hostcommentstring = 'hostcomment {';
	$servicecommentstring = 'servicecomment {';
	$programstring = 'programstatus {';
	$infostring = 'info {';
	//begin parse	
	while(!feof($file)) //read through file and assign host and service status into separate arrays 
	{	
		$line = fgets($file); //Gets a line from file pointer.
						
		//////////////////////////NEW REVISION//////////////////////////
		if($case==OUTOFBLOCK) {
			//host
			if(strpos($line,$hoststring)!==false) {	
				$case = HOSTDEF; //enable grabbing of host variables
				//unset($hostkey);
				continue; 
			}	
			//service
			if(strpos($line,$servicestring)!==false) {
				$case = SERVICEDEF; //enable grabbing of service variables
				$servicestatus[$service_id] = array(); 
				continue;
			}
			//hostcomment
			if(strpos($line,$hostcommentstring)!==false) {	
				$case = HOSTCOMMENT; //enable grabbing of host variables
				//unset($hostkey);
				continue; 
			}
			//service
			if(strpos($line,$servicecommentstring)!==false) {
				$case = SERVICECOMMENT; //enable grabbing of service variables
				$s_comment_id++; 
				continue;
			}
			//program status 
			if(strpos($line,$programstring)!==false) {
				$case = PROGRAM; 
				continue;
			}			
			//info
			if(strpos($line,$infostring)!==false) {
				$case = INFO; 
				continue;
			}		
		} //end OUTOFBLOCK IF 
		//end of definition 
		if(strpos($line, '}') !==false) {
			if($case == SERVICEDEF) 
				$service_id++; 
			if($case == SERVICECOMMENT)
			   $s_comment_id++;	
			if($case == HOSTCOMMENT)
			   $h_comment_id++;	 
			$case = OUTOFBLOCK; //turn off switches once a definition ends 		
			continue;
		}
		//capture key / value pair 
		list($key,$value) = get_key_value($line);
		
		//grab variables according to the enabled boolean switch	
		switch($case) 
		{					
			case HOSTDEF:
			//do something
				if($key=='host_name' && !isset($hoststatus[$value])) {
					$hostkey = $value;
					$hoststatus[$hostkey] = array();
				}					 
				$hoststatus[$hostkey][$key]= $value;				
			break;
			
			case SERVICEDEF:  					 
				$servicestatus[$service_id][$key]= $value;	
				$servicestatus[$service_id]['service_id']= $service_id;	 				
			break;
			
			case HOSTCOMMENT:
				if(!isset($hostcomments[$h_comment_id])) 
					$hostcomments[$h_comment_id] = array();
														 
				$hostcomments[$h_comment_id][$key]= $value;				
			break;
			
			case SERVICECOMMENT:				 
				$servicecomments[$s_comment_id][$key]= $value;			
			break;
			
			case INFO:
				$info[$key] = $value; 
			break;
			
			case PROGRAM:				
				$programstatus[$key] = $value; 
			break;
			
			case OUTOFBLOCK:
			default:
				//switches are off, do nothing 
			break;			
		}	//end of switch 	
		
		
	}//end of WHILE 
	
	fclose($file);
	
	return array($hoststatus, 
					$servicestatus, 
					$hostcomments,
					$servicecomments,
					$programstatus,
					$info, 
		);
}

/*
function get_key_value($line) {
	$strings = explode('=', $line,2);			
	$key = isset($strings[0]) ? trim($strings[0]) : '';
	$value = isset($strings[1]) ? trim($strings[1]) : '';
	return array($key,$value); 

}
*/


//$data = parse_status_file('/usr/local/nagios/var/status.dat'); 
//print "<pre>".print_r($data,true)."</pre>";
//die();  
?>