////////////////////////////////////////
//creates xml output for tactical overview 
//expecting $tac_data array for values 
function tac_xml($td)
{
	//$td is $tac_data from main array 
	$xmldoc='<?xml version="1.0" encoding="utf-8"?>'; 
	$xmldoc.=<<<XMLDOC
	
<tacinfo>	
	<!-- hosts -->
	<hoststatustotals>
		<down>
			<total><?php echo $td['hostsDownTotal; ?></total>
			<unhandled><?php echo $td['hostsDownUnhandled; ?></unhandled>
			<scheduleddowntime><?php echo $td['hostsDownScheduled; ?></scheduleddowntime>	
			<acknowledged><?php echo $td['hostsDownAcknowledged; ?></acknowledged>
			<disabled><?php echo $td['hostsDownDisabled; ?></disabled>
		</down>
		<unreachable>
			<total><?php echo $td['hostsUnreachableTotal; ?></total>
			<unhandled><?php echo $td['hostsUnreachableUnhandled; ?></unhandled>
			<scheduledunreachabletime><?php echo $td['hostsUnreachableScheduled; ?></scheduledunreachabletime>
			<acknowledged><?php echo $td['hostsUnreachableAcknowledged; ?></acknowledged>
			<disabled><?php echo $td['hostsUnreachableDisabled; ?></disabled>
		</unreachable>	
		<up>
			<total><?php echo $td['hostsUpTotal; ?></total>
			<disabled><?php echo $td['hostsUpDisabled; ?></disabled>
		</up>
		<pending>
			<total><?php echo $td['hostsPending; ?></total>
			<disabled><?php echo $td['hostsPendingDisabled; ?></disabled>
		</pending>
	</hoststatustotals>
	
	<!-- services -->
	<servicestatustotals>
		<warning>
			<total><?php echo $td['servicesWarningTotal; ?></total>	
			<unhandled><?php echo $td['servicesWarningUnhandled; ?></unhandled>
			<scheduleddowntime><?php echo $td['servicesWarningScheduled; ?></scheduleddowntime>
			<acknowledged><?php echo $td['servicesWarningAcknowledged; ?></acknowledged>
			<hostproblem><?php echo $td['servicesWarningHostProblem; ?></hostproblem>
			<disabled><?php echo $td['servicesWarningDisabled; ?></disabled>
		</warning>
		<unknown>
			<total><?php echo $td['servicesUnknownTotal; ?></total>
			<unhandled><?php echo $td['servicesUnknownUnhandled; ?></unhandled>
			<scheduleddowntime><?php echo $td['servicesUnknownScheduled; ?></scheduleddowntime>	
			<acknowledged><?php echo $td['servicesUnknownAcknowledged; ?></acknowledged>
			<hostproblem><?php echo $td['servicesUnknownHostProblem; ?></hostproblem>
			<disabled><?php echo $td['servicesUnknownDisabled; ?></disabled>
		</unknown>
		<critical>
			<total><?php echo $td['servicesCriticalTotal; ?></total>
			<unhandled><?php echo $td['servicesCriticalUnhandled; ?></unhandled>
			<scheduleddowntime><?php echo $td['servicesCriticalScheduled; ?></scheduleddowntime>
			<acknowledged><?php echo $td['servicesCriticalAcknowledged; ?></acknowledged>
			<hostproblem><?php echo $td['servicesCriticalHostProblem; ?></hostproblem>	
			<disabled>2</disabled>
		</critical>
		<ok>
			<total><?php echo $td['servicesOkTotal; ?></total>
			<disabled><?php echo $td['servicesOkDisabled; ?></disabled>
		</ok>
		<pending>
			<total><?php echo $td['servicesPending; ?></total>
			<disabled><?php echo $td['servicesPendingDisabled; ?></disabled>
		</pending>
	</servicestatustotals>
	
	<!-- monitoring features -->
	<monitoringfeaturestatus>	
		<flapdetection>
			<global><?php echo $td['flap_detection; ?></global>
		</flapdetection>
		<notifications>
			<global><?php echo $td['notifications; ?></global>
		</notifications>
		<eventhandlers>
			<global><?php echo $td['event_handlers; ?></global>
		</eventhandlers>
		<activeservicechecks>
			<global><?php echo $td['active_service_checks; ?></global>
		</activeservicechecks>
		<passiveservicechecks>		
			<global><?php echo $td['passive_service_checks; ?></global>
		</passiveservicechecks>
	</monitoringfeaturestatus>
</tacinfo>

XMLDOC;

	return $xmldoc; 	

}