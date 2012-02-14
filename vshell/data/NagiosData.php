<?php // Object to encapsulate all of the old global variables

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


class NagiosData
{
	// Hold an instance of the class
	private static $instance;
	
	// Storage for all necessary variables.  Replaces the many globals
	protected $_vars;

	protected static $properties = array('hosts_objs', 'services_objs', 
		'hostgroups_objs', 'servicegroups_objs', 'contacts', 'contactgroups', 
		'timeperiods', 'commands', 'hosts', 'services', 'comments', 'info',
		'details', 'permissions', 'hostgroups', 'servicegroups', 'program',
		'hostescalations','serviceescalations','hostdependencys','servicedependencys');

	
	public function dumpVars()
	{
		return $this->properties; 
	}

	/* General purpose "getter" for protected properties
	 *
	 * $var is the old global variable name to retrive
	 * 
	 * returns the old global variable now stored as a property
	 *   *or* NULL if the requested name is an invalid property
	 *
	 */
	public function getProperty($var) 
	{
		$retval = NULL;
		
		if (isset($this->properties[$var])) {
			$retval = $this->properties[$var];
		}

		return $retval;
	}

	/*  status detail arrays for application use 
	 *
	 *  Example Usage: $hostdetails = grab_details('host');
	 *                 $servicedetails = grab_details('service')
	 */
	public function grab_details($type) 
	{
		//fb("grab_details({$type})");
		$details = $this->getProperty($type.'s'); 
		return $details;
	}

	/* 
	 * this function grabs all status details from an individual host 
	 *   or service 
	 *
     *  $type = 'host' 'service' 'program' 
     *  $arg = index of host or service array, starting at 1 	
	 * 
	 *  returns: array(status details of a single host or service) 
	 *
	 *  Example usage: $host_a = get_details_by('service', 'service4');
	 */
	public function get_details_by($type, $arg)
	{
		$arg = trim($arg);
		$retval = NULL;

		$details = NULL;
		if (in_array($type, array('service', 'host'))) {
			$details = $this->grab_details($type);
		} else { 
			// XXX Do soemthing better here
			//fb("invalid type '$type'"); 
		}

		if ($type == 'service')
		{
			/* serviceID index no longer exists, had to call array by index 
			 * number instead 
			 */
			$id = str_replace('service', '', $arg); 
			$retval = $details[$id];	//call service details by array index 
	
		}
		if ($type == 'host')	
			$retval = $details[$arg]; 


		return $retval;
	}

	// A private constructor; prevents direct creation of object
	function __construct()
	{
		//objects.cache data 
		list($this->properties['hosts_objs'], $this->properties['services_objs'], 
		$this->properties['hostgroups_objs'], $this->properties['servicegroups_objs'], $this->properties['contacts'],
		$this->properties['contactgroups'],$this->properties['timeperiods'], $this->properties['commands'], 
		$this->properties['hostescalations'],$this->properties['serviceescalations'],
		$this->properties['hostdependencys'],$this->properties['servicedependencys']) = parse_objects_file();
		
		//status.dat data  
		list($this->properties['hosts'],
			$this->properties['services'],
			$this->properties['hostcomments'],
			$this->properties['servicecomments'],
			$this->properties['program'],
			$this->properties['info']) = parse_status_file();  
			
		//todo hostgroups, servicegroups, permissions
		$this->properties['permissions'] = parse_perms_file(); 
	}




}

?>
