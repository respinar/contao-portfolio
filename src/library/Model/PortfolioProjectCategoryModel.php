<?php

/*
 * Portfolio module for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2014-2017, Respinar
 * @author     Hamid Abbaszadeh <https://respinar.com>
 * @license    GNU/LGPL-3+
 */


/**
 * Namespace
 */
namespace Respinar\Portfolio\Model;

/**
 * Class PortfolioProjectCategoryModel
 *
 * @copyright  2014
 * @author     Hamid Abbaszadeh
 * @package    Devtools
 */
class PortfolioProjectCategoryModel extends \Model
{

	/**
	 * Name of the table
	 * @var string
	 */
	protected static $strTable = 'tl_portfolio_project_category';

	public static function findPublishedById($intId)
	{
		$t = static::$strTable;

		$arrColumns = array("$t.id=?");

		return static::findBy($arrColumns, $intId);
	}

}
