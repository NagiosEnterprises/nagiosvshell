<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// NagiosUser.php
// nagios user class to handle authorized hosts, services, and admin functionality

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

class Nagios_user extends CI_Model
{
    /*
    *	boolean for users who can see and access all features
    *	@var boolean $admin
    */
    protected $admin = false;

    /*
    *	boolean for viewing all hosts and services
    *	@var boolean $sees_all
    */
    protected $sees_all = false;

    /**
    *	array for storing global authorizations from cgi file
    *	@var mixed $authKeys
    */
    protected $authKeys = array(
        'authorized_for_all_host_commands'         => false,
        'authorized_for_all_hosts'                 => false,
        'authorized_for_all_service_commands'      => false,
        'authorized_for_all_services'              => false,
        'authorized_for_configuration_information' => false,
        'authorized_for_system_commands'           => false,
        'authorized_for_system_information'        => false,
        'authorized_for_read_only'                 => false,
    );

    /**
    *	MAIN AUTH ARRAY
    *	@var mixed $authHosts
    */
    protected static $authHosts = array();

    /**
    *	contactgroup memberships
    *	@var mixed $cg_memberships
    */
    protected static $cg_memberships = array();

    /**
    *	current user
    *	@var string username
    */
    protected static $username;

    /**
    *	constructor initializes authorized hosts and services and determines global privileges
    *	@TODO cache data and move towards session auth so this info gets updated upon login and restart of Nagios
    */
    public function __construct()
    {
        parent::__construct();

        if (! $this->get_user()) {
            exit('Access Denied: No authentication detected.');
        }

        //build main authKeys array (cgi.cfg settings)
        $this->set_perms();

        //check if user can see everything
        $this->admin = $this->determine_admin();

        //if user level account, determin authorized objects for object filtering
        if (! $this->admin) {

            //check to see if user can see all hosts and services
            $this->sees_all = ($this->authKeys['authorized_for_all_hosts'] == true && $this->authKeys['authorized_for_all_services']) ? true : false;

            //build auth objects array
            $this->build_authorized_objects();
        }
    }

    /**
    *	determines authorized user, checks for hard-coded name, then Basic Auth, then Digest auth.  Sets $this->username
    *	@global string $username
    *	@return string $this->username
    */
    private function get_user()
    {
        // some users have requested to turn off authentication or user other
        // methods, this allows override and backwards compatibility
        global $username;

        //allow for basic auth override for backwards compatibility with early
        //versions of V-Shell
        if ($username) {
            $this->username = $username;
        } elseif (isset($_SERVER['REMOTE_USER'])) {
            // HTTP BASIC AUTHENTICATION through Nagios Core or XI
            // When using PHP as mod_php or mod_fcgid
            $this->username = $_SERVER['REMOTE_USER'];
        } elseif (isset($_SERVER['REDIRECT_REMOTE_USER'])) {
            // HTTP BASIC AUTHENTICATION through Nagios Core or XI
            // When using PHP as CGI (including mod_fastcgi)
            $this->username = $_SERVER['REDIRECT_REMOTE_USER'];
        } elseif (isset($_SERVER['PHP_AUTH_USER'])) {
            //digest authentication
            $this->username = $_SERVER['PHP_AUTH_USER'];
        }

        return $this->username ? $this->username : False;
    }

    /**
    *	username is obtained from @global $_SERVER authorized user for nagios
    *	@TODO: make this better.  All auth stuff needs to be handled here, no more global authorizations array
    *	@global mixed $NagiosData
    */
    private function set_perms()
    {
        $ci = &get_instance();
        $permissions = $ci->nagios_data->getProperty('permissions');

        foreach ($permissions as $key => $array) {
            foreach ($array as $user) {

                //look for wildcard
                if ($user == $this->username || $user == '*') {
                    $this->authorize($key);
                }
            }
        }
    }

    /**
    *	sets authorization for user, also sets global $authorizations.  See authorizations.inc.php for auth list
    *	@TODO replace @global $authorizations array and encapsulate this in user object
    *	@global mixed $authorizations array
    */
    private function authorize($auth)
    {
        //sets global permission
        $this->authKeys[$auth] = true; //class auth controller
    }

    // get methods

    /**
    *	gets list of authorized hosts for user
    *	@return mixed $authHosts array of all authorized hosts and services
    */
    public function get_authorized_hosts()
    {
        return $this->authHosts;
    }

    /**
    *	returns username
    *	@return string $username current user
    */
    public function get_username()
    {
        return $this->username;
    }

    /**
    *	returns boolean if user is admin
    *	@return boolean $admin boolean if user has admin privileges and can see and do everything
    */
    public function is_admin()
    {
        return $this->admin;
    }
    /**
    *	@return boolean $key authorization key from cgi.cfg file
    */
    public function if_has_authKey($key)
    {
        if (isset($this->authKeys[$key])) {
            return $this->authKeys[$key];
        }
    }

