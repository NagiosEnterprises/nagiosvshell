<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

function hosts_and_services_data($type, $state_filter = NULL, $name_filter = NULL, $host_filter = NULL)
{
    $ci = &get_instance();

    $data = $ci->nagios_data->getProperty($type);

    //add filter for user-level filtering
    if (! $ci->nagios_user->is_admin()) {
        $data = user_filtering($data, $type);
    }

    $data_in = $data;

    if ($state_filter) {
        //merge arrays for multiple states
        if ($state_filter == 'PROBLEMS' || $state_filter == 'UNHANDLED' || $state_filter == 'ACKNOWLEDGED') {
            $data = array_merge(
                get_by_state('UNKNOWN', $data_in),
                get_by_state('CRITICAL', $data_in),
                get_by_state('WARNING', $data_in),
                get_by_state('UNREACHABLE', $data_in),
                get_by_state('DOWN', $data_in)
            );

            //filter down problem array
            if ($state_filter == 'UNHANDLED') {
                //loop and return array
                $unhandled = array();
                foreach ($data as $d) {
                    if ($d['problem_has_been_acknowledged'] == 0 && $d['scheduled_downtime_depth'] == 0) {
                        $unhandled[] = $d;
                    }
                }
                $data = $unhandled;
            }

            if ($state_filter == 'ACKNOWLEDGED') {
                //loop and return array
                $acknowledged = array();
                foreach ($data as $d) {
                    if ($d['problem_has_been_acknowledged'] > 0 || $d['scheduled_downtime_depth'] > 0) {
                        $acknowledged[] = $d;
                    }
                }
                $data = $acknowledged;
            }
        } elseif ($state_filter == 'PENDING' || $state_filter == 'OK' || $state_filter == 'UP') {
            $filtered = array();

            if ($state_filter == 'PENDING') {
                foreach ($data as $d) {
                    if ($d['current_state'] == 0 && $d['last_check'] == 0) {
                        $filtered[] = $d;
                    }
                }
            } else {
                foreach ($data as $d) {
                    if ($d['current_state'] == 0 && $d['last_check'] != 0) {
                        $filtered[] = $d;
                    }
                }
            }
            $data = $filtered;
        } else {
            $s = ($type == 'services') ? true : false;
            $data = get_by_state($state_filter, $data,$s);
        }
    }

    if ($name_filter) {
        $name_data = get_by_name($name_filter, $data);
        $service_data = get_by_name($name_filter, $data, 'service_description');
        $data = $name_data;
        foreach ($service_data as $i => $service) {
            if (!isset($data[$i])) {
                $data[$i] = $service;
            }
        }
        $data = array_values($data);
    }

    if ($host_filter) {
        $name_data = get_by_name($name_filter, $data,'host_name',$host_filter);
        $service_data = get_by_name($name_filter, $data, 'service_description',$host_filter);
        $data = $name_data;
        foreach ($service_data as $i => $service) {
            if (!isset($data[$i])) {
                $data[$i] = $service;
            }
        }
        $data = array_values($data);
    }

    return $data;
}

function hostgroups_and_servicegroups_data($type, $name_filter = NULL)
{
    $ci = &get_instance();
    $data = array();

    if ($type == 'hostgroups') {
        $data = $ci->nagios_group->get_hostgroup_data();
    } elseif ($type == 'servicegroups') {
        $data = $ci->nagios_group->get_servicegroup_data();
    }

    if ($name_filter) {
        // TODO filters against Services and/or hosts within groups, status of services/hosts in groups, etc...
        $name = preg_quote($name_filter, '/');

        //XXX create_function needs to be removed
        $match_keys = array_filter(array_keys($data), create_function('$d', 'return !preg_match("/'.$name.'/i", $d);'));

        // XXX is there a better way?
        foreach ($match_keys as $key) {
            unset($data[$key]);
        }
    }

    return $data;
}

function object_data($objtype_filter, $name_filter = '')
{
    $ci = &get_instance();
    $data = array();

    if (verify_object_data_filter($objtype_filter)) {
        $data = $ci->nagios_data->getProperty($objtype_filter);

        if ($name_filter) {
            $name_data = get_by_name($name_filter, $data);
            $service_data = get_by_name($name_filter, $data, 'service_description');

            $data = $name_data;
            foreach ($service_data as $i => $service) {
                if (!isset($data[$i])) {
                    $data[$i] = $service;
                }
            }
        }
    }

    return $data;
}

function verify_object_data_filter ($objtype_filter)
{
    $valid_objtype_filters = array(
        'hosts_objs',
        'services_objs',
        'hostgroups_objs',
        'servicegroups_objs',
        'timeperiods',
        'contacts',
        'contactgroups',
        'commands'
    );

    return in_array($objtype_filter, $valid_objtype_filters);
}

/* End of file data_functions_helper.php */
/* Location: ./application/helpers/data_functions_helper.php */
