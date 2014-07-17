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
 * Add palettes to tl_module
 */

$GLOBALS['TL_DCA']['tl_module']['palettes']['portfolio_customer_list']   = '{title_legend},name,headline,type;{portfolio_legend},portfolio_category;{config_legend},customer_featured,customer_detailModule;{template_legend:hide},numberOfItems,perPage,imgSize,customerClass;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['portfolio_customer_detail'] = '{title_legend},name,headline,type;{template_legend:hide},imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_module']['palettes']['portfolio_project_list']    = '{title_legend},name,headline,type;{portfolio_legend},portfolio_category;{project_legend},project_status,project_featured;{config_legend},projects_detailModule;{template_legend:hide},numberOfItems,perPage,imgSize,projectClass;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['portfolio_project_detail']  = '{title_legend},name,headline,type;{template_legend:hide},imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';



/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['portfolio_category'] = array
(
	'label'                => &$GLOBALS['TL_LANG']['tl_module']['portfolio_category'],
	'exclude'              => true,
	'inputType'            => 'radio',
	'foreignKey'           => 'tl_portfolio.title',
	'eval'                 => array('multiple'=>true, 'mandatory'=>true),
    'sql'                  => "blob NULL"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['customer_featured'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['customer_featured'],
	'default'                 => 'all_customers',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('all_customers', 'feature_customers', 'unfeature_customers'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(20) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['customer_detailModule'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['customer_detailModule'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_portfolio', 'getCustomerReaderModules'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['customerClass'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['customerClass'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>128, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
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
	'options_callback'        => array('tl_module_portfolio', 'getProjectReaderModules'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['projectClass'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['projectClass'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>128, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);

class tl_module_portfolio extends Backend
{

	/**
	 * Get all customer detail modules and return them as array
	 * @return array
	 */
	public function getCustomerReaderModules()
	{
		$arrModules = array();
		$objModules = $this->Database->execute("SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE m.type='portfolio_customer_detail' ORDER BY t.name, m.name");

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
	public function getProjectReaderModules()
	{
		$arrModules = array();
		$objModules = $this->Database->execute("SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE m.type='portfolio_project_detail' ORDER BY t.name, m.name");

		while ($objModules->next())
		{
			$arrModules[$objModules->theme][$objModules->id] = $objModules->name . ' (ID ' . $objModules->id . ')';
		}

		return $arrModules;
	}

}

