<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Need to be able to do more custom includes that we don't want as part of the singleton
 */
class VS_Loader extends CI_Loader
{


	

	/**
	 * Initialize the Loader
	 *
	 * This method is called once in CI_Controller.
	 *
	 * @param 	array
	 * @return 	object
	 */
	public function initialize()
	{
	
		$this->_load_factory();

		return parent::initialize();
	}


	/**
	 * Allow for factory modules to be loaded into the main includes 
	 * These are classes we want to be able to call independently of the singleton
	 * @return null
	 */
	protected function _load_factory(){
		if (defined('ENVIRONMENT') AND file_exists(APPPATH.'config/'.ENVIRONMENT.'/autoload.php'))
		{
			include(APPPATH.'config/'.ENVIRONMENT.'/autoload.php');
		}
		else
		{
			include(APPPATH.'config/autoload.php');
		}

		if ( ! isset($autoload))
		{
			return FALSE;
		}

		if(isset($autoload['factory'])){

			foreach($autoload['factory'] as $factory){

				$file = APPPATH.FACTORY.$factory;

				if(is_dir($file)){
					$files = scandir($file);
					foreach($files as $subfile){
						if($subfile[0]!='.'){
							include_once($file.'/'.$subfile);
						}
					}
					
				} elseif(file_exists($file.'.php') ) {
					include_once($file.'.php');
				} else {
					show_error('Unable to locate the factory you have specified: '.$factory.' in location: '.$file);
				}
			}
		} 

	}
	

}	