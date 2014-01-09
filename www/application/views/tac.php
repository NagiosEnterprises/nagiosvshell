<?php //tactical overview page

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

$ci =& get_instance();

?>

<!-- ##################Nagios Info Table################### -->
<div id='infodiv'>

<p class='note'>Nagios V-Shell <?php echo $version; ?><br />
    Copyright (c) 2010-2012 <br />
    Nagios Enterprises, LLC. <br />
   <?php echo gettext('Written by'); ?> Mike Guthrie<br />
   <?php echo gettext("For questions, feedback, <br /> or support, visit the"); ?><br />
   <a href='http://support.nagios.com/forum/viewforum.php?f=19' target='_blank'>V-Shell
    <?php echo gettext('Forum'); ?>
   </a>.</p>

</div>

<table class='tac'>
<tr><th><?php echo gettext('Tactical Monitoring Overview'); ?></th></tr>
    <tr>
        <td>
            <?php echo gettext('Last Check'); echo ": $lastcmd"; ?><br />
             Nagios® Core™ <?php echo $version; ?> - www.nagios.org<br />
            <?php echo gettext('Logged in as'); ?> :
            <?php echo $ci->nagios_user->get_username(); ?>
            <br />
        </td>
    </tr>
</table>

<!-- builds health meter divs for hosts and services -->

    <div id='meterContainer'>

<?php
    $Total = $hostsTotal;
    $problems = $hostsProblemsTotal;
    $health = (floatval($Total-$problems) / floatval($Total)) *100;
    //$health = 30;
?>
    <div class='h_container'><?php echo gettext('Host');?> <?php echo gettext('Health'); ?>: <?php echo round($health,2); ?>% <br />
                    <div class='borderDiv'>
                    <div class='healthmeter' style='background: rgb(<?php echo color_code(round($health,0)); ?>); width: <?php echo $health; ?>%;'>
                </div></div></div>

<?php
    $Total = $servicesTotal;
    $problems = $servicesProblemsTotal;
    $health = (floatval($Total-$problems) / floatval($Total)) *100;
    //$health = 30;
?>
    <div class='h_container'><?php echo gettext('Service');?> <?php echo gettext('Health'); ?>: <?php echo round($health,2); ?>% <br />
                    <div class='borderDiv'>
                    <div class='healthmeter' style='background: rgb(<?php echo color_code(round($health,0)); ?>); width: <?php echo $health; ?>%;'>
                </div></div></div>

</div> <!-- end health meters -->

<?php echo hosts_table(); ?>

<?php echo services_table(); ?>

<!-- #####################SEARCH BOX####################-->
<div class='resultFilter'>
    <form id='resultfilterform' action='<?php echo $_SERVER['PHP_SELF']; ?>' method='get'>
        <input type='hidden' name='type' value='services'>
        <label class='label' for='name_filter'><?php echo gettext('Search String'); ?></label>
        <input type='text' name='name_filter'></input>
        <input type='submit' name='submitbutton' value='<?php echo gettext('Filter'); ?>' />
    </form>
</div>

<!-- #####################ENABLED FEATURES TABLE ####################-->
<table class='tac'>
<tr><th><?php echo gettext('Monitoring Features'); ?></th></tr>
<tr>
    <td><?php echo gettext('Flap Detection'); ?></td><td><?php echo gettext('Notifications'); ?></td><td><?php echo gettext('Event Handlers'); ?></td>
    <td><?php echo gettext('Active Checks'); ?></td><td><?php echo gettext('Passive Checks'); ?></td>
</tr><tr>

<!-- ///////////////////////FLAPPING//////////////////////////////// -->
    <td class='green'>
        <?php echo $hostsFdHtml; ?><br />
         <?php echo $servicesFdHtml; ?><br />
        <?php echo $hostsFlapHtml; ?><br />
         <?php echo $servicesFlapHtml; ?><br />
    </td>

    <!-- /////////////////////////////NOTIFICATIONS/////////////////////////////// -->
    <td class='green'>
        <?php echo $hostsNtfHtml; ?><br />
         <?php echo $servicesNtfHtml; ?><br />
    </td>

    <!-- ///////////////////////////////EVENT HANDLERS///////////////////////////// -->
    <td class='green'>
        <?php echo $hostsEhHtml; ?><br />
         <?php echo $servicesEhHtml; ?><br />
    </td>

    <!-- /////////////////////////////////ACTIVE/PASSIVE CHECKS///////////////////////////	-->
    <td class='green'>
        <?php echo $hostsAcHtml; ?><br />
         <?php echo $servicesAcHtml; ?><br />
    </td>

    <td class='green'>
        <?php echo $hostsPcHtml; ?><br />
         <?php echo $servicesPcHtml; ?><br />
    </td>

</tr>
</table>
<br />

<?php
