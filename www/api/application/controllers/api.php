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
        $Data['username'] = $this->nagios_user->get_username();
        $this->output($Data);
    }

    /**
     * Fetch object names
     */
    public function quicksearch()
    {

        $Data = array();

        $hosts = $this->nagios_data->get_collection('hoststatus');
        $services = $this->nagios_data->get_collection('servicestatus');
        $hostgroups = $this->nagios_data->get_collection('hostgroup');
        $servicegroups = $this->nagios_data->get_collection('servicegroup');

        foreach($hosts as $host){
            $Data[] = $this->quicksearch_item('host', $host->host_name, $host->host_name);
        }

        foreach($services as $service){
            $Data[] = $this->quicksearch_item('service', $service->service_description.' on '.$service->host_name, $service->host_name.'/'.$service->id);
        }

        foreach($hostgroups as $hostgroup){
            $Data[] = $this->quicksearch_item('hostgroup', $hostgroup->alias, $hostgroup->hostgroup_name);
        }

        foreach($servicegroups as $servicegroup){
            $Data[] = $this->quicksearch_item('servicegroup', $servicegroup->alias, $servicegroup->servicegroup_name);
        }

        $this->output($Data);

    }

    private function quicksearch_item($type, $name, $uri)
    {
        return array(
            'type' => $type,
            'name' => $name,
            'uri' => $uri
        );
    }


    /**
     * Retrieve tactical overview data
     */
    public function tacticaloverview() {
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
            $Data = $Data->get_index_key('host_name',$host_name)->first();
            //add comments
            $all_comments = $this->nagios_data->get_collection('hostcomment');
            $host_comments = $all_comments->get_index_key('host_name',$host_name);
            $Data->hostcomments = $host_comments ? $host_comments : array();
        }

        $this->output($Data);

    }


    /**
     * Retrieve service status objects based on parameters
     * @param  string $host_name host name filter
     * @param  string $service   service description (requires host name)
     */
    public function servicestatus($host_name='',$service_description=''){
        $service_description = urldecode($service_description);

        $Data = $this->nagios_data->get_collection('servicestatus');

        //fetch by host name
        if(!empty($host_name)){

            if(empty($service_description)){
                $Data = $Data->get_index_key('host_name',$host_name);
            } else {
                $Data = $Data->get_index_key('host_name',$host_name)->get_where('service_description',$service_description)->first();
                //add comments
                $all_comments = $this->nagios_data->get_collection('servicecomment');
                $service_comments = $all_comments->get_where('service_description',$service_description);
                $Data->servicecomments = $service_comments ? $service_comments : array();
            }

        }

        $this->output($Data);
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
            if( $hostgroup_name != '' ) {
                if( $Hostgroup->hostgroup_name == $hostgroup_name ){
                    $Hostgroup->hydrate();
                    $HostgroupStatus[] = $Hostgroup;
                }
            }else{
                $Hostgroup->hydrate();
                $HostgroupStatus[] = $Hostgroup;
            }
        }

        $this->output($HostgroupStatus);

    }

    public function servicegroupstatus($servicegroup_name = ''){

        $ServicegroupStatus = new ServiceStatusCollection();
        $Servicegroups = $this->nagios_data->get_collection('servicegroup');

        foreach($Servicegroups as $Servicegroup){
            if( $servicegroup_name != '' ) {
                if( $Servicegroup->servicegroup_name == $servicegroup_name ){
                    $Servicegroup->hydrate();
                    $ServicegroupStatus[] = $Servicegroup;
                }
            }else{
                $Servicegroup->hydrate();
                $ServicegroupStatus[] = $Servicegroup;
            }
        }

        $this->output($ServicegroupStatus);

    }

    public function configurations($type = '')
    {
        $configurations = array();

        $key_lookup = array(
            'hosts'         => 'hosts_objs',
            'services'      => 'services_objs',
            'hostgroups'    => 'hostgroups_objs',
            'servicegroups' => 'servicegroups_objs',
            'timeperiods'   => 'timeperiods',
            'contacts'      => 'contacts',
            'contactgroups' => 'contactgroups',
            'commands'      => 'commands'
        );

        $keys = array();

        if( $type != '' ){
            if( isset($key_lookup[$type]) ){
                $keys[$type] = $key_lookup[$type];
            }
        }else{
            $keys = $key_lookup;
        }

        foreach($keys as $name => $objtype){

            $data = object_data($objtype);

            $configurations[$name] = array(
                'items'   => $data,
                'name'    => $name,
                'objtype' => $objtype,
            );
        }

        $this->output($configurations);
    }

    /**
     * Fetch all comments or only those of a certain type.
     * Returns a flat array of comment objects.
     *
     * @param  string $type
     */
    public function comments($type='') {

        function flatten($array){
            $flattened = array();
            foreach($array as $comments){
                $flattened[] = $comments[0];
            }
            return $flattened;
        }

        function merge($first, $second){
            $first = flatten($first);
            $second = flatten($second);
            return array_merge($first, $second);
        }

        if( $type == '' ){
            $host_comments = $this->nagios_data->get_collection('hostcomment')->get_index('host_name');
            $service_comments = $this->nagios_data->get_collection('servicecomment')->get_index('host_name');
            $comments = merge($host_comments, $service_comments);
        } else {
            $specific_comments = $this->nagios_data->get_collection($type);
            $comments = flatten($specific_comments);
        }

        $this->output($comments);
    }


    /**
     * Fetch /etc/vshell2.conf file values, as parsed by CodeIgniter
     *
     * @param  string $type
     */
    function vshellconfig() {
        $config = array(
            'baseurl'        => BASEURL,
            'cgicfg'         => CGICFG,
            'coreurl'        => COREURL,
            'lang'           => LANG,
            'objectsfile'    => OBJECTSFILE,
            'statusfile'     => STATUSFILE,
            'ttl'            => TTL,
            'updateinterval' => UPDATEINTERVAL
        );

        $this->output($config);
    }

}

/* End of file api.php */
/* Location: ./application/controllers/api.php */
