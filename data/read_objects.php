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
function parse_objects_file($objfile = OBJECTSFILE) {

	$objs_file = fopen($objfile, "r") or die("Unable to open '$objfile' file!");
	
	$defmatches = array();
	$curdeftype = NULL;
	$kvp = array();
	$object_collector = array();
	
	while(!feof($objs_file)) //read through the file and read object definitions
	{
	
		//var_dump($line)."<br />";
		$line = fgets($objs_file); //Gets a line from file pointer.
	
	if (preg_match('/^\s*define\s+(\w+)\s*{\s*$/', $line, $defmatches)) {
		// Beginning of a new definition;
		$curdeftype = $defmatches[1];
	
	} elseif (preg_match('/^\s*}\s*$/', $line)) {
		// End of a definition.  Assign key-value pairs and reset variables
		$object_collector[$curdeftype][] = $kvp;
		$curdeftype = NULL;
		$kvp = array();
	
	} elseif($curdeftype != NULL) {
		// Collect the key-value pairs for the definition
		list($key, $value) = explode("\t", trim($line), 2);
		$kvp[$key] = $value;
	
	} else {
		// outside of definitions? Comments and whitespace should be caught
	}
	      
	} //end of while
	
	fclose($objs_file);	
	
	$return_array = array();
	
	foreach (array('host', 'service', 'hostgroup', 'servicegroup', 
	  'contact', 'contactgroup', 'timeperiod', 'command') as $name) {
		$return_array[]= $object_collector[$name];
	}
	
	return $return_array;
}

?>
