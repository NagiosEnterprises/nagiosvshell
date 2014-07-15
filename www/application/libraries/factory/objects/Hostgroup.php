<?php

class Hostgroup extends NagiosGroup
{

	const HOSTUP = 0;
	const HOSTDOWN = 1;
	const HOSTUNREACHABLE = 2;


	protected $_type = 'hostgroup';

	protected static $_count;

	public $HostStatusCollection;

	public $hostsUp = 0;

	public $hostsDown = 0;

	public $hostsUnreachable = 0;

	public $hostsPending = 0;

	/**
	 * Hydrate status data and host totals 
	 * @return [type] [description]
	 */
	public function hydrate(){

		$this->HostStatusCollection = new HostStatusCollection();
		$CI = get_instance();
		$AllHoststatus = $CI->nagios_data->get_collection('hoststatus');
		$AllServicestatus= $CI->nagios_data->get_collection('servicestatus');

		foreach(explode(',',$this->members) as $hostname){

			//get the host status by host name
            $Hoststatus = $AllHoststatus->get_index_key('host_name',$hostname)->first();

            //Get services for this host 
            $Services = $AllServicestatus->get_index_key('host_name',$hostname);

            //Push collection into host 
            $Hoststatus->ServiceStatusCollection = $Services;

            $this->_add($Hoststatus);
       
		}
	}

	/**
	 * Adds a Hoststatus into the hostgroups status collection. Tallies state totals 
	 * @param HostStatus $Hoststatus [description]
	 */
	protected function _add(HostStatus $Hoststatus){
		$this->HostStatusCollection[] = $Hoststatus;

		if($Hoststatus->current_state==self::HOSTUP && $Hoststatus->last_check==0){
			$this->hostsPending++;
		} elseif($Hoststatus->current_state==self::HOSTUP){
			$this->hostsUp++;
		}elseif($Hoststatus->current_state==self::HOSTDOWN){
			$this->hostsDown++;
		} else {
			$this->hostsUnreachable++;
		}

	}


}