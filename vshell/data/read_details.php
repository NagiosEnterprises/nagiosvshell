<?php //read_details.php     reads full host and service status details  


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

function grab_details($type) {
  global $details;
  return $details[$type];
}


//status detail arrays for application use 
//USAGE:   
//$hostdetails = grab_details('host');
//$servicedetails = grab_details('service');
//print_r($hostdetails);


///////////////////////////////////////////////////////////////////////////
//FUNCTION get_details_by() 
//
//this function grabs all status details from an individual host or service 
//
//$type = 'host' 'service' 'program' 
//$arg = index of host or service array, starting at 1 	
//
//returns: array(status details of a single host or service) 
//
//USAGE: $host_a = get_details_by('service', 'service4');
//
function get_details_by($type, $arg)
{
  $retval = NULL;

	if($type=='service')
	{
    $details = grab_details($type);
    $retval = $details[$arg];
  }
	if($type=='host')
	{
		foreach(grab_details($type) as $host_details)
		{
			if(trim($host_details['host_name']) == trim($arg)) 
			{
        $retval = $host_details;
        break;
			}
		}
  }
  return $reval;
}

?>
