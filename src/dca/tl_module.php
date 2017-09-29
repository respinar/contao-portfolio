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
 * Add palettes to tl_module
 */

$GLOBALS['TL_DCA']['tl_module']['palettes']['portfolio_client_list']    = '{title_legend},name,headline,type;
                                                                           {portfolio_legend},portfolio_client_categories;
                                                                           {config_legend},client_detailModule;
                                                                           {template_legend},client_featured,numberOfItems,perPage,skipFirst,portfolio_sortBy;
                                                                           {clients_legend},client_template,client_imgSize,portfolio_list_class,client_class,show_title;
                                                                           {protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['portfolio_client_detail']  = '{title_legend},name,headline,type;
                                                                           {portfolio_legend},portfolio_client_categories;
                                                                           {clients_legend},client_template,client_imgSize;
                                                                           {projects_legend},project_show;
                                                                           {protected_legend:hide},protected;
                                                                           {expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_module']['palettes']['portfolio_project_list']   = '{title_legend},name,headline,type;
                                                                           {portfolio_legend},portfolio_project_categories;
                                                                           {projects_legend},project_detailModule;
                                                                           {config_legend},projects_detailModule;
                                                                           {template_legend:hide},project_status,project_featured,numberOfItems,perPage,skipFirst,portfolio_sortBy;
                                                                           {projects_legend},project_template,project_imgSize,portfolio_list_class,project_class;
                                                                           {protected_legend:hide},protected;
                                                                           {expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['portfolio_project_detail'] = '{title_legend},name,headline,type;
                                                                           {portfolio_legend},portfolio_project_categories;
                                                                           {template_legend:hide},project_template,project_imgSize;
                                                                           {client_legend},client_show;
                                                                           {protected_legend:hide},protected;
                                                                           {expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'client_show';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['client_show'] = 'client_template,client_imgSize';

$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'project_show';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['project_show'] = 'project_template,project_perRow,project_class,project_imgSize';



/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['portfolio_client_categories'] = array
(
	'label'                => &$GLOBALS['TL_LANG']['tl_module']['portfolio_client_categories'],
	'exclude'              => true,
	'inputType'            => 'checkbox',
	'foreignKey'           => 'tl_portfolio_client_category.title',
	'eval'                 => array('multiple'=>true, 'mandatory'=>true),
    'sql'                  => "blob NULL"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['portfolio_project_categories'] = array
(
	'label'                => &$GLOBALS['TL_LANG']['tl_module']['portfolio_project_categories'],
	'exclude'              => true,
	'inputType'            => 'checkbox',
	'foreignKey'           => 'tl_portfolio_project_category.title',
	'eval'                 => array('multiple'=>true, 'mandatory'=>true),
    'sql'                  => "blob NULL"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['portfolio_sortBy'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['portfolio_sortBy'],
	'default'                 => 'custom',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('custom','title_asc', 'title_desc', 'date_asc', 'date_desc'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(16) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['portfolio_list_class'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['portfolio_list_class'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>128, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['client_featured'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['client_featured'],
	'default'                 => 'all_clients',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('all_clients', 'feature_clients', 'unfeature_clients'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(20) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['client_detailModule'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['client_detailModule'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_portfolio', 'getClientDetailModules'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['client_class'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['client_class'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>128, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['client_imgSize'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['client_imgSize'],
	'exclude'                 => true,
	'inputType'               => 'imageSize',
	'options'                 => System::getImageSizes(),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'helpwizard'=>true, 'tl_class'=>'w50'),
	'sql'                     => "varchar(64) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['client_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['client_template'],
	'default'                 => 'client_full',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_portfolio', 'getClientTemplates'),
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(32) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['client_show'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['client_show'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['show_title'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['show_title'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array(),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['project_featured'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['project_featured'],
	'default'                 => 'all_projects',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('all_projects', 'feature_projects', 'unfeature_projects'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(20) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['project_status'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['project_status'],
	'default'                 => 'all',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('all','planed', 'started', 'completed'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(20) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['project_detailModule'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['project_detailModule'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_portfolio', 'getProjectDetailModules'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['project_class'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['project_class'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>128, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['project_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['project_template'],
	'default'                 => 'client_full',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_portfolio', 'getProjectTemplates'),
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(32) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['project_imgSize'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['project_imgSize'],
	'exclude'                 => true,
	'inputType'               => 'imageSize',
	'options'                 => System::getImageSizes(),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'helpwizard'=>true, 'tl_class'=>'w50'),
	'sql'                     => "varchar(64) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['project_show'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['project_show'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true),
	'sql'                     => "char(1) NOT NULL default ''"
);

class tl_module_portfolio extends Backend
{

	/**
	 * Get all client detail modules and return them as array
	 * @return array
	 */
	public function getClientDetailModules()
	{
		$arrModules = array();
		$objModules = $this->Database->execute("SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE m.type='portfolio_client_detail' ORDER BY t.name, m.name");

		while ($objModules->next())
		{
			$arrModules[$objModules->theme][$objModules->id] = $objModules->name . ' (ID ' . $objModules->id . ')';
		}

		return $arrModules;
	}

	/**
	 * Get all project detail modules and return them as array
	 * @return array
	 */
	public function getProjectDetailModules()
	{
		$arrModules = array();
		$objModules = $this->Database->execute("SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE m.type='portfolio_project_detail' ORDER BY t.name, m.name");

		while ($objModules->next())
		{
			$arrModules[$objModules->theme][$objModules->id] = $objModules->name . ' (ID ' . $objModules->id . ')';
		}

		return $arrModules;
	}

    /**
	 * Return all client templates as array
	 * @return array
	 */
	public function getClientTemplates()
	{
		return $this->getTemplateGroup('client_');
	}

    /**
	 * Return all project templates as array
	 * @return array
	 */
	public function getProjectTemplates()
	{
		return $this->getTemplateGroup('project_');
	}

}
