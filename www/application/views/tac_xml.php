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
// WITHOUT ANY WARRANTY']; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
// General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program']; if not, write to the Free Software
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
// GOODS OR SERVICES']; LOSS OF USE, DATA, OR PROFITS']; OR BUSINESS INTERRUPTION) OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, STRICT LIABILITY, TORT (INCLUDING
// NEGLIGENCE OR OTHERWISE) OR OTHER ACTION, ARISING FROM, OUT OF OR IN CONNECTION
// WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

?><?xml version="1.0" encoding="utf-8"?>
<tacinfo>
    <!-- hosts -->
    <hoststatustotals>
        <down>
            <total><?php echo $td['hostsDownTotal']; ?></total>
            <unhandled><?php echo $td['hostsDownUnhandled']; ?></unhandled>
            <scheduleddowntime><?php echo $td['hostsDownScheduled']; ?></scheduleddowntime>
            <acknowledged><?php echo $td['hostsDownAcknowledged']; ?></acknowledged>
            <disabled><?php echo $td['hostsDownDisabled']; ?></disabled>
        </down>
        <unreachable>
            <total><?php echo $td['hostsUnreachableTotal']; ?></total>
            <unhandled><?php echo $td['hostsUnreachableUnhandled']; ?></unhandled>
            <scheduledunreachabletime><?php echo $td['hostsUnreachableScheduled']; ?></scheduledunreachabletime>
            <acknowledged><?php echo $td['hostsUnreachableAcknowledged']; ?></acknowledged>
            <disabled><?php echo $td['hostsUnreachableDisabled']; ?></disabled>
        </unreachable>
        <up>
            <total><?php echo $td['hostsUpTotal']; ?></total>
            <disabled><?php echo $td['hostsUpDisabled']; ?></disabled>
        </up>
        <pending>
            <total><?php echo $td['hostsPending']; ?></total>
            <disabled><?php echo $td['hostsPendingDisabled']; ?></disabled>
        </pending>
    </hoststatustotals>

    <!-- services -->
    <servicestatustotals>
        <warning>
            <total><?php echo $td['servicesWarningTotal']; ?></total>
            <unhandled><?php echo $td['servicesWarningUnhandled']; ?></unhandled>
            <scheduleddowntime><?php echo $td['servicesWarningScheduled']; ?></scheduleddowntime>
            <acknowledged><?php echo $td['servicesWarningAcknowledged']; ?></acknowledged>
            <hostproblem><?php echo $td['servicesWarningHostProblem']; ?></hostproblem>
            <disabled><?php echo $td['servicesWarningDisabled']; ?></disabled>
        </warning>
        <unknown>
            <total><?php echo $td['servicesUnknownTotal']; ?></total>
            <unhandled><?php echo $td['servicesUnknownUnhandled']; ?></unhandled>
            <scheduleddowntime><?php echo $td['servicesUnknownScheduled']; ?></scheduleddowntime>
            <acknowledged><?php echo $td['servicesUnknownAcknowledged']; ?></acknowledged>
            <hostproblem><?php echo $td['servicesUnknownHostProblem']; ?></hostproblem>
            <disabled><?php echo $td['servicesUnknownDisabled']; ?></disabled>
        </unknown>
        <critical>
            <total><?php echo $td['servicesCriticalTotal']; ?></total>
            <unhandled><?php echo $td['servicesCriticalUnhandled']; ?></unhandled>
            <scheduleddowntime><?php echo $td['servicesCriticalScheduled']; ?></scheduleddowntime>
            <acknowledged><?php echo $td['servicesCriticalAcknowledged']; ?></acknowledged>
            <hostproblem><?php echo $td['servicesCriticalHostProblem']; ?></hostproblem>
            <disabled>2</disabled>
        </critical>
        <ok>
            <total><?php echo $td['servicesOkTotal']; ?></total>
            <disabled><?php echo $td['servicesOkDisabled']; ?></disabled>
        </ok>
        <pending>
            <total><?php echo $td['servicesPending']; ?></total>
            <disabled><?php echo $td['servicesPendingDisabled']; ?></disabled>
        </pending>
    </servicestatustotals>

    <!-- monitoring features -->
    <monitoringfeaturestatus>
        <flapdetection>
            <global><?php echo $td['flap_detection']; ?></global>
        </flapdetection>
        <notifications>
            <global><?php echo $td['notifications']; ?></global>
        </notifications>
        <eventhandlers>
            <global><?php echo $td['event_handlers']; ?></global>
        </eventhandlers>
        <activeservicechecks>
            <global><?php echo $td['active_service_checks']; ?></global>
        </activeservicechecks>
        <passiveservicechecks>
            <global><?php echo $td['passive_service_checks']; ?></global>
        </passiveservicechecks>
    </monitoringfeaturestatus>
</tacinfo>
