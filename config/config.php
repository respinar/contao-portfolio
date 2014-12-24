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
array_insert($GLOBALS['BE_MOD']['content'], 1, array
(
	'portfolio' => array
	(
		'tables' => array('tl_portfolio','tl_portfolio_client','tl_portfolio_project'),
		'icon'   => 'system/modules/portfolio/assets/icon.png'
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
