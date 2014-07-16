<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Customers
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'customers',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Models
	'customers\CustomersModel'         => 'system/modules/customers/models/CustomersModel.php',
	'customers\CustomersCategoryModel' => 'system/modules/customers/models/CustomersCategoryModel.php',

	// Modules
	'customers\ModuleCustomersDetail'  => 'system/modules/customers/modules/ModuleCustomersDetail.php',
	'customers\ModuleCustomersList'    => 'system/modules/customers/modules/ModuleCustomersList.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_customers_list'   => 'system/modules/customers/templates',
	'mod_customers_empty'  => 'system/modules/customers/templates',
	'mod_customers_detail' => 'system/modules/customers/templates',
));
