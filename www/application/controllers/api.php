<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class API extends VS_Controller
{


    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Default to Tactical overview data
     */
    public function index()
    {
        $Data = $this->tac_data->get_tac_data();
        $this->output($Data);
    }


    /**
     * Fetch status of a certain type
     *
     * @param  string $type 
     * @param  string $host_name
     */
    public function hoststatus($host_name='') {
        
        $Data = $this->nagios_data->get_collection('hoststatus');

        //fetch by host name
        if(!empty($host_name)){
            $Data = $Data->get_index_key('host_name',$host_name);
        }

        $this->output($Data);

    }


    /**
     * Retrieves a host by numeric ID
     * @param  int $id host $id property
     */
    public function host_by_id($id){
        $Hosts = $this->nagios_data->get_collection('hoststatus');
        $this->output($Hosts[$id]);
    }


    /**
     * Retrieve service status objects based on parameters
     * @param  string $host_name host name filter
     * @param  string $service   service description (requires host name)
     */
    public function servicestatus($host_name='',$service=''){

        $Data = $this->nagios_data->get_collection('servicestatus');

        //fetch by host name
        if(!empty($host_name)){

            if(empty($service)){
                 $Data = $Data->get_index_key('host_name',$host_name);
            } else {

                $Data = $Data->get_index_key('host_name',$host_name)->get_where('service_description',$service);
            }    

        }

        $this->output($Data);
    }


    /**
     * Retrieve a single service by $id property
     * @param  int $id property
     */
    public function service_by_id($id) {
        $Services = $this->nagios_data->get_collection('servicestatus');
        $this->output($Services[$id]);
    }


    /**
     * Retrieve program status
     */
    public function programstatus(){
        $Program = $this->nagios_data->get_collection('programstatus');
        $this->output($Program);
    }


    /**
     * Retrieve info status
     * @return [type] [description]
     */
    public function info(){
        $Info = $this->nagios_data->get_collection('info');
        $this->output($Info);
    }


    /**
     * Retrieve object data, either the collection or a single record 
     * @param  string $type Object type
     * @param  string $name object's name field
     */
    public function object($type,$name='') {
        $Object = $this->nagios_data->get_collection($type);

        $this->output($Object);
    }


    public function hostgroupstatus($hostgroup_name = ''){

        $HostgroupStatus = new HostStatusCollection();
        $Hostgroups = $this->nagios_data->get_collection('hostgroup');

        foreach($Hostgroups as $Hostgroup){
            $Hostgroup->hydrate();
            $HostgroupStatus[] = $Hostgroup; 

        }

        $this->output($HostgroupStatus);


    }




}

/* End of file api.php */
/* Location: ./application/controllers/api.php */