    /**
    *	@return boolean decide if this user is an admin
    */
    private function determine_admin()
    {
        if ($this->username == 'nagiosadmin') {
           return true;
        }

        foreach ($this->authKeys as $key) {
            //if all auth keys are set, user is an admin
            if ($key != true) {
                return false;
            }
        }

        return true;
    }

    /**
    *	sets global auths from cgi.cfg file
    *	@TODO: move to protected method -> this will replace previous global auths in future versions
    *	@return null changes cgi.cfg booleans for current NagiosUser
    */
    public function setAuthKey($keyname, $value)
    {
        if (isset($this->authKeys[$keyname])) {
            $this->authKeys[$keyname] = $value;
        }
    }

    /**
    *	tests if user can view host
    *	@returns boolean
    */
    public function is_authorized_for_host($hostname)
    {
        //can user see everything?
        if ($this->admin == true || $this->sees_all == true) {
            return true;
        }

        //user level filtering
        if (isset($this->authHosts[$hostname]) && $this->authHosts[$hostname]['all_services'] == true ) {
            return true;
        }

        //not authorized
        return false;
    }

    /**
    *	tests if user can view service
    *	@returns boolean
    */
    public function is_authorized_for_service($hostname, $service)
    {
        //can user see everything?
        if ($this->admin == true || $this->sees_all == true) {
            return true;
        }

        //user level filtering
        if (isset($this->authHosts[$hostname]) && (in_array($service, $this->authHosts[$hostname]['services']) || $this->authHosts[$hostname]['all_services'] == true) ) {
            return true;
        }

        //not authorized
        return false;
    }

    /**
    *	main logic function for user-level filtering, builds $this->authHosts array
    *	CREATES SINGLE MULTI-D HEIRARCHY ARRAY
    *	LOGIC: - check for host contacts, and host contactgroups
    *			 - check for host escalations, and hostescalation contactgroups
    *			 - check for service contacts, and service contactgroups
    *			 - check for serviceescalation contacts, then serviceescalation contact groups
    *
    *	ARRAY STRUCTURE
    *	$authObjects =
    *	array ( 'localhost' => array(
    *										'host_name' => 'localhost',
    *										'all_services' => true | false,
    *										'services'	=> array( 0 => service1
    *																	 1 => service2
    *																	 3 => service3 )
    *
    *										)
    */
    private function build_authorized_objects()
    {
        $ci = &get_instance();

        //fetch necessary object config arrays
        $hosts = $ci->nagios_data->getProperty('hosts_objs');
        $contactgroups = $ci->nagios_data->getProperty('contactgroups');

        //find relevant contact groups for user
        $this->cg_memberships = array();
        foreach ($contactgroups as $cg) {
            if (in_array($this->username, explode(',', $cg->members)) ) {
                //add contactgroup to array if user is a member of it
                $this->cg_memberships[] = $cg->contactgroup_name;
            }
        }

        // Hosts
        foreach ($hosts as $host) {

            //check is user is a direct contact
            $key = $host->host_name;

            if (property_exists($host, 'contacts') && in_array($this->username, explode(',', $host->contacts)) ) {
                $this->authHosts[$key] = array('host_name' => $key, 'all_services' => true, 'services' => array());

                //skip to next host
                continue;
            }

            //if host has contact groups
            if (property_exists($host, 'contact_groups')) {

                //members to array
                $cgmems = explode(',', $host->contact_groups);

                foreach ($cgmems as $cg) {
                    //check if contact group is in user's list of memberships
                    if (in_array($cg, $this->cg_memberships)) {
                        $this->authHosts[$key] = array('host_name' => $key, 'services' => array(), 'all_services' => true );
                        break;
                    }

                }
            }

        }

        // Host escalations
        // add hosts if user is assigned as a contact or contactgroup member
        $this->add_escalated_hosts();

        // Services

        // get services objects
        $services = $ci->nagios_data->getProperty('services_objs');

        foreach ($services as $service) {
            $key = $service->host_name;
            //check for authorized host first, if all services are authorized skip ahead
            if (! empty($this->authHosts) && isset($this->authHosts[$key]) && $this->authHosts[$key]['all_services'] == true) {
                continue;
            }

            //check for authorization at the service level
            //if user is a contact
            if (property_exists($service, 'contacts') && in_array($this->username, explode(',', $service->contacts)) ) {
                if (! isset($this->authHosts[$key])) {
                    //if this is set somewhere else, the all_services boolean should already be set
                    $this->authHosts[$key] = array('host_name' => $key, 'services' =>array($service->service_description), 'all_services' => false );
                } else {
                    //only add service if it's not already there
                    if (!in_array($service->service_description, $this->authHosts[$key]['services']) && $this->authHosts[$key]['all_services'] == false) {
                        $this->authHosts[$key]['services'][] = $service->service_description;
                    }
                }

                continue;
            }

            //check against contactgroups
            if (property_exists($service, 'contact_groups') ) {
                $cgmems = explode(',', $service->contact_groups);
                foreach ($cgmems as $cg) {
                    //user is a contact for service
                    if (in_array($cg, $this->cg_memberships)) {
                        if (! isset($this->authHosts[$key])) {
                            //if this is set somewhere else, the all_services boolean should already be set
                            $this->authHosts[$key] = array('host_name' => $key, 'services' =>array(), 'all_services' => false );
                        } else {
                            //add service if it's not already in the array
                            if (! in_array($service->service_description, $this->authHosts[$key]['services']) && $this->authHosts[$key]['all_services'] == false) {
                                $this->authHosts[$key]['services'][] = $service->service_description;
                            }
                        }

                        break;
                    }
                }
            }
        }

        //Service Escalations
        //add services if user is assigned as an escalated contact or contactgroup member
        $this->add_escalated_services();
    }

