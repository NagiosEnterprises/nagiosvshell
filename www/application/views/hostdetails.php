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

            <fieldset class="attributes">
                <legend><?php echo gettext('Service Attributes'); ?></legend>
                <table>
                    <tbody>
                        <tr>
                            <td class="<?php echo $details['ActiveChecks']; ?>"><?php echo gettext('Active Checks'); ?>: <?php echo $details['ActiveChecks']; ?></td>
                            <td><a href="<?php echo $details['CmdActiveChecks']; ?>"><img src="<?php echo IMAGESURL; ?>/action_small.gif" title="<?php echo gettext('Toggle Active Checks'); ?>" class="iconLink" height="12" width="12" alt="Toggle" /></a></td>
                        </tr>
                        <tr>
                            <td class="<?php echo $details['PassiveChecks']; ?>"><?php echo gettext('Passive Checks'); ?>: <?php echo $details['PassiveChecks']; ?></td>
                            <td><a href="<?php echo $details['CmdPassiveChecks']; ?>"><img src="<?php echo IMAGESURL; ?>/action_small.gif" title="<?php echo gettext('Toggle Passive Checks'); ?>" class="iconLink" height="12" width="12" alt="Toggle" /></a></td>
                        </tr>
                        <tr>
                            <td class="<?php echo $details['Obsession']; ?>"><?php echo gettext('Obsession'); ?>: <?php echo $details['Obsession']; ?></td>
                            <td><a href="<?php echo $details['CmdObsession']; ?>"><img src="<?php echo IMAGESURL; ?>/action_small.gif" title="<?php echo gettext('Toggle Obsession'); ?>" class="iconLink" height="12" width="12" alt="Toggle" /></a></td>
                        </tr>
                        <tr>
                            <td class="<?php echo $details['Notifications']; ?>"><?php echo gettext('Notifications'); ?>: <?php echo $details['Notifications']; ?></td>
                            <td><a href="<?php echo $details['CmdNotifications']; ?>"><img src="<?php echo IMAGESURL; ?>/action_small.gif" title="<?php echo gettext('Toggle Notifications'); ?>" class="iconLink" height="12" width="12" alt="Toggle" /></a></td>
                        </tr>
                        <tr>
                            <td class="<?php echo $details['FlapDetection']; ?>"><?php echo gettext('Flap Detection'); ?>: <?php echo $details['FlapDetection']; ?></td>
                            <td><a href="<?php echo $details['CmdFlapDetection']; ?>"><img src="<?php echo IMAGESURL; ?>/action_small.gif" title="<?php echo gettext('Toggle Flap Detection'); ?>" class="iconLink" height="12" width="12" alt="Toggle" /></a></td>
                        </tr>
                    </tbody>
                </table>
                <p class="note"><?php echo gettext('Commands will not appear until after page reload'); ?></p>
            </fieldset>

            <fieldset class="corecommands">
                <legend><?php echo gettext('Core Commands'); ?></legend>
                <table>
                    <tbody>
                        <tr>
                            <td><a href="<?php echo $details['MapHost']; ?>" title="<?php echo gettext('Map Host'); ?>"><img src="<?php echo IMAGESURL; ?>/statusmapballoon.png" class="iconLink" height="12" width="12" alt="Map" /></a></td>
                            <td><?php echo gettext('Locate host on map'); ?></td>
                        </tr>
                        <tr>
                            <td><a href="<?php echo $details['CmdCustomNotification']; ?>" title="<?php echo gettext('Send Custom Notification'); ?>"><img src="<?php echo IMAGESURL; ?>/notification.gif" class="iconLink" height="12" width="12" alt="Notification" /></a></td>
                            <td><?php echo gettext('Send custom notification'); ?></td>
                        </tr>
                        <tr>
                            <td><a href="<?php echo $details['CmdScheduleDowntime']; ?>" title="<?php echo gettext('Schedule Downtime'); ?>"><img src="<?php echo IMAGESURL; ?>/downtime.png" class="iconLink" height="12" width="12" alt="Downtime" /></a></td>
                            <td><?php echo gettext('Schedule downtime'); ?></td>
                        </tr>
                        <tr>
                            <td><a href="<?php echo $details['CmdScheduleDowntimeAll']; ?>" title="<?php echo gettext('Schedule Recursive Downtime'); ?>"><img src="<?php echo IMAGESURL; ?>/downtime.png" class="iconLink" height="12" width="12" alt="Downtime" /></a></td>
                            <td><?php echo gettext('Schedule downtime for this host and all services'); ?></td>
                        </tr>
                        <tr>
                            <td><a href="<?php echo $details['CmdScheduleChecks']; ?>" title="<?php echo gettext('Schedule Check'); ?>"><img src="<?php echo IMAGESURL; ?>/schedulecheck.png" class="iconLink" height="12" width="12" alt="Schedule" /></a></td>
                            <td><?php echo gettext('Schedule a check for all services of this host'); ?></td>
                        </tr>
                        <tr>
                            <td><a href="<?php echo $details['CmdAcknowledge']; ?>" title="<?php echo $details['AckTitle']; ?>"><img src="<?php echo IMAGESURL; ?>/ack.png" class="iconLink" height="12" width="12" alt="Acknowledge" /></a></td>
                            <td><?php echo $details['AckTitle']; ?></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <a class="label" href="<?php echo $details['CoreLink']; ?>" title="<?php echo gettext('See This Host In Nagios Core'); ?>"><?php echo gettext('See This Host In Nagios Core'); ?></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>

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
