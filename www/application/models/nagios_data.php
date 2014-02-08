<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Object to encapsulate all of the old global variables

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

class Nagios_data extends CI_Model
{
    //Object based 
    protected $_HostCollection;
    protected $_ServiceCollection;
    protected $_HostgroupCollection;
    protected $_ContactCollection;
    protected $_ContactgroupCollection;

    protected $_ServicegroupCollection;

    protected $_TimeperiodCollection;
    protected $_CommandCollection;
    protected $_HostescalationCollection;
    protected $_ServiceescalationCollection;
    protected $_HostdependencyCollection;
    protected $_ServicedependencyCollection;

    // Status based collections
    protected $_HoststatusCollection;
    protected $_ServicestatusCollection;
    protected $_HostgroupstatusCollection;
    protected $_ServicegroupstatusCollection;

    protected $_Programstatus; 

    //Various status collections 
    protected $_HostcommentCollection;
    protected $_ServicecommentCollection;
    protected $_Info;


 //   protected $_DetailsCollection; // ?? What is this??
    protected $_PermissionsCollection;

    private $_map = array();




    protected $properties = array(
        'hosts_objs',
        'services_objs',
        'hostgroups_objs',
        'servicegroups_objs',
        'contacts',
        'contactgroups',
        'timeperiods',
        'commands',


        'program',
        'hostescalations',
        'serviceescalations',
        'hostdependencys',
        'servicedependencys',


        'hostgroups',
        'servicegroups',
        'hosts',
        'services',
        'comments',
        'info',
       // 'details',
        'permissions',

    );




    // A private constructor; prevents direct creation of object
    // TODO: add private keyword here?
    public function __construct()
    {
        parent::__construct();
        //$this->load->model('nagios_user');
        
        $this->_map_collections();

        $objects_are_cached = false;
        $status_is_cached	= false;
        $perms_are_cached=false;
        $apc_exists = false;

        //if apc exists
        if (false || function_exists('apc_fetch')) {
            $apc_exists = true;
            if (isset($_GET['clearcache']) && htmlentities($_GET['clearcache'],ENT_QUOTES)=='true') {
                apc_clear_cache('user');
                apc_clear_cache('opcode');
            }

            //see what data is available
            list($objects_are_cached,$status_is_cached,$perms_are_cached) = $this->use_apc_data();
            if ($objects_are_cached) {
                $this->get_data_from_apc('objects');
            }

            if ($status_is_cached) {
                $this->get_data_from_apc('status');
            }

            if ($perms_are_cached) {
                $this->get_data_from_apc('permissions');
            }
        }

        //fetch any data that isn't cached
        if (!$status_is_cached || !$objects_are_cached || !$perms_are_cached) {
            $this->raw_file_parse($objects_are_cached,$perms_are_cached,$apc_exists);
        }
    }

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

        //build hostgroup data
        if ($var == 'hostgroups') {
            $this->properties['hostgroups'] = $this->build_group_array($this->properties['hostgroups_objs'], 'host');
            foreach ($this->properties['services'] as $service) {
                if (!isset($this->properties['hosts'][$service['host_name']]['services'])) {
                    $this->properties['hosts'][$service['host_name']]['services'] = array();
                }
                $this->properties['hosts'][$service['host_name']]['services'][] = $service;
            }
        }

