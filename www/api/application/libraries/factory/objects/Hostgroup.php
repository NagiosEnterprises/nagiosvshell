<?php

class Hostgroup extends NagiosGroup
{

	const HOSTUP = 0;
	const HOSTDOWN = 1;
	const HOSTUNREACHABLE = 2;

    const SERVICEOK = 0;
    const SERVICEWARNING = 1;
    const SERVICECRITICAL = 2;
    const SERVICEUNKNOWN = 3;
    const SERVICEPENDING = 4;
    const SERVICEPROBLEMS = 5;
    const SERVICEUNHANDLED = 6;
    const SERVICEACKNOWLEDGED = 7;

	protected $_type = 'hostgroup';

	protected static $_count;

	public $HostStatusCollection;

	public $hostsUp = 0;

	public $hostsDown = 0;

	public $hostsUnreachable = 0;

	public $hostsPending = 0;

    public $servicesOK = 0;

    public $servicesWarning = 0;

    public $servicesUnknown = 0;

    public $servicesCritical = 0;

    public $servicesPending = 0;


	/**
	 * Hydrate status data and host totals 
	 * @return [type] [description]
	 */
	public function hydrate(){

		$this->HostStatusCollection = new HostStatusCollection();
		$CI = get_instance();
		$AllHoststatus = $CI->nagios_data->get_collection('hoststatus');
		$AllServicestatus= $CI->nagios_data->get_collection('servicestatus');

        if( property_exists($this, 'members') ){
		    foreach(explode(',',$this->members) as $hostname){

			    //get the host status by host name
                $Hoststatus = $AllHoststatus->get_index_key('host_name',$hostname)->first();

                //Get services for this host 
                $Servicestatus = $AllServicestatus->get_index_key('host_name',$hostname);

                $this->_add($Hoststatus, $Servicestatus);
        
		    }
        }
	}

	/**
	 * Adds a Hoststatus into the hostgroups status collection. Tallies state totals 
	 * @param HostStatus $Hoststatus [description]
	 */
	protected function _add(HostStatus $Hoststatus, $Servicestatus = ''){
		if($Hoststatus->current_state==self::HOSTUP && $Hoststatus->last_check==0){
			$this->hostsPending++;
		} elseif($Hoststatus->current_state==self::HOSTUP){
			$this->hostsUp++;
		}elseif($Hoststatus->current_state==self::HOSTDOWN){
			$this->hostsDown++;
		} else {
			$this->hostsUnreachable++;
		}

        if( $Servicestatus != NULL ){
            foreach($Servicestatus as $service){
                if($service->current_state==self::SERVICEOK && $service->last_check==0){
                    $this->servicesPending++;
                } elseif($service->current_state==self::SERVICEOK){
                    $this->servicesOK++;
                }elseif($service->current_state==self::SERVICEWARNING){
                    $this->servicesWarning++;
                }elseif($service->current_state==self::SERVICEUNKNOWN){
                    $this->servicesUnknown++;
                } else {
                    $this->servicesCritical++;
                }
            }
        }

        $Hoststatus->ServiceStatusCollection = $Servicestatus;
		$this->HostStatusCollection[] = $Hoststatus;
    }

}
