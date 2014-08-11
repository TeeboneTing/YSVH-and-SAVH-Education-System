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
$route['personnel/import_new_employ'] = "control/import_new_employ";
$route['personnel/update_employ'] = "control/update_employ";
$route['get_DPNAME/(:num)/(:num)'] = "control/get_DPNAME/$1/$2";
$route['get_all_DPNAME/(:num)'] = "control/get_all_DPNAME/$1";
$route['get_remote_employ_by_ID/(:num)/(:any)'] = "control/get_remote_employ_by_ID/$1/$2";
$route['(:any)'] = "control/$1";
$route['default_controller'] = "control/home";
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */