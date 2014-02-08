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

        echo get_class($this->nagios_data);
        echo 'hello world';
    }


    /**
     * Fetch status of a certain type
     *
     * @param  string $type 
     * @param  string $name [description]
     * @return [type]       [description]
     */
    public function hoststatus($hostname='') {
        
        $Data = $this->nagios_data->get_collection('hoststatus');

        //fetch by host name
        if(!empty($host_name)){
            $Data = $Data->get_index_key('host_name',$host_name);
        }

        $this->output($Data->to_array());

    }



    public function host_by_id($id){
        $Data = $this->nagios_data->get_collection('hoststatus');
        $this->output($Data[$id]->to_array());
    }


    public function servicestatus($host_name='',$service=''){

        $Data = $this->nagios_data->get_collection('servicestatus');

        //fetch by host name
        if(!empty($host_name)){

            $Data = $Data->get_index_key('host_name',$host_name)->get_where('service_description',$service);

        }

        $this->output($Data->to_array());
    }



    public function service_by_id($id) {
        $Data = $this->nagios_data->get_collection('servicestatus');
        $this->output($Data[$id]->to_array());
    }



    public function programstatus(){
        $Data = $this->nagios_data->get_collection('programstatus');
        $this->output($Data->to_array());
    }

    public function info(){
        $Data = $this->nagios_data->get_collection('info');
        $this->output($Data->to_array());
    }


    public function object($type,$name='') {
        $Data = $this->nagios_data->get_collection($type);

        $this->output($Data->to_array());
    }







}

/* End of file api.php */
/* Location: ./application/controllers/api.php */
