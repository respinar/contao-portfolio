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
 * Class ModulePortfolioCustomerList
 *
 * @copyright  2014
 * @author     Hamid Abbaszadeh
 * @package    Devtools
 */
class ModulePortfolioCustomerList extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_portfolio_customer_list';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['customers_list'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Show the customers detail if an item has been selected
		if ($this->customers_detailModule > 0 && (isset($_GET['items']) || ($GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))))
		{
			return $this->getFrontendModule($this->customers_detailModule, $this->strColumn);
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{

		$limit = null;

		// Maximum number of items
		if ($this->numberOfItems > 0)
		{
			$limit = $this->numberOfItems;
		}

		// Handle featured customers
		if ($this->customers_featured == 'feature_customers')
		{
			$blnFeatured = true;
		}
		elseif ($this->customers_featured == 'unfeature_customers')
		{
			$blnFeatured = false;
		}
		else
		{
			$blnFeatured = null;
		}

		$intTotal = \CustomersModel::countPublishedByPid($this->customers_category,$blnFeatured);

		// Return if no Customers were found
		if ($intTotal < 1)
		{
			$this->Template = new \FrontendTemplate('mod_customers_empty');
			$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['emptyCustomers'];
			return;
		}

		$objCustomersCategory = $this->Database->prepare("SELECT * FROM tl_portfolio WHERE id=?")->execute($this->customers_category);

		$total=$intTotal - $offset;

		// Split the results
		if ($this->perPage > 0 && (!isset($limit) || $this->numberOfItems > $this->perPage))
		{
			// Adjust the overall limit
			if (isset($limit))
			{
				$total = min($limit, $total);
			}

			// Get the current page
			$id = 'page_n' . $this->id;
			$page = \Input::get($id) ?: 1;

			// Do not index or cache the page if the page number is outside the range
			if ($page < 1 || $page > max(ceil($total/$this->perPage), 1))
			{
				global $objPage;
				$objPage->noSearch = 1;
				$objPage->cache = 0;

				// Send a 404 header
				header('HTTP/1.1 404 Not Found');
				return;
			}

			// Set limit and offset
			$limit = $this->perPage;
			$offset += (max($page, 1) - 1) * $this->perPage;
			$skip = intval($this->skipFirst);

			// Overall limit
			if ($offset + $limit > $total + $skip)
			{
				$limit = $total + $skip - $offset;
			}

			// Add the pagination menu
			$objPagination = new \Pagination($total, $this->perPage, $GLOBALS['TL_CONFIG']['maxPaginationLinks'], $id);
			$this->Template->pagination = $objPagination->generate("\n  ");
		}

		// Get the items
		if (isset($limit))
		{
			$objCustomers = \CustomersModel::findPublishedByPid($this->customers_category, $blnFeatured, $limit, $offset);
		}
		else
		{
			$objCustomers = \CustomersModel::findPublishedByPid($this->customers_category, $blnFeatured, 0, $offset);
		}

		$strLink = '';

		// Generate a jumpTo link
		if ($objCustomersCategory->jumpTo > 0)
		{
			$objJump = \PageModel::findByPk($objCustomersCategory->jumpTo);

			if ($objJump !== null)
			{
				$strLink = $this->generateFrontendUrl($objJump->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ? '/%s' : '/items/%s'));
			}
		}

		$size = deserialize($this->imgSize);

		$arrCustomers = array();

		// Generate Customers
		while ($objCustomers->next())
		{
			$strImage = '';
			$objImage = \FilesModel::findByPk($objCustomers->singleSRC);

			// Add image
			if ($objImage !== null)
			{
				$strImage = \Image::getHtml(\Image::get($objImage->path, $size[0], $size[1], $size[2]));
			}

			$arrCustomers[] = array
			(
				'title' => $objCustomers->title,
				'image' => $strImage,
				'link' => strlen($strLink) ? sprintf($strLink, $objCustomers->alias) : ''
			);
		}

		$this->Template->customers = $arrCustomers;

	}
}
