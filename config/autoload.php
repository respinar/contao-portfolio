<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
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
	'portfolio\PortfolioProjectModel'         => 'system/modules/portfolio/models/PortfolioProjectModel.php',
	'portfolio\PortfolioClientModel'          => 'system/modules/portfolio/models/PortfolioClientModel.php',
	'portfolio\PortfolioClientCategoryModel'  => 'system/modules/portfolio/models/PortfolioClientCategoryModel.php',
	'portfolio\PortfolioProjectCategoryModel' => 'system/modules/portfolio/models/PortfolioProjectCategoryModel.php',
	'portfolio\PortfolioProvinceModel'        => 'system/modules/portfolio/models/PortfolioProvinceModel.php',


	// Modules
	'portfolio\ModulePortfolioProjectList'    => 'system/modules/portfolio/modules/ModulePortfolioProjectList.php',
	'portfolio\ModulePortfolio'               => 'system/modules/portfolio/modules/ModulePortfolio.php',
	'portfolio\ModulePortfolioProjectDetail'  => 'system/modules/portfolio/modules/ModulePortfolioProjectDetail.php',
	'portfolio\ModulePortfolioClientDetail'   => 'system/modules/portfolio/modules/ModulePortfolioClientDetail.php',
	'portfolio\ModulePortfolioClientList'     => 'system/modules/portfolio/modules/ModulePortfolioClientList.php',

	// Classes
	'Contao\Portfolio'                        => 'system/modules/portfolio/classes/Portfolio.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'project_short'                => 'system/modules/portfolio/templates/project',
	'project_full'                 => 'system/modules/portfolio/templates/project',
	'mod_portfolio_project_list'   => 'system/modules/portfolio/templates/modules',
	'mod_portfolio_project_detail' => 'system/modules/portfolio/templates/modules',
	'mod_portfolio_client_detail'  => 'system/modules/portfolio/templates/modules',
	'mod_portfolio_client_list'    => 'system/modules/portfolio/templates/modules',
	'client_full'                  => 'system/modules/portfolio/templates/client',
	'client_short'                 => 'system/modules/portfolio/templates/client',
));