    /**
    *	sweeps through host escalation definitions and adds to authHosts as needed
    */
    private function add_escalated_hosts()
    {
        $ci = &get_instance();

        //loop through all host escalations
        $host_escs = $ci->nagios_data->getProperty('hostescalations');
        foreach ($host_escs as $he) {

            //check for authorized host first, skip ahead if it
            if (isset($this->authHosts[$he['host_name']]) && $this->authHosts[$he['host_name']]['all_services'] == false) {
                continue;
            }

            //check if user is a contact for escalation
            if (in_array($this->username, explode(',', $he['contacts'])) ) {
                //add host if not already there, or if it's there but not all services are authorized
                if (!isset($this->authHosts[$he['host_name']]) || (isset($this->authHosts[$he['host_name']]) && $this->authHosts[$he['host_name']]['all_services'] == false) ) {
                    //don't overwrite existing arrays
                    $this->authHosts[$he['host_name']] = array('host_name' => $he['host_name'], 'services' => array(), 'all_services' => true);
                }

                //do nothing if host is already authorized
                //no need to check contactgroups
                continue;
            }

            //check if user's contactgroups are in the list
            if (isset($he['contact_groups'])) {

                //compare arrays
                $matches = array_intersect(explode(',', $he['contact_groups']), $this->cg_memberships);
                if (! empty($matches)) {

                    // push host list into authHosts array
                    if (!isset($this->authHosts[$he['host_name']]) || (isset($this->authHosts[$he['host_name']]) && $this->authHosts[$he['host_name']]['all_services'] == false) ) {

                        //don't overwrite existing arrays
                        $this->authHosts[$he['host_name']] = array('host_name' => $he['host_name'], 'services' => array(), 'all_services' => true );
                    }

                    //do nothing if host is already fully authorized
                }
            }
        }
    }

    /**
    *	sweeps through escalation definitions and adds to $authHosts as needed
    *	sort through all service escalations in objects.cache and push appropriate services onto array stack
    *	NOTE host_name and service_descriptions are not comma delineated in objects.cache, all single definitions
    */
    private function add_escalated_services()
    {
        $ci = &get_instance();

        $serv_escs = $ci->nagios_data->getProperty('serviceescalations');
        foreach ($serv_escs as $se) {

            //skip ahead if all services are already authorized for host
            if (isset($this->authHosts[$se['host_name']]) && $this->authHosts[$se['host_name']]['all_services'] == true) {
                continue;
            }

            //check if user is a contact for escalation
            //if user is in list of contacts
            if (isset($se['contacts']) && in_array($this->username, explode(',', $se['contacts'])) ) {

                //check to see if host key exists in the array
                if (! isset($this->authHosts[$se['host_name']])) {

                    //don't overwrite existing arrays
                    $this->authHosts[$se['host_name']] = array('host_name' => $se['host_name'], 'all_services'=> false, 'services' => array($se['service_description']) );
                } else {

                    //if it exists, push services onto array stack, services array should already be there
                    $this->authHosts[$se['host_name']]['services'][] = $se['service_description'];
                }

                //no need to check contactgroups if defined as contact
                continue;
            }

            //check if user's contactgroups are in the list
            if (isset($se['contact_groups'])) {

                //compare arrays
                $matches = array_intersect(explode(',', $se['contact_groups']), $this->cg_memberships);

                if (! empty($matches)) {

                    //check to see if array key exists yet
                    if (! isset($this->authHosts[$se['host_name']]) ) {

                        //don't overwrite existing arrays
                        $this->authHosts[$se['host_name']] = array('host_name' => $se['host_name'], 'services' => array($se['service_description']), 'all_services'=>false );
                    } else {

                        //if it exists, push services onto array stack
                        $this->authHosts[$se['host_name']]['services'][] = $se['service_description'];	//object.cache separates services into individual defs
                    }
                }
            }
        }
    }
}

/* End of file nagios_user.php */
/* Location: ./application/models/nagios_user.php */
