<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Portfolio
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'portfolio',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Models
	'portfolio\PortfolioCustomerModel'        => 'system/modules/portfolio/models/PortfolioCustomerModel.php',
	'portfolio\PortfolioModel'                => 'system/modules/portfolio/models/PortfolioModel.php',
	'portfolio\PortfolioProjectModel'         => 'system/modules/portfolio/models/PortfolioProjectModel.php',

	// Modules
	'portfolio\ModulePortfolioProjectList'    => 'system/modules/portfolio/modules/ModulePortfolioProjectList.php',
	'portfolio\ModulePortfolioCustomerDetail' => 'system/modules/portfolio/modules/ModulePortfolioCustomerDetail.php',
	'portfolio\ModulePortfolioCustomerList'   => 'system/modules/portfolio/modules/ModulePortfolioCustomerList.php',
	'portfolio\ModulePortfolioProjectDetail'  => 'system/modules/portfolio/modules/ModulePortfolioProjectDetail.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_portfolio_customer_detail' => 'system/modules/portfolio/templates/customer',
	'mod_portfolio_customer_list'   => 'system/modules/portfolio/templates/customer',
	'mod_portfolio_customer_empty'  => 'system/modules/portfolio/templates/customer',
	'mod_portfolio_project_detail'  => 'system/modules/portfolio/templates/project',
	'mod_portfolio_project_empty'   => 'system/modules/portfolio/templates/project',
	'mod_portfolio_project_list'    => 'system/modules/portfolio/templates/project',
));
