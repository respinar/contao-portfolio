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
	'portfolio\ModulePortfolio'               => 'system/modules/portfolio/modules/ModulePortfolio.php',

	// Classes
	'Contao\Portfolio'                        => 'system/modules/portfolio/classes/Portfolio.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'customer_full'                 => 'system/modules/portfolio/templates/customer',
	'customer_short'                => 'system/modules/portfolio/templates/customer',
	'project_full'                  => 'system/modules/portfolio/templates/project',
	'project_short'                 => 'system/modules/portfolio/templates/project',
	'mod_portfolio_customer_detail' => 'system/modules/portfolio/templates/modules',
	'mod_portfolio_customer_list'   => 'system/modules/portfolio/templates/modules',
	'mod_portfolio_project_detail'  => 'system/modules/portfolio/templates/modules',
	'mod_portfolio_project_list'    => 'system/modules/portfolio/templates/modules',
));
