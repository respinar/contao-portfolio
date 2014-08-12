<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package   customers
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
		'tables' => array('tl_portfolio','tl_portfolio_customer','tl_portfolio_project'),
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
        'portfolio_customer_list'   => 'ModulePortfolioCustomerList',
		'portfolio_customer_detail' => 'ModulePortfolioCustomerDetail',
		'portfolio_project_list'    => 'ModulePortfolioProjectList',
		'portfolio_project_detail'  => 'ModulePortfolioProjectDetail'
	)
));
