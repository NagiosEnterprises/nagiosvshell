<?php

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

?>

    <?php 
    //commented out, needs further revisions to be used on config pages.  Only host filter works right now -MG
    /*
    <div class='resultFilter'>
        <form id='resultfilterform' action='{$_SERVER['PHP_SELF']}' method='get'>
            <input type="hidden" name="type" value="$type">
            <input type="hidden" name="objtype_filter" value="$objtype_filter">
            <label class='label' for='name_filter'>Search Configuration Name:</label>
            <input type="text" name='name_filter' value="$name_filter"></input>
            <input type='submit' name='submitbutton' value='Filter' />
        </form>
    </div>
    */ 
    ?>

<ul class="configlist">

    <?php 

    $id = 0;
    // TODO: Verify this service_count works as expected. 
    $service_count = 0;
    $object_list = '';

    foreach ($data as $a) {

        $id++;

        //change variables based on type of object being viewed
        switch ($objtype_filter) {
            case 'hosts_objs':
                $name = $a['host_name'];
                $link = htmlentities('/'.BASEURL.'/details/host/'.urlencode($name));
                $title = gettext('Host').': <a href="'.$link.'" title="Host Details">'.$name.'</a>';
            break;

            case 'services_objs':
                $name = $a['service_description'];
                $host = $a['host_name'];
                $hlink = htmlentities('/'.BASEURL.'/details/host/'.urlencode($host));
                $link = htmlentities('/'.BASEURL.'/details/service/'.$service_count);
                $title = gettext('Host').': <a href="'.$hlink.'" title="Host Details">'.$host.'</a>'.
                         gettext('Service').':<a href="'.$link.'" title="Service Details">'.$name.'</a>';
                $service_count++;
            break;

            case 'commands':
                $name = $a['command_name'];
                $title = gettext('Command').": $name";
            break;

            case 'hostgroups_objs':
                $name = $a['hostgroup_name'];
                $title = gettext('Group Name').": $name";
            break;

            case 'servicegroups_objs':
                $name = $a['servicegroup_name'];
                $title = gettext('Group Name').": $name";
            break;

            case 'timeperiods':
                $name = $a['timeperiod_name'];
                $title = gettext('Timeperiod').": $name";
            break;

            case 'contacts':
                $name = $a['contact_name'];
                $title = gettext('Contact').": $name";
            break;

            case 'contactgroups':
                $name = $a['contactgroup_name'];
                $title = gettext('Contact Group').": $name";
            break;

            default:
            break;

        }

        //print raw config data into a table
        $data_rows = array();
        foreach ($a as $key => $value) {
            $data_rows[] = '
                <tr class="objectList">
                    <td>'.$key.'</td>
                    <td>'.$value.'</td>
                </tr>
            ';
        }
        $data_rows = implode("\n", $data_rows);

        $table_id = 'table_'.$id;

        $object_list .= '
            <li class="configlist">
                '.$title.'
                <a class="label" onclick="showHide(\''.$table_id.'\')" href="javascript:void(0)">
                    <img class="label" src="'.IMAGESURL.'/expand.gif" title="Show Config" alt="Image" height="12" width="12" />
                </a>
                <div class="hidden" id="'.$table_id.'">
                    <table class="objectList">
                        <tbody>
                            <tr>
                                <th>'.gettext('Config').'</th><th>'.gettext('Value').'</th>
                            </tr>
                            '.$data_rows.'
                        </tbody>
                    </table>
                </div>
            </li>
        ';
    }

    echo $object_list;

    ?>

</ul>
