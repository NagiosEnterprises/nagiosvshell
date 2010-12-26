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

	protected static $property_list = array('hosts_objs', 'services_objs', 
		'hostgroups_objs', 'servicegroups_objs', 'contacts', 'contactgroups', 
		'timeperiods', 'commands', 'hosts', 'services', 'comments', 'info',
		'details', 'permissions', 'hostgroups', 'servicegroups');

	/*  Return, and build as necessary, the singleton to store nagios data
	 *
	 * TODO:  Check APC cache for object
	 *        Modify __update()/cache_or_disk to only update data if it
	 *         out of date.
	 */
	public static function singleton() 
	{
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		self::$instance->__update();
		return self::$instance;
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
		
		if (self::_is_valid_variable($var)) {
			$retval = $this->_vars[$var];
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
		$details = self::$instance->getProperty('details'); 
		return $details[$type];
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
			$details = self::$instance->grab_details($type);
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
		elseif ($type == 'host')
		{
			foreach($details as $host_details)
			{
				if($host_details['host_name'] == $arg) 
				{
					$retval = $host_details;
					break;
				}
			}
		}
		return $retval;
	}

	// A private constructor; prevents direct creation of object
	private function __construct() {
		$this->_vars = array();
	}

	/* When a new singleton is requested make sure the contained data is up 
	 * to date
	 *
	 * This wraps what used to live in data.inc.php and loads all of the
	 *  appropriate properties from cache or disk
	 *
	 * TODO: Cache object between requests and then simply check to see if 
	 *        the cache needs to be updated because of a change on disk.  
	 *        This will be a massive performance increase
	 *
	 */
	private function __update() 
	{
		$disk_cache_keys = array('hosts_objs', 'services_objs', 
			'hostgroups_objs', 'servicegroups_objs', 'contacts', 
			'contactgroups', 'timeperiods', 'commands', 'hostgroups', 
			'servicegroups');
		self::$instance->_set_vars(cache_or_disk('objects', OBJECTSFILE, 
			$disk_cache_keys));

		self::$instance->_set_vars(cache_or_disk('perms', CGICFG, 
			array('permissions')));

		self::$instance->_set_vars(cache_or_disk('status', STATUSFILE, 
			array('hosts', 'services', 'comments', 'info', 'details')));

	}

	private static function _is_valid_variable($var) {
		return in_array($var, NagiosData::$property_list);
	}

	private function _setProperty($var, $value) {
		if (self::_is_valid_variable($var)) {
			$this->_vars[$var] = $value;
		} else {
			// XXX Do something better here
			//fb($var, "Invalid property");
		}
	}

	/* Helper function to store data returned from cache_or_disk(...) to 
	 *   properties
	 *  
	 * Given an array of the form array('property_name' => property_data, ...)
	 *  set the property called property name to property_data
	 */
	private function _set_vars($array) 
	{
		foreach (array_keys($array) as $key)
		{
			self::$instance->_setProperty($key, $array[$key]);
		}
	}
	
	// Prevent users to clone the instance since this is a singleton
	public function __clone()
	{
		trigger_error('Singleton', E_USER_ERROR);
	}

	// Storage for all necessary variables.  Replaces the many globals
	protected $_vars;
}

?>
