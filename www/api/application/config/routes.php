<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route["default_controller"] = "api";
$route["404_override"] = "";

$route["status"] = "api";

$route["comments/(:any)"] = "api/comments/$1";
$route["comments"] = "api/comments";
$route["configurations/(:any)"] = "api/configurations/$1";
$route["configurations"] = "api/configurations";
$route["hostgroupstatus/(:any)"] = "api/hostgroupstatus/$1";
$route["hostgroupstatus*"] = "api/hostgroupstatus";
$route["hoststatus/(:any)"] = "api/hoststatus/$1";
$route["hoststatus*"] = "api/hoststatus";
$route["overview"] = "api/tacticaloverview";
$route["nagiosinfo"] = "api/info";
$route["nagiosstatus"] = "api/programstatus";
$route["quicksearch"] = "api/quicksearch";
$route["servicegroupstatus/(:any)"] = "api/servicegroupstatus/$1";
$route["servicegroupstatus*"] = "api/servicegroupstatus";
$route["servicestatus/(:any)/(:any)"] = "api/servicestatus/$1/$2";
$route["servicestatus/(:any)"] = "api/servicestatus/$1";
$route["servicestatus*"] = "api/servicestatus";
$route["vshellconfig"] = "api/vshellconfig";

/* End of file routes.php */
/* Location: ./application/config/routes.php */
