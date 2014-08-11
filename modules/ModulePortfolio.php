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
 * Namespace
 */
namespace portfolio;


/**
 * Class ModulePortfolio
 *
 * @copyright  2014
 * @author     Hamid Abbaszadeh
 * @package    Devtools
 */
abstract class ModulePortfolio extends \Module
{

	/**
	 * URL cache array
	 * @var array
	 */
	private static $arrUrlCache = array();

	/**
	 * Sort out protected archives
	 * @param array
	 * @return array
	 */
	protected function sortOutProtected($arrCategories)
	{
		if (BE_USER_LOGGED_IN || !is_array($arrCategories) || empty($arrCategories))
		{
			return $arrCategories;
		}

		$this->import('FrontendUser', 'User');
		$objCategory = \PortfolioModel::findMultipleByIds($arrCategories);
		$arrCategories = array();

		if ($objCategory !== null)
		{
			while ($objCategory->next())
			{
				if ($objCategory->protected)
				{
					if (!FE_USER_LOGGED_IN)
					{
						continue;
					}

					$groups = deserialize($objCategory->groups);

					if (!is_array($groups) || empty($groups) || !count(array_intersect($groups, $this->User->groups)))
					{
						continue;
					}
				}

				$arrCategories[] = $objCategory->id;
			}
		}

		return $arrCategories;
	}


