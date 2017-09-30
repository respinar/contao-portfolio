<?php

/*
 * Portfolio module for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2014-2017, Respinar
 * @author     Hamid Abbaszadeh <https://respinar.com>
 * @license    GNU/LGPL-3+
 */


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
