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

    <?php echo services_table(); ?>

    <div class="tableOptsWrapper">

        <?php echo $doPagination ? do_pagenumbers($pageCount, $start, $limit, $resultsCount, 'services') : ''; ?>

        <?php

            //creates notes for total results as well as form for setting page limits
            echo do_result_notes($start, $limit, $resultsCount, 'services');

            //moved result filter to display_functions.php and made into function
            echo result_filter($name_filter, 'service');

        ?>

    </div>

    <div class="statusTable">
        <table class="statusTable">
            <thead>
                <tr>
                    <th class="hostname"><?php echo gettext('Host Name')?></th>
                    <th class="service_description"><?php echo gettext('Service')?></th>
                    <th class="status"><?php echo gettext('Status')?></th>
                    <th class="duration"><?php echo gettext('Duration')?></th>
                    <th class="attempt"><?php echo gettext('Attempt')?></th>
                    <th class="last_check"><?php echo gettext('Last Check')?></th>
                    <th class="plugin_output"><?php echo gettext('Status Information')?></th>
                </tr>
            </thead>
            <tbody>

                <?php

                    $last_displayed_host = NULL;
                    for ( $i = $start; $i <= ($start + $limit); $i++) {

                        if ($i > $resultsCount) {
                            break;
                        }

                        // Skip undefined indexes
                        if (! isset($services[$i])) {
                            continue;
                        }

                        process_service_status_keys($services[$i]);

                        $tr = get_color_code($services[$i]);
                        $url = htmlentities('/'.BASEURL.'/details/service/'.$services[$i]['service_id']);
                        $host_url = htmlentities('/'.BASEURL.'/details/host/').urlencode($services[$i]['host_name']);

                        $color = get_host_status_color($services[$i]['host_name']);
                        $hosticons = fetch_host_icons($services[$i]['host_name']);
                        $serviceicons = fetch_service_icons($services[$i]['service_id']);

                        //removing duplicate host names from table for a cleaner look
                        if ($services[$i]['host_name'] == $last_displayed_host) {
                            $td1 = '<td>&nbsp;</td>';
                        } else {
                            $last_displayed_host = $services[$i]['host_name'];
                            $hostlink = '<a class="highlight" href="'.$host_url.'" title="'.gettext('View Host Details').'">';
                            $td1 = '<td class="'.$color.'"><div class="hostname">'.$hostlink.' '.htmlentities($services[$i]['host_name']).'</a>'.$hosticons.'</div></td>';
                        }

                        //table data generation
                        echo '
                            <tr class="statustablerow">
                                '.$td1.'
                                <td class="service_description"><div class="service_description"><a href="'.$url.'">'.$services[$i]['service_description'].'</a>'.$serviceicons.'</div></td>
                                <td class="'.$tr.'">'.$services[$i]['current_state'].'</td>
                                <td class="duration">'.$services[$i]['duration'].'</td>
                                <td class="attempt">'.$services[$i]['attempt'].'</td>
                                <td class="last_check">'.$services[$i]['last_check'].'</td>
                                <td class="plugin_output"><div class="plugin_output">'.$services[$i]['plugin_output'].'</div></td>
                            </tr>
                        ';

                    }

                ?>

            </tbody>
        </table>
    </div>

    <?php echo $doPagination ? do_pagenumbers($pageCount, $start, $limit, $resultsCount, 'services') : '';