	/**
	 * Parse one or more items and return them as array
	 * @param object
	 * @param boolean
	 * @return array
	 */
	protected function parseCustomers($objCustomers, $blnAddCategory=false)
	{
		$limit = $objCustomers->count();

		if ($limit < 1)
		{
			return array();
		}

		$count = 0;
		$arrCustomers = array();

		while ($objCustomers->next())
		{
			$arrCustomers[] = $this->parseCustomer($objCustomers, $blnAddCategory, ((++$count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even'), $count);
		}

		return $arrCustomers;
	}

	/**
	 * Parse one or more items and return them as array
	 * @param object
	 * @param boolean
	 * @return array
	 */
	protected function parseProjects($objProjects, $blnAddCategory=false)
	{
		$limit = $objProjects->count();

		if ($limit < 1)
		{
			return array();
		}

		$count = 0;
		$arrProjects = array();

		while ($objProjects->next())
		{
			$arrProjects[] = $this->parseProject($objProjects, $blnAddCategory, ((++$count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even'), $count);
		}

		return $arrProjects;
	}


	/**
	 * Parse an item and return it as string
	 * @param object
	 * @param boolean
	 * @param string
	 * @param integer
	 * @return string
	 */
	protected function parseCustomer($objCustomer, $blnAddCategory=false, $strClass='', $intCount=0)
	{
		global $objPage;

		$objTemplate = new \FrontendTemplate($this->customer_template);
		$objTemplate->setData($objCustomer->row());

		$objTemplate->class = (($this->setClass != '') ? ' ' . $this->setClass : '') . $strClass;
		$objTemplate->class = (($this->customerClass != '') ? ' ' . $this->customerClass : '') . $strClass;

		$objTemplate->title       = $objCustomer->title;
		$objTemplate->link        = $objCustomer->link;
		$objTemplate->description = $objCustomer->description;

		$objTemplate->url         = $this->generateCustomerUrl($objCustomer, $blnAddCategory);
		$objTemplate->more        = $this->generateCustomerLink($GLOBALS['TL_LANG']['MSC']['moredetail'], $objCustomer, $blnAddCategory, true);

		$objTemplate->category    = $objCustomer->getRelated('pid');

		$objTemplate->count = $intCount; // see #5708

		$objTemplate->addImage = false;

		// Add an image
		if ($objCustomer->singleSRC != '')
		{
			$objModel = \FilesModel::findByUuid($objCustomer->singleSRC);

			if ($objModel === null)
			{
				if (!\Validator::isUuid($objCustomer->singleSRC))
				{
					$objTemplate->text = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
				}
			}
			elseif (is_file(TL_ROOT . '/' . $objModel->path))
			{
				// Do not override the field now that we have a model registry (see #6303)
				$arrCustomer = $objCustomer->row();

				// Override the default image size
				if ($this->imgSize != '')
				{
					$size = deserialize($this->imgSize);

					if ($size[0] > 0 || $size[1] > 0)
					{
						$arrCustomer['size'] = $this->imgSize;
					}
				}

				$arrCustomer['singleSRC'] = $objModel->path;
				$this->addImageToTemplate($objTemplate, $arrCustomer);
			}
		}

		return $objTemplate->parse();
	}

	/**
	 * Parse an item and return it as string
	 * @param object
	 * @param boolean
	 * @param string
	 * @param integer
	 * @return string
	 */
	protected function parseProject($objProject, $blnAddCategory=false, $strClass='', $intCount=0)
	{
		global $objPage;

		$objTemplate = new \FrontendTemplate($this->project_template);
		$objTemplate->setData($objProject->row());

		$objTemplate->class = (($this->projectClass != '') ? ' ' . $this->projectClass : '') . $strClass;
		$objTemplate->projectClass = $this->projectClass;

		$objTemplate->title       = $objProject->title;
		$objTemplate->date        = $objProject->date;
		$objTemplate->link        = $objProject->link;
		$objTemplate->duration    = $objProject->duration;
		$objTemplate->status      = $objProject->status;
		$objTemplate->description = $objProject->description;

		$objTemplate->url         = $this->generateProjectUrl($objProject, $blnAddCategory);
		$objTemplate->more        = $this->generateProjectLink($GLOBALS['TL_LANG']['MSC']['moredetail'], $objProject, $blnAddCategory, true);

		$objTemplate->category    = $objProject->getRelated('pid');

		$objTemplate->count = $intCount; // see #5708
		$objTemplate->text = '';

		$objTemplate->addImage = false;

		// Add an image
		if ($objProject->singleSRC != '')
		{
			$objModel = \FilesModel::findByUuid($objProject->singleSRC);

			if ($objModel === null)
			{
				if (!\Validator::isUuid($objProject->singleSRC))
				{
					$objTemplate->text = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
				}
			}
			elseif (is_file(TL_ROOT . '/' . $objModel->path))
			{
				// Do not override the field now that we have a model registry (see #6303)
				$arrProject = $objProject->row();

				// Override the default image size
				if ($this->project_imgSize != '')
				{
					$size = deserialize($this->project_imgSize);

					if ($size[0] > 0 || $size[1] > 0)
					{
						$arrProject['size'] = $this->project_imgSize;
					}
				}

				$arrProject['singleSRC'] = $objModel->path;
				$this->addImageToTemplate($objTemplate, $arrProject);
			}
		}

		return $objTemplate->parse();
	}


	/**
	 * Generate a URL and return it as string
	 * @param object
	 * @param boolean
	 * @return string
	 */
	protected function generateCustomerUrl($objItem, $blnAddCategory=false)
	{
		$strCacheKey = 'id_' . $objItem->id;

		// Load the URL from cache
		if (isset(self::$arrUrlCache[$strCacheKey]))
		{
			return self::$arrUrlCache[$strCacheKey];
		}

		// Initialize the cache
		self::$arrUrlCache[$strCacheKey] = null;

		// Link to the default page
		if (self::$arrUrlCache[$strCacheKey] === null)
		{
			$objPage = \PageModel::findByPk($objItem->getRelated('pid')->jumpToCustomer);

			if ($objPage === null)
			{
				self::$arrUrlCache[$strCacheKey] = ampersand(\Environment::get('request'), true);
			}
			else
			{
				self::$arrUrlCache[$strCacheKey] = ampersand($this->generateFrontendUrl($objPage->row(), ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ?  '/' : '/items/') . ((!\Config::get('disableAlias') && $objItem->alias != '') ? $objItem->alias : $objItem->id)));
			}

		}

		return self::$arrUrlCache[$strCacheKey];
	}

	/**
	 * Generate a link and return it as string
	 * @param string
	 * @param object
	 * @param boolean
	 * @param boolean
	 * @return string
	 */
	protected function generateCustomerLink($strLink, $objCustomer, $blnAddCategory=false, $blnIsReadMore=false)
	{

		return sprintf('<a href="%s" title="%s">%s%s</a>',
						$this->generateCustomerUrl($objCustomer, $blnAddCategory),
						specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $objCustomer->title), true),
						$strLink,
						($blnIsReadMore ? ' <span class="invisible">'.$objCustomer->title.'</span>' : ''));

	}

	/**
	 * Generate a URL and return it as string
	 * @param object
	 * @param boolean
	 * @return string
	 */
	protected function generateProjectUrl($objItem, $blnAddCategory=false)
	{
		$strCacheKey = 'id_' . $objItem->id;

		// Load the URL from cache
		if (isset(self::$arrUrlCache[$strCacheKey]))
		{
			return self::$arrUrlCache[$strCacheKey];
		}

		// Initialize the cache
		self::$arrUrlCache[$strCacheKey] = null;

		// Link to the default page
		if (self::$arrUrlCache[$strCacheKey] === null)
		{
			$objPage = \PageModel::findByPk($objItem->getRelated('pid')->jumpToProject);

			if ($objPage === null)
			{
				self::$arrUrlCache[$strCacheKey] = ampersand(\Environment::get('request'), true);
			}
			else
			{
				self::$arrUrlCache[$strCacheKey] = ampersand($this->generateFrontendUrl($objPage->row(), ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ?  '/' : '/items/') . ((!\Config::get('disableAlias') && $objItem->alias != '') ? $objItem->alias : $objItem->id)));
			}

		}

		return self::$arrUrlCache[$strCacheKey];
	}

	/**
	 * Generate a link and return it as string
	 * @param string
	 * @param object
	 * @param boolean
	 * @param boolean
	 * @return string
	 */
	protected function generateProjectLink($strLink, $objProject, $blnAddCategory=false, $blnIsReadMore=false)
	{

		return sprintf('<a href="%s" title="%s">%s%s</a>',
						$this->generateProjectUrl($objProject, $blnAddCategory),
						specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $objProject->title), true),
						$strLink,
						($blnIsReadMore ? ' <span class="invisible">'.$objProject->title.'</span>' : ''));

	}

}
