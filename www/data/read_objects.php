<?php //script reads objects.cache file and builds objects arrays from file 


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
//define('OUTOFBLOCK',0);
//define('HOSTDEF',1);
//define('SERVICEDEF',2);
//define('PROGRAM',3);
//define('INFO',4);
//define('HOSTCOMMENT',5);
//define('SERVICECOMMENT',6); 

/*  Open and parse the Nagios objects file.
 *
 *  Returns an array of the following arrays:
 *
 * $hosts_objs
 * $services_objs
 * $hostgroups_objs
 * $servicegroups_objs
 * $contacts
 * $contactgroups
 * $timeperiods
 * $commands
 */
function parse_objects_file($objfile = OBJECTSFILE)
{
	$file = fopen($objfile, "r") or die("Unable to open '$objfile' file!");
	$in_block = false; 	
	$matches = array();
	$object_type = NULL;
	$host_name = ''; 
	$serviceID = 0; 
	
	$objects = array(   'host' => array(),
						'service' => array(),
						'hostgroup' => array(),
						'servicegroup' => array(),
						'timeperiod' => array(),
						'command' => array(),
						'contact' => array(),
						'contactgroup' => array(),
						'serviceescalation' => array(),
						'hostescalation' => array(),
						'hostdepencency' => array(),
						'servicedependency' => array(),
					);
					
	$counters = array(  'host' => 0,
						'service' => 0,
						'hostgroup' => 0,
						'servicegroup' => 0,
						'timeperiod' => 0,
						'command' => 0,
						'contact' => 0,
						'contactgroup' => 0,
						'serviceescalation' => 0,
						'hostescalation' => 0,
						'hostdepencency' => 0,
						'servicedependency' => 0,
					);
	

		
	while(!feof($file)) //read through the file and read object definitions
	{
		$line = fgets($file); //Gets a line from file pointer.
		
		if($in_block) {
			if (strpos($line,'}') !== FALSE) { 
				$in_block = false; //end of block 
				//add a service id 
				if($object_type ==' service')	
					$objects[$object_type][$counters[$object_type] ]['service_id'] = $serviceID++;
				//increment type counter 
				if($object_type != 'host')
					$objects[$object_type][$counters[$object_type]++]; 

				continue;
			}		
			else {
				// Collect the key-value pairs for the definition
				@list($key, $value) = explode("\t", trim($line), 2);
				//$objects['host'][0]['host_name'] = 'thename';
				if($object_type=='host') {
					if($key == 'host_name') //index array by hostname 
						$host_name = $value; 
					$objects[$object_type][$host_name][trim($key)] = trim($value); 
				
				}
				//create unique service ID 
				//if($object_type=='service')
					//$objects[$object_type][$counters[$object_type] ]['service_id'] = $serviceID; 
										
				else //all other objects  
					$objects[$object_type][$counters[$object_type] ][trim($key)] = trim($value);
			} 
		}
		else {  //outside of a block 
			if(preg_match('/^\s*define\s+(\w+)\s*{\s*$/', $line, $matches)) {
				$object_type = $matches[1];
				$in_block = true;
				continue;
			}	

		}
	      
	} //end of while
	
	fclose($file);	

	//only do this for group and details page
//	$object_collector['hostgroups'] = build_group_array($object_collector['hostgroups_objs'], 'host');
//	$object_collector['servicegroups'] = build_group_array($object_collector['servicegroups_objs'], 'service');

	return array(  $objects['host'],
						$objects['service'],
						$objects['hostgroup'],
						$objects['servicegroup'],
						$objects['timeperiod'],
						$objects['command'],
						$objects['contact'],
						$objects['contactgroup'],
						$objects['serviceescalation'],
						$objects['hostescalation'],
						$objects['hostdepencency'],
						$objects['servicedependency'],
					);
}

//$objects = parse_objects_file('/usr/local/nagios/var/objects.cache'); 
//print "<pre>".print_r($objects,true)."</pre>";
//die(); 

/*
function typemap($type)
{
  $retval = NULL;
  if (in_array($type, array('host', 'service', 'hostgroup', 'servicegroup'))) {
	$retval = $type.'s_objs';
  } elseif (in_array($type, array('contact', 'contactgroup', 'timeperiod', 'command','hostescalation','serviceescalation','hostdependency','servicedependency'))) {
	$retval = $type.'s';
  } else { // TODO other types?  
  }

  return $retval;
}
*/


?>