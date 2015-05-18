<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package   portfolio
 * @author    Hamid Abbaszadeh
 * @license   GNU/LGPL
 * @copyright 2014
 */


/**
 * Back end modules
 */
array_insert($GLOBALS['BE_MOD'], 1, array
(
	'portfolio' => array
	(
		'client' => array
		(
			'tables' => array('tl_portfolio_client_category','tl_portfolio_client'),
			'icon'   => 'system/modules/portfolio/assets/clients.png'
		),
		'project' => array
		(
			'tables' => array('tl_portfolio_project_category','tl_portfolio_project'),
			'icon'   => 'system/modules/portfolio/assets/projects.png'
		)
	)
));


/**
 * Front end modules
 */

array_insert($GLOBALS['FE_MOD'], 2, array
(
	'portfolio' => array
	(
		'portfolio_client_list'    => 'ModulePortfolioClientList',
		'portfolio_client_detail'  => 'ModulePortfolioClientDetail',
		'portfolio_project_list'   => 'ModulePortfolioProjectList',
		'portfolio_project_detail' => 'ModulePortfolioProjectDetail'
	)
));

/**
 * Register hook to add carpets items to the indexer
 */
$GLOBALS['TL_HOOKS']['getSearchablePages'][]     = array('Portfolio', 'getSearchablePages');
