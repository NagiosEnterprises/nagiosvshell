<?php

class Servicegroup extends NagiosGroup
{

    const SERVICEOK = 0;
    const SERVICEWARNING = 1;
    const SERVICECRITICAL = 2;
    const SERVICEUNKNOWN = 3;
    const SERVICEPENDING = 4;
    const SERVICEPROBLEMS = 5;
    const SERVICEUNHANDLED = 6;
    const SERVICEACKNOWLEDGED = 7;

    protected $_type = 'servicegroup';

    protected static $_count;

    public $ServiceStatusCollection;

    public $servicesOK = 0;

    public $servicesWarning = 0;

    public $servicesUnknown = 0;

    public $servicesCritical = 0;

    public $servicesPending = 0;

    /**
     * Hydrate status data and service totals
     * @return [type] [description]
     */
    public function hydrate(){
        $this->ServiceStatusCollection = new ServiceStatusCollection();
        $CI = get_instance();
        $AllServicestatus= $CI->nagios_data->get_collection('servicestatus');

        // Extract host_name[0] and service_name[1] from members list
        if( property_exists($this, 'members') ){
            $pieces = explode(',', $this->members);
            for( $i = 0; $i < count($pieces); $i = $i + 2 ){
                $host_name = $pieces[$i];
                $service = $pieces[$i + 1];

                $Servicestatus = $AllServicestatus->get_index_key('host_name', $host_name)->get_where('service_description', $service)->first();

                $this->_add($Servicestatus);
            }
        }
    }

    /**
     * Adds a Servicestatus into the servicegroups status collection. Tallies state totals
     * @param ServiceStatus $Servicestatus [description]
     */
    protected function _add(ServiceStatus $Servicestatus){
        $this->ServiceStatusCollection[] = $Servicestatus;

        if($Servicestatus->current_state==self::SERVICEOK && $Servicestatus->last_check==0){
            $this->servicesPending++;
        } elseif($Servicestatus->current_state==self::SERVICEOK){
            $this->servicesOK++;
        }elseif($Servicestatus->current_state==self::SERVICEWARNING){
            $this->servicesWarning++;
        }elseif($Servicestatus->current_state==self::SERVICEUNKNOWN){
            $this->servicesUnknown++;
        } else {
            $this->servicesCritical++;
        }
    }

}
