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

	$objs_file = fopen($objfile, "r") or die("Unable to open '$objfile' file!");
	
	$defmatches = array();
	$curdeftype = NULL;
	$kvp = array();
	$object_collector = array();
	
	while(!feof($objs_file)) //read through the file and read object definitions
	{
		$line = fgets($objs_file); //Gets a line from file pointer.
	
	if (preg_match('/^\s*define\s+(\w+)\s*{\s*$/', $line, $defmatches)) {
		// Beginning of a new definition;
		$curdeftype = $defmatches[1];
	
	} elseif (preg_match('/^\s*}\s*$/', $line)) {
		// End of a definition.  Assign key-value pairs and reset variables
		switch($curdeftype){
			case 'host':
			case 'servicegroup':
			$object_collector[typemap($curdeftype)][$kvp[$curdeftype.'_name']] = $kvp;
			break;

			case 'service':
			$object_collector[typemap('host')][$kvp['host_name']]['services'][] = $kvp;
			$object_collector[typemap($curdeftype)][] = $kvp;
			break;

			default:
			$object_collector[typemap($curdeftype)][] = $kvp;
			break;
		}

		$curdeftype = NULL;
		$kvp = array();
	
	} elseif($curdeftype != NULL) {
		// Collect the key-value pairs for the definition
		list($key, $value) = explode("\t", trim($line), 2);
		$kvp[trim($key)] = trim($value);
	
	} else {
		// outside of definitions? Comments and whitespace should be caught
	}
	      
	} //end of while
	
	fclose($objs_file);	

	$object_collector['hostgroups'] = build_group_array($object_collector['hostgroups_objs'], 'host');
	$object_collector['servicegroups'] = build_group_array($object_collector['servicegroups_objs'], 'service');

	return $object_collector;
}

function typemap($type)
{
  $retval = NULL;
  if (in_array($type, array('host', 'service', 'hostgroup', 'servicegroup'))) {
	$retval = $type.'s_objs';
  } elseif (in_array($type, array('contact', 'contactgroup', 'timeperiod', 'command'))) {
	$retval = $type.'s';
  } else { // TODO other types?  
  }

  return $retval;
}

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
			array_walk($lineitems, create_function('$v', '$v = trim($v);'));
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
					$group_members[] = array(
						'host_name' => $host,
						'service_description' => $service);

				}
			}

			$membersArray[$group] = $group_members;
		}
	}
	//ARRAY MEMBERS NEED SPACES TRIMMED!!!!!!!! 
	return $membersArray;
}

?>
