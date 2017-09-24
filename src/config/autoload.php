<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register PSR-0 namespaces
 */
 if (class_exists('NamespaceClassLoader')) {
    NamespaceClassLoader::add('Respinar\Portfolio', 'system/modules/portfolio/src');
}


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
