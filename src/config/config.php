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
		'clients' => array
		(
			'tables' => array('tl_portfolio_client_category','tl_portfolio_client'),
			'icon'   => 'system/modules/portfolio/assets/clients.png'
		),
		'projects' => array
		(
			'tables' => array('tl_portfolio_project_category','tl_portfolio_project','tl_content'),
			'icon'   => 'system/modules/portfolio/assets/projects.png'
		)
	)
));

/**
 * Register models
 */
 $GLOBALS['TL_MODELS']['tl_portfolio_client']           = 'Respinar\Portfolio\Model\PortfolioClientModel';
 $GLOBALS['TL_MODELS']['tl_portfolio_client_category']  = 'Respinar\Portfolio\Model\PortfolioClientCategoryModel'; 
 $GLOBALS['TL_MODELS']['tl_portfolio_project']          = 'Respinar\Portfolio\Model\PortfolioProjectModel';
 $GLOBALS['TL_MODELS']['tl_portfolio_project_category'] = 'Respinar\Portfolio\Model\PortfolioProjectCategoryModel'; 

/**
 * Front end modules
 */
array_insert($GLOBALS['FE_MOD'], 2, array
(
	'portfolio' => array
	(
		'portfolio_client_list'    => 'Respinar\Portfolio\Frontend\Module\ModulePortfolioClientList',
		'portfolio_client_detail'  => 'Respinar\Portfolio\Frontend\Module\ModulePortfolioClientDetail',
		'portfolio_project_list'   => 'Respinar\Portfolio\Frontend\Module\ModulePortfolioProjectList',
		'portfolio_project_detail' => 'Respinar\Portfolio\Frontend\Module\ModulePortfolioProjectDetail'
	)
));

/**
 * Register hook to add carpets items to the indexer
 */
$GLOBALS['TL_HOOKS']['getSearchablePages'][]     = array('Respinar\Portfolio\Portfolio', 'getSearchablePages');
