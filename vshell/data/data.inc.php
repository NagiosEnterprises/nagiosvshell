<?php //include file for data directory 


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

require_once('cache_or_disk.php');

$useAPC = useAPC();
//if ($useAPC) {
//  apc_clear_cache();
//  apc_clear_cache('user');
//}

//future releases will isolate array builds into separate functions to improve performance 
//include('read_objects.php');
//list('hosts_objs', 'services_objs', 'hostgroups_objs', 'servicegroups_objs',
//  'contacts', 'contactgroups', 'timeperiods', 'commands') = parse_objects_file();
$disk_cache_keys = array('hosts_objs', 'services_objs', 'hostgroups_objs', 'servicegroups_objs', 
  'contacts', 'contactgroups', 'timeperiods', 'commands');
cache_or_disk('objects', OBJECTSFILE, $disk_cache_keys);
//fb($hosts_objs, 'hosts_objs from read_obects');
//fb($services_objs, 'services_objs from read_obects');
//fb($hostgroups_objs, 'hostgroups_objs from read_obects');
//fb($servicegroups_objs, 'servicegroups_objs from read_obects');
//fb($contacts, 'contacts from read_obects');
//fb($contactgroups, 'contactgroups from read_obects');
//fb($timeperiods, 'timeperiods from read_obects');
//fb($commands, 'commands from read_obects');

//include('read_perms.php');//returns $permissions array from cgi.cgf
//list($permissions) = parse_perms_file();
cache_or_disk('perms', CGICFG, array('permissions'));
//fb($permissions, 'permissions from read_perms');

cache_or_disk('status', STATUSFILE, array('hosts', 'services', 'comments', 'info', 'details'));
//fb($hosts, 'hosts from read_status');
//fb($services, 'services from read_status');
//fb($comments, 'comments from read_status');
//fb($info, 'info from read_status');
//fb($details, 'details from read_status');

require('build_groups.php'); //returns host and service groups into global array 												
require('read_details.php');
?>
