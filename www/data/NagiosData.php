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
		//echo "VAR IS: $var<br />"; 
		if($var=='hostgroups') { //build hostgroup data			 
				$this->properties['hostgroups'] = build_group_array($this->properties['hostgroups_objs'], 'host');
				foreach($this->properties['services'] as $service) {
					if(!isset($this->properties['hosts'][$service['host_name']]['services'])) 
						$this->properties['hosts'][$service['host_name']]['services'] = array(); 
					$this->properties['hosts'][$service['host_name']]['services'][] = $service;	
				}
		}
		//build servicegroup data 
		if($var=='servicegroups')				
				$this->properties['servicegroups'] = build_group_array($this->properties['servicegroups_objs'], 'service');
						
		if (isset($this->properties[$var]))			
			$retval = $this->properties[$var];

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
		if ($type == 'host')	{
			if(isset($details[$arg]))
				$retval = $details[$arg];
			else { //character replacement search 
				foreach($details as $hostname => $array) {
					if(strtolower($hostname) == $arg) {
						$retval = $array;
						break;
					}//end IF 
				}//end foreach 
			}//end ELSE 	
		}//end IF host 

		return $retval;
	}

	// A private constructor; prevents direct creation of object
	function __construct()
	{
		$objects_are_cached = false;
		$status_is_cached	= false;
		$perms_are_cached=false;
		$apc_exists = false;
	
		//if apc exists
		if(function_exists('apc_fetch')) {
			$apc_exists = true;
			//echo "APC EXISTS!<br />";
			if(isset($_GET['clearcache']) && htmlentities($_GET['clearcache'],ENT_QUOTES)=='true') {
              apc_clear_cache('user');
              apc_clear_cache('opcode');
              //echo "CACHE CLEARED!<br />";	
			}	
			//see what data is available 
			list($objects_are_cached,$status_is_cached,$perms_are_cached) = $this->use_apc_data();
			if($objects_are_cached) {
				//echo "objects are cached<br />";
				$this->get_data_from_apc('objects');
			}	
			if($status_is_cached) {
			   //echo "status is cached<br />"; 
				$this->get_data_from_apc('status');	 
			}	
			if($perms_are_cached) {
			    //echo "perms is cached<br />";
				$this->get_data_from_apc('permissions');
			}		 	
		} 
		//fetch any data that isn't cached 
		if(!$status_is_cached || !$objects_are_cached || !$perms_are_cached)
			$this->raw_file_parse($objects_are_cached,$perms_are_cached,$apc_exists); 
			
		//echo "MEMORY PEAK: ".memory_get_peak_usage()."<br />";	
 
	}
	
	private function raw_file_parse($objects_are_cached = false,$perms_are_cached=false,$apc_exists = false) 
	{
		//echo "RAW PARSE<br />"; 		
		if(!$objects_are_cached) 
		{
			//objects.cache data 
			list( $this->properties['hosts_objs'], 
					$this->properties['services_objs'], 
				   $this->properties['hostgroups_objs'], 
				   $this->properties['servicegroups_objs'], 
				   $this->properties['timeperiods'],
				   $this->properties['commands'],
				   $this->properties['contacts'],
					$this->properties['contactgroups'],				 
					$this->properties['serviceescalations'],				 
					$this->properties['hostescalations'],
					$this->properties['hostdependencys'],
					$this->properties['servicedependencys']) = parse_objects_file();
					
			if($apc_exists)		
			   $this->set_data_to_apc('objects');		
																		
		}				
		//status.dat data always gets parsed if this function is called  
		list($this->properties['hosts'],
			$this->properties['services'],
			$this->properties['hostcomments'],
			$this->properties['servicecomments'],
			$this->properties['program'],
			$this->properties['info']) = parse_status_file();  
		if($apc_exists) {
			//echo "SAVING STATUS TO CACHE...<BR />"; 			
			$this->set_data_to_apc('status');				
		}		
			
		//grab perms if they need to be updated 
		if(!$perms_are_cached)	{
			$this->properties['permissions'] = parse_perms_file();
         if($apc_exists)
            $this->set_data_to_apc('permissions');
		}		
	}//end raw_file_parse() 
	
	private function use_apc_data() {
	
		$use_apc_objects  = false;
		$use_apc_status = false;
		$use_apc_perms = false;
		
		//is there is APC object data? 
		if(apc_fetch('object_data_exists'))  {
			$objects_filemtime = apc_fetch('last_objects_mtime'); 
//			echo $objects_filemtime."<br />";
//			echo filemtime(OBJECTSFILE);
			//if objects.cache has been updated since last cached value, re-read all data
			if((filemtime(OBJECTSFILE) == $objects_filemtime)) 					
				$use_apc_objects = true;			
		}
		//apc status data? 
		if(apc_fetch('status_data_exists'))
			$use_apc_status = true;
			
		//apc CGI data?
		if(apc_fetch('cgi_data_exists'))  {
			$filemtime = apc_fetch('last_cgi_mtime'); 
			//if objects.cache has been updated since last cached value, re-read all data
			if($filemtime && (filemtime(CGICFG) == $filemtime)) 					
				$use_apc_perms = true;			
		}
		
		return array($use_apc_objects,$use_apc_status,$use_apc_perms);
	
	}
	
	private function get_data_from_apc($type) 
	{
		
		if($type=='objects') {
			//echo "Gettign object data from cache<br />"; 
			//set object data from cache
			$this->properties['hosts_objs'] = apc_fetch('hosts_objs'); 
			$this->properties['services_objs'] = apc_fetch('services_objs'); 
			$this->properties['hostgroups_objs'] = apc_fetch('hostgroups_objs'); 
			$this->properties['servicegroups_objs'] = apc_fetch('servicegroup_objs');
			$this->properties['timeperiods'] = apc_fetch('timeperiods');
			$this->properties['commands'] = apc_fetch('commands');
			$this->properties['contacts'] = apc_fetch('contacts');
			$this->properties['contactgroups'] = apc_fetch('contactgroups');				 
			$this->properties['serviceescalations'] = apc_fetch('serviceescalations');				 
			$this->properties['hostescalations'] = apc_fetch('hostescalations');
			$this->properties['hostdependencys'] = apc_fetch('hostdependencys');
			$this->properties['servicedependencys'] = apc_fetch('servicedependencys');

		}
		
		if($type=='status') {
			//echo "Getting status data from cache<br />";
			//set data from status cache
			$this->properties['hosts'] = apc_fetch('hosts');
			$this->properties['services'] = apc_fetch('services');
			$this->properties['hostcomments'] = apc_fetch('hostcomments');
			$this->properties['servicecomments'] = apc_fetch('servicecomments');
			$this->properties['program'] = apc_fetch('program');
			$this->properties['info'] = apc_fetch('info');
		}	
		
		if($type=='permissions') {//set data from cgi.cfg cache
			$this->properties['permissions'] = apc_fetch('permissions');
			//echo "Getting cgi data from cache<br />";
		}		
	}
	
	private function set_data_to_apc($type) 
	{
		if($type=='objects') {
			//echo "Saving object data to cache<br />";
			//set object data from cache
			apc_store('hosts_objs',$this->properties['hosts_objs']); 
			apc_store('services_objs',$this->properties['services_objs']); 
			apc_store('hostgroups_objs',$this->properties['hostgroups_objs']); 
			apc_store('servicegroup_objs',$this->properties['servicegroups_objs']);
			apc_store('timeperiods',$this->properties['timeperiods']);
			apc_store('commands',$this->properties['commands']);
			apc_store('contacts',$this->properties['contacts']);
			apc_store('contactgroups',$this->properties['contactgroups']);				 
			apc_store('serviceescalations',$this->properties['serviceescalations']);				 
			apc_store('hostescalations',$this->properties['hostescalations']);
			apc_store('hostdependencys',$this->properties['hostdependencys']);
			apc_store('servicedependencys',$this->properties['servicedependencys']);
			apc_store('object_data_exists',true);
			apc_store('last_objects_mtime',filemtime(OBJECTSFILE)); 
		}
		
		if($type=='status') {
			//echo "Saving status data to cache<br />";
			//set data from status cache
			apc_store('hosts',$this->properties['hosts'],TTL);
			apc_store('services',$this->properties['services'],TTL);
			apc_store('hostcomments',$this->properties['hostcomments'],TTL);
			apc_store('servicecomments',$this->properties['servicecomments'],TTL);
			apc_store('program',$this->properties['program'],TTL);
			apc_store('info',$this->properties['info'],TTL);
			apc_store('status_data_exists',true,TTL);
		}	
		
		if($type=='permissions') {
			//echo "Saving cgi data to cache<br />";
			//set data from cgi.cfg cache
			apc_store('permissions',$this->properties['permissions']);
			apc_store('last_cgi_mtime',filemtime(CGICFG));
			apc_store('cgi_data_exists',true);
		}	
	}//end set_data_to_apc

}

?>