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

    <?php echo hosts_table(); ?>

    <div class="tableOptsWrapper">

        <?php echo $doPagination ? do_pagenumbers($pageCount, $start, $limit, $resultsCount, 'hosts') : ''; ?>

        <?php

            //creates notes for total results as well as form for setting page limits
            echo do_result_notes($start, $limit, $resultsCount, 'hosts');

            //moved result filter to display_functions.php and made into function
            echo result_filter($name_filter, 'host');

        ?>

    </div>

    <div class="statusTable">
        <table class="statusTable">
            <thead>
                <tr>
                    <th><?php echo gettext('Host Name'); ?></th>
                    <th><?php echo gettext('Status'); ?></th>
                    <th><?php echo gettext('Duration'); ?></th>
                    <th><?php echo gettext('Attempt'); ?></th>
                    <th><?php echo gettext('Last Check'); ?></th>
                    <th><?php echo gettext('Status Information'); ?></th>
                </tr>
            </thead>
            <tbody>

                <?php

                    for ( $i = $start; $i <= ($start + $limit); $i++) {

                        if ($i >= $resultsCount) {
                            break;
                        }

                        //skip undefined indexes of hosts array
                        if ( ! isset($hosts[$hostnames[$i]]) ) {
                            continue;
                        }

                        $host = $hosts[$hostnames[$i]];

                        //process remaining variables for display here
                        process_host_status_keys($host);

                        // CSS style class based on status
                        $tr = get_color_code($host);
                        $url = htmlentities(BASEURL.'index.php?type=hostdetail&name_filter='.$host['host_name']);

                        //add function to fetch_icons
                        $icons = fetch_host_icons($host['host_name']); //returns all icons in one string

                        echo '<tr>
                                <td><a href="'.$url.'">'.$host['host_name'].'</a>'.$icons.'</td>
                                <td class="'.$tr.'">'.$host['current_state'].'</td>
                                <td>'.$host['duration'].'</td>
                                <td>'.$host['attempt'].'</td>
                                <td>'.$host['last_check'].'</td>
                                <td>'.$host['plugin_output'].'</td>
                            </tr>';
                    }

                ?>

            </tbody>
        </table>
    </div>

    <?php echo $doPagination ? do_pagenumbers($pageCount, $start, $limit, $resultsCount, 'hosts') : '';
