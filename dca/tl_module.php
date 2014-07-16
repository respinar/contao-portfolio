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

$GLOBALS['TL_DCA']['tl_module']['palettes']['customers_list']   = '{title_legend},name,headline,type;{customers},customers_category;{config_legend},customers_featured,customers_detailModule;{template_legend:hide},numberOfItems,perPage,imgSize,customerClass;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['customers_detail'] = '{title_legend},name,headline,type;{template_legend:hide},imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['customers_category'] = array
(
	'label'                => &$GLOBALS['TL_LANG']['tl_module']['customers_category'],
	'exclude'              => true,
	'inputType'            => 'radio',
	'foreignKey'           => 'tl_customers_category.title',
	'eval'                 => array('multiple'=>true, 'mandatory'=>true),
    'sql'                  => "blob NULL"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['customers_featured'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['customers_featured'],
	'default'                 => 'all_customers',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('all_customers', 'feature_customers', 'unfeature_customers'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(20) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['customers_detailModule'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['customers_detailModule'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_customers', 'getReaderModules'),
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

class tl_module_customers extends Backend
{

	/**
	 * Get all customers detail modules and return them as array
	 * @return array
	 */
	public function getReaderModules()
	{
		$arrModules = array();
		$objModules = $this->Database->execute("SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE m.type='customers_detail' ORDER BY t.name, m.name");

		while ($objModules->next())
		{
			$arrModules[$objModules->theme][$objModules->id] = $objModules->name . ' (ID ' . $objModules->id . ')';
		}

		return $arrModules;
	}

}

