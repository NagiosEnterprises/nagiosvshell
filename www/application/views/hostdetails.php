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

    <h3><?php echo gettext('Host Status Detail'); ?></h3>

    <div class="detailWrapper">

        <h4>
            <em><?php echo gettext('Host'); ?>: </em>
            <?php echo $details['Host']; ?>
        </h4>

        <h5>
            <em><?php echo gettext('Member of'); ?>: </em>
            <?php echo $details['MemberOf']; ?>
        </h5>

        <h5>
            <?php $service_link = '/'.BASEURL.'/services?host_filter='.$details['Host']; ?>
            <a href="<?php echo $service_link; ?>" title="<?php echo gettext('See All Services For This Host'); ?>"><?php echo gettext('See All Services For This Host'); ?></a>
        </h5>

        <div class="detailcontainer">

            <fieldset class="hostdetails">
                <legend><?php echo gettext('Advanced Details'); ?></legend>

                <table>
                    <tbody>
                        <tr>
                            <td><?php echo gettext('Current State'); ?></td>
                            <td class="<?php echo $details['State']; ?>"><?php echo $details['State']; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo gettext('Status Information'); ?></td>
                            <td><div class="td_maxwidth"><?php echo $details['StatusInformation']; ?></div></td>
                        </tr>
                        <tr>
                            <td><?php echo gettext('Duration'); ?></td>
                            <td><?php echo $details['Duration']; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo gettext('State Type'); ?></td>
                            <td><?php echo $details['StateType']; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo gettext('Current Check'); ?></td>
                            <td><?php echo $details['CurrentCheck']; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo gettext('Last Check'); ?></td>
                            <td><?php echo $details['LastCheck']; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo gettext('Next Check'); ?></td>
                            <td><?php echo $details['NextCheck']; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo gettext('Last State Change'); ?></td>
                            <td><?php echo $details['LastStateChange']; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo gettext('Last Notification'); ?></td>
                            <td><?php echo $details['LastNotification']; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo gettext('Check Type'); ?></td>
                            <td><?php echo $details['CheckType']; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo gettext('Check Latency'); ?></td>
                            <td><?php echo $details['CheckLatency']; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo gettext('Execution Time'); ?></td>
                            <td><?php echo $details['ExecutionTime']; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo gettext('State Change'); ?></td>
                            <td><?php echo $details['StateChange']; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo gettext('Performance Data'); ?></td>
                            <td><div class="td_maxwidth"><?php echo $details['PerformanceData']; ?></div></td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>

        </div>

        <?php if ($is_authorized) : ?>

        <div class="rightContainer">

<?php
        $page ="

            <fieldset class='attributes'>
            <legend>".gettext('Service Attributes')."</legend>
            <table>
            <tr><td class='{$details['ActiveChecks']}'>".gettext('Active Checks').": {$details['ActiveChecks']}</td>
            <td><a href='{$details['CmdActiveChecks']}'><img src='views/images/action_small.gif' title='".gettext('Toggle Active Checks')."' class='iconLink' height='12' width='12' alt='Toggle' /></a></td></tr>
            <tr><td class='{$details['PassiveChecks']}'>".gettext('Passive Checks').": {$details['PassiveChecks']}</td>
            <td><a href='{$details['CmdPassiveChecks']}'><img src='views/images/action_small.gif' title='".gettext('Toggle Passive Checks')."' class='iconLink' height='12' width='12' alt='Toggle' /></a></td></tr>
            <tr><td class='{$details['Obsession']}'>".gettext('Obsession').": {$details['Obsession']}</td>
            <td><a href='{$details['CmdObsession']}'><img src='views/images/action_small.gif' title='".gettext('Toggle Obsession')."' class='iconLink' height='12' width='12' alt='Toggle' /></a></td></tr>
            <tr><td class='{$details['Notifications']}'>".gettext('Notifications').": {$details['Notifications']}</td>
            <td><a href='{$details['CmdNotifications']}'><img src='views/images/action_small.gif' title='".gettext('Toggle Notifications')."' class='iconLink' height='12' width='12' alt='Toggle' /></a></td></tr>
            <tr><td class='{$details['FlapDetection']}'>".gettext('Flap Detection').": {$details['FlapDetection']}</td>
            <td><a href='{$details['CmdFlapDetection']}'><img src='views/images/action_small.gif' title='".gettext('Toggle Flap Detection')."' class='iconLink' height='12' width='12' alt='Toggle' /></a></td></tr>
            </table>
            <p class='note'>".gettext('Commands will not appear until after page reload')."</p>
            </fieldset>


            <!-- Nagios Core Command Table -->
            <fieldset class='corecommands'>
            <legend>".gettext('Core Commands')."</legend>
            <table>
            <tr><td><a href='{$details['MapHost']}' title='".gettext('Map Host')."'><img src='views/images/statusmapballoon.png' class='iconLink' height='12' width='12' alt='Map' /></a></td><td>".gettext('Locate host on map')."</td></tr>
            <tr><td><a href='{$details['CmdCustomNotification']}' title='".gettext('Send Custom Notification')."'><img src='views/images/notification.gif' class='iconLink' height='12' width='12' alt='Notification' /></a></td><td>".gettext('Send custom notification')."</td></tr>
            <tr><td><a href='{$details['CmdScheduleDowntime']}' title='".gettext('Schedule Downtime')."'><img src='views/images/downtime.png' class='iconLink' height='12' width='12' alt='Downtime' /></a></td><td>".gettext('Schedule downtime')."</td></tr>
            <tr><td><a href='{$details['CmdScheduleDowntimeAll']}' title='".gettext('Schedule Recursive Downtime')."'><img src='views/images/downtime.png' class='iconLink' height='12' width='12' alt='Downtime' /></a></td><td>".gettext('Schedule downtime for this host and all services')."</td></tr>
            <tr><td><a href='{$details['CmdScheduleChecks']}' title='".gettext('Schedule Check')."'><img src='views/images/schedulecheck.png' class='iconLink' height='12' width='12' alt='Schedule' /></a></td><td>".gettext('Schedule a check for all services of this host')."</td></tr>
            <tr><td><a href='{$details['CmdAcknowledge']}' title='{$details['AckTitle']}'><img src='views/images/ack.png' class='iconLink' height='12' width='12' alt='Acknowledge' /></a></td><td>{$details['AckTitle']}</td></tr><!-- make into variable -->
            <tr><td colspan='2'><a class='label' href='{$details['CoreLink']}' title='".gettext('See This Host In Nagios Core')."'>".gettext('See This Host In Nagios Core')."</a></td></tr>
            </table>
            </fieldset>
            ";

        echo $page;
?>

        </div>

        <?php endif; ?>

    </div>

    <?php if ($is_authorized) : ?>

    <div class="commentTable">

        <h5 class="commentTable"><?php echo gettext('Comments'); ?></h5>

        <p class="commentTable">
            <a class="label" href="<?php echo $details['AddComment']; ?>" title="<?php echo gettext('Add Comment'); ?>"><?php echo gettext('Add Comment'); ?></a>
        </p>

        <table class="commentTable">
            <tbody>
                <tr>
                    <th><?php echo gettext('Author'); ?></th>
                    <th><?php echo gettext('Entry Time'); ?></th>
                    <th><?php echo gettext('Comment'); ?></th>
                    <th><?php echo gettext('Actions'); ?></th>
                </tr>
                <?php echo get_host_comments($details['Host']); ?>
            <tbody>
        </table>

    </div>

    <?php endif; ?>
