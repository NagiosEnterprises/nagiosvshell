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

function object_output($objtype_filter, $data, $mode)
{
    $retval = '';
    switch ($mode) {
        case 'html':
            include(DIRBASE.'/views/config_viewer.php');
            $retval = build_object_list($data, $objtype_filter);
        break;
    }

    return $retval;
}

function host_and_service_detail_output($type, $data, $mode)
{
    $retval = '';
    switch ($mode) {
        case 'html':
            require_once(DIRBASE.'/views/'.$type.'s.php');
            $display_function = 'get_'.preg_replace('/detail/', '_detail', $type).'s';
            $retval = $display_function($data);
        break;
    }

    return $retval;
}

function hostgroups_and_servicegroups_output($type, $data, $mode)
{
    $retval = '';
    switch ($mode) {
        case 'html':
            $title = ucwords(preg_replace('/objs/', 'Objects', preg_replace('/_/', ' ', $type)));
            $display_function = 'display_'.$type;
            $retval = $display_function($data);
        break;
    }

    return $retval;
}

function hosts_and_services_output($type, $data, $mode)
{
    $retval = '';
    switch ($mode) {
        case 'html':
            list($start, $limit) = get_pagination_values();
            $title = ucwords(preg_replace('/objs/', 'Objects', preg_replace('/_/', ' ', $type)));
            include_once(DIRBASE.'/views/'.$type.'.php');
            $display_function = 'display_'.$type;
            $retval = $display_function($data, $start, $limit);
        break;
    }

    return $retval;
}

/* End of file output_functions_helper.php */
/* Location: ./application/helpers/output_functions_helper.php */
