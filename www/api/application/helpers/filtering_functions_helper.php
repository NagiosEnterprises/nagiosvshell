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

function user_filtering($data, $type)
{
    $ci = &get_instance();
    $new_data = array();

    //rebuild array for auth hosts
    if ($type == 'hosts') {
        foreach ($data as $d) {
            if ($ci->nagios_user->is_authorized_for_host($d['host_name'])) {
                $new_data[] = $d;
            }
        }
    }

    //rebuild array for auth services
    if ($type == 'services') {
        if (! empty($data)){
            foreach ($data as $d) {
                if ($ci->nagios_user->is_authorized_for_service($d['host_name'], $d['service_description'])) {
                    $new_data[] = $d;
                }
            }
        }
    }

    return $new_data;
}

function process_state_filter($filter_str)
{
    $ret_filter = NULL;
    $filter_str = strtoupper($filter_str);
    $valid_states = array(
        'UP',
        'DOWN',
        'UNREACHABLE',
        'OK',
        'CRITICAL',
        'WARNING',
        'UNKNOWN',
        'PENDING',
        'PROBLEMS','UNHANDLED',
        'ACKNOWLEDGED'
    );

    if (in_array($filter_str, $valid_states)) {
        $ret_filter = $filter_str;
    }

    return $ret_filter;
}

function process_name_filter($filter_str)
{
    $filter_str = strtolower(rawurldecode($filter_str));

    return $filter_str;
}

function process_objtype_filter($filter_str)
{
    $ret_filter = NULL;
    $filter_str = strtolower($filter_str);
    $valid_objtypes = array(
        'hosts_objs',
        'services_objs',
        'hostgroups_objs',
        'servicegroups_objs',
        'timeperiods',
        'contacts',
        'contactgroups',
        'commands'
    );

    if (in_array($filter_str, $valid_objtypes)) {
        $ret_filter = $filter_str;
    }

    return $ret_filter;
}

/* End of file filtering_functions_helper.php */
/* Location: ./application/helpers/filtering_functions_helper.php */