        //build servicegroup data
        if ($var == 'servicegroups') {
            $this->properties['servicegroups'] = $this->build_group_array($this->properties['servicegroups_objs'], 'service');
        }

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
        }

        if ($type == 'service') {
            /* serviceID index no longer exists, had to call array by index
             * number instead
             */
            $id = str_replace('service', '', $arg);
            $retval = $details[$id];	//call service details by array index
        }

        if ($type == 'host') {
            if (isset($details[$arg])) {
                $retval = $details[$arg];
            } else {
                //character replacement search
                foreach ($details as $hostname => $array) {
                    if (strtolower($hostname) == $arg) {
                        $retval = $array;
                        break;
                    }
                }
            }
        }

        return $retval;
    }

    private function raw_file_parse($objects_are_cached = false, $perms_are_cached=false, $apc_exists = false)
    {
        if (!$objects_are_cached) {

            $this->parse_objects_file();

            if ($apc_exists) {
               $this->set_data_to_apc('objects');
            }

        }

        //status.dat data always gets parsed if this function is called
        $this->parse_status_file();

        if ($apc_exists) {
            $this->set_data_to_apc('status');
        }

        //grab perms if they need to be updated
        if (!$perms_are_cached) {
            $this->properties['permissions'] = $this->parse_perms_file();
            if ($apc_exists) {
                $this->set_data_to_apc('permissions');
            }
        }

    }

    private function use_apc_data()
    {

        //TURN OFF APC DURING DEVELOPMENT
        return FALSE;

        $use_apc_objects  = false;
        $use_apc_status = false;
        $use_apc_perms = false;

        //is there is APC object data?
        if (apc_fetch('object_data_exists')) {
            $objects_filemtime = apc_fetch('last_objects_mtime');
            //if objects.cache has been updated since last cached value, re-read all data
            if((filemtime(OBJECTSFILE) == $objects_filemtime))
                $use_apc_objects = true;
        }

        //apc status data?
        if (apc_fetch('status_data_exists')) {
            $use_apc_status = true;
        }

        //apc CGI data?
        if (apc_fetch('cgi_data_exists')) {
            $filemtime = apc_fetch('last_cgi_mtime');
            //if objects.cache has been updated since last cached value, re-read all data
            if ($filemtime && (filemtime(CGICFG) == $filemtime)) {
                $use_apc_perms = true;
            }
        }

        return array($use_apc_objects,$use_apc_status,$use_apc_perms);

    }

    private function get_data_from_apc($type)
    {


        die('APC IS TURND OFF DURING 2.x DEVELOPMENT');

        if ($type == 'objects') {
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

        if ($type == 'status') {
            //set data from status cache
            $this->properties['hosts'] = apc_fetch('hosts');
            $this->properties['services'] = apc_fetch('services');
            $this->properties['hostcomments'] = apc_fetch('hostcomments');
            $this->properties['servicecomments'] = apc_fetch('servicecomments');
            $this->properties['program'] = apc_fetch('program');
            $this->properties['info'] = apc_fetch('info');
        }

        if ($type == 'permissions') {
            //set data from cgi.cfg cache
            $this->properties['permissions'] = apc_fetch('permissions');
        }
    }

    private function set_data_to_apc($type)
    {


        return;


        if ($type == 'objects') {
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

        if ($type == 'status') {
            //set data from status cache
            apc_store('hosts',$this->properties['hosts'],TTL);
            apc_store('services',$this->properties['services'],TTL);
            apc_store('hostcomments',$this->properties['hostcomments'],TTL);
            apc_store('servicecomments',$this->properties['servicecomments'],TTL);
            apc_store('program',$this->properties['program'],TTL);
            apc_store('info',$this->properties['info'],TTL);
            apc_store('status_data_exists',true,TTL);
        }

        if ($type == 'permissions') {
            //set data from cgi.cfg cache
            apc_store('permissions',$this->properties['permissions']);
            apc_store('last_cgi_mtime',filemtime(CGICFG));
            apc_store('cgi_data_exists',true);
        }
    }

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
    private function parse_objects_file()
    {
        $file = fopen(OBJECTSFILE, "r") or die("Unable to open objects: '".OBJECTSFILE."' file!");
        $in_block = false;
        $matches = array();
        $object_type = NULL;
        $host_name = '';
        $serviceID = 0;

        //read through the file and read object definitions
        while ( !feof($file) ) {
            //Gets a line from file pointer.
            $line = fgets($file);

            if ($in_block) {
                if (strpos($line,'}') !== FALSE) {
                    //end of block
                    $in_block = false;

                    $Obj = NagiosObject::factory($object_type,$objectArray);
                    $this->_add($object_type,$Obj);

                    continue;

                } else {
                    // Collect the key-value pairs for the definition
                    @list($key, $value) = explode("\t", trim($line), 2);

                    $objectArray[$key] = $value;

                }
            } else {
                //outside of a block
                if (preg_match('/^\s*define\s+(\w+)\s*{\s*$/', $line, $matches)) {
                    $object_type = $matches[1];

                    $in_block = true;

                    $objectArray = array();
                    continue;
                }
            }
        }

        fclose($file);

    }

    /* TODO
     * - create status arrays for hostgroups and servicegroups
     */

    /*	Parse STATUSFILE for status information, nagios information, as well as
     *	build the details array and collect comments
     *	modified and stripped down to only capture raw data for authorized objects, process values later
     */
    private function parse_status_file()
    {
        $file = fopen(STATUSFILE, "r") or die("Unable to open status: '".STATUSFILE."' file!");

        if (!$file) {
            die("File '$statusfile' not found!");
        }

        //switch constants
        define('OUTOFBLOCK',0);
        define('HOSTDEF','hoststatus');
        define('SERVICEDEF','servicestatus');
        define('PROGRAM','programstatus');
        define('INFO','info');
        define('HOSTCOMMENT','hostcomment');
        define('SERVICECOMMENT','servicecomment');

        //counters for iteration through file
        $case = OUTOFBLOCK;

        //keywords for string match
        $hoststring = 'hoststatus {';
        $servicestring = 'servicestatus {';
        $hostcommentstring = 'hostcomment {';
        $servicecommentstring = 'servicecomment {';
        $programstring = 'programstatus {';
        $infostring = 'info {';

        $buf = array();

        //begin parse
        //read through file and assign host and service status into separate arrays
        while (!feof($file)) {
            $line = fgets($file); //Gets a line from file pointer.

            // NEW REVISION
            if ($case == OUTOFBLOCK) {

                //hoststatus
                if (strpos($line, $hoststring) !== false) {
                    $case = HOSTDEF;
                    continue;
                }

                //servicestatus
                if (strpos($line, $servicestring) !== false) {
                    $case = SERVICEDEF;
                    continue;
                }

                //hostcomment
                if (strpos($line, $hostcommentstring) !== false) {
                    $case = HOSTCOMMENT;
                    continue;
                }

                //servicecomment
                if (strpos($line, $servicecommentstring) !== false) {
                    $case = SERVICECOMMENT;
                    continue;
                }

                //program status
                if (strpos($line,$programstring) !== false) {
                    $case = PROGRAM;
                    continue;
                }

                //info
                if (strpos($line,$infostring) !== false) {
                    $case = INFO;
                    continue;
                }

            }


            //End definition
            if (strpos($line, '}') !==false) {
    
                //Only one info, doesn't need a collection
                if($case==INFO){
                    $this->_Info = new Info($buf);

                //Only one programstatus, doesn't need a collection     
                } elseif($case==PROGRAM){
                    $this->_Programstatus = new Programstatus($buf);

                //objectstatus collection or comment collection     
                }else {
                    $Status = NagiosObject::factory($case,$buf);
                    $this->_add($case,$Status);
                     unset($Status);
                }
               

                //turn off switches once a definition ends
                $case = OUTOFBLOCK;

                //clear the buffer
                $buf = array();

                continue;
            }


            //capture key / value pair
            list($key,$value) = get_key_value($line);
            $buf[$key] = $value;

        }

        fclose($file);
          
    }

    //returns array of authorization => users[array]
    private function parse_perms_file()
    {
        $cgi = fopen(CGICFG, "r") or exit("Unable to open cgi '".CGICFG."' file!");

        if (!$cgi) {
            die('cgi.cfg not found');
        }

        $keywords = array(  'host_commands',
                            'hosts',
                            'service_commands',
                            'services',
                            'configuration_information',
                            'system_commands',
                            'system_information',
                            'read_only'
        );

        $keyword_regex = '/('.join('|', $keywords).')/';

        //read through file and assign host and service status into separate arrays
        while (!feof($cgi)) {

            //Gets a line from file pointer.
            $line = fgets($cgi);

            if (!preg_match('/^\s*#/', $line) && preg_match($keyword_regex, $line, $keyword_matches)) {
                $perm = $keyword_matches[1];

                list($actual_perm, $userlist) = explode('=', trim($line), 2);

                $permusers = explode(',', $userlist);

                //XXX change this, create_function too slow and consumes HUGE memory
                //array_walk($permusers, create_function('&$v', 'trim($v);'));

                if (is_array($permusers)) {
                    array_walk($permusers,'trim');
                }

                $perms[$actual_perm] = $permusers; //XXX move all to NagiosUser in future versions
            }

        }

        fclose($cgi);

        return $perms;
    }

    //creates group array based on type
    //$objectarray - expecting an object group array -> $hostgroups_objs $servicegroups_objs $contactgroups
    //				-these groups are read from objects.cache file
    //$type - expecting 'host' 'service' or 'contact'
    public function build_group_array($objectarray, $type)
    {
        $membersArray = array();
        $index = $type.'group_name';

        foreach ($objectarray as $object) {
            $group = $object[$index];
            if (isset($object['members'])) {
                $members = $object['members'];
                $lineitems = explode(',', trim($members));

                //array_walk($lineitems, create_function('$v', '$v = trim($v);'));  //XXX BAD to use create_function
                array_walk($lineitems, 'trim');

                $group_members = NULL;
                if ($type == 'host' || $type == 'contact') {
                    $group_members = $lineitems;
                } elseif ($type == 'service') {
                    for ($i = 0; $i < count($lineitems); $i+=2) {
                        $host = $lineitems[$i];
                        $service = $lineitems[$i+1];
                        $group_members[$host][] = $service;

                    }
                }

                $membersArray[$group] = $group_members;
            }
        }

        return $membersArray;
    }



    private function _map_collections() {

        $this->_map = array(
            'host' => &$this->_HostCollection,
            'service' => &$this->_ServiceCollection,
            'hostgroup' => &$this->_HostgroupCollection,
            'servicegroup' => &$this->_ServicegroupCollection,
            'timeperiod' => &$this->_TimeperiodCollection,
            'command' => &$this->_CommandCollection,
            'contact' => &$this->_ContactCollection,
            'contactgroup' => &$this->_ContactgroupCollection,
            'serviceescalation' => &$this->_ServiceescalationCollection,
            'hostescalation' => &$this->_HostescalationCollection,
            'hostdependency' => &$this->_HostdependencyCollection,
            'servicedependency' => &$this->_ServicedependencyCollection,

            'hoststatus' => &$this->_HoststatusCollection,
            'servicestatus' => &$this->_ServicestatusCollection,
            'hostgroupstatus' => &$this->_HostgroupstatusCollection,
            'servicegroupstatus' => &$this->_ServicegroupstatusCollection,
            'hostcomment' => &$this->_HostcommentCollection,
            'servicecomment' => &$this->_ServicecommentCollection,
           // 'programstatus' => &$this->_Programstatus,
           // 'info'  => &$this->_Info,

        );

/** TEMPORARY MAP  */
        
        $this->properties['hosts_objs'] = &$this->_HostCollection;
        $this->properties['services_objs'] = &$this->_ServiceCollection;
        $this->properties['hostgroups_objs'] = &$this->_HostgroupCollection;
        $this->properties['servicegroups_objs'] = &$this->_ServicegroupCollection;
        $this->properties['contacts'] = &$this->_ContactCollection;
        $this->properties[ 'contactgroups'] = &$this->_ContactgroupCollection;
        $this->properties['timeperiods'] = &$this->_TimeperiodCollection;
        $this->properties['commands'] = &$this->_CommandCollection;

        $this->properties['hostescalations'] = &$this->_HostescalationCollection;
        $this->properties['serviceescalations'] = &$this->_ServiceescalationCollection;
        $this->properties['hostdependencys'] = &$this->_HostdependencyCollection;
        $this->properties['servicedependencys'] = &$this->_ServicedependencyCollection;

        //Factory load classes 
        foreach($this->_map as $type => &$Collection){
            $Collection = NagiosCollection::factory($type);
        }

        //Non-factory classes 
        $this->_map['programstatus'] = &$this->_Programstatus;
        $this->_map['info'] = &$this->_Info;

    }


    private function _add($type,$Data){

        $Collection = $this->_map[$type];

       // $Collection[$Data->id] = $Data; 
       $Collection->add($Data);
    }

    public function get_collection($type){

        $collection = '_'.ucfirst($type).'Collection';

        if(isset($this->_map[$type])){
            return $this->_map[$type];
        } elseif(isset($this->$collection)){
            return $this->$collection;
        } else {
            throw new Exception(get_class($this).': Unable to retrieve collection of type: '.$type); 
        }
    }



}

/* End of file nagios_data.php */
/* Location: ./application/models/nagios_data.php */
