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
class ModulePortfolioCustomerList extends \ModulePortfolio
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

		$this->portfolio_categories = $this->sortOutProtected(deserialize($this->portfolio_categories));

		// No portfolio categories available
		if (!is_array($this->portfolio_categories) || empty($this->portfolio_categories))
		{
			return '';
		}

		// Show the customers detail if an item has been selected
		if ($this->customer_detailModule > 0 && (isset($_GET['items']) || ($GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))))
		{
			return $this->getFrontendModule($this->customer_detailModule, $this->strColumn);
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{

		$offset = intval($this->skipFirst);
		$limit = null;

		// Maximum number of items
		if ($this->numberOfItems > 0)
		{
			$limit = $this->numberOfItems;
		}

		// Handle featured news
		if ($this->customer_featured == 'featured')
		{
			$blnFeatured = true;
		}
		elseif ($this->customer_featured == 'unfeatured')
		{
			$blnFeatured = false;
		}
		else
		{
			$blnFeatured = null;
		}

		$this->Template->projects = array();
		$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['emptyCustomerList'];

		// Get the total number of items
		$intTotal = \PortfolioCustomerModel::countPublishedByPids($this->portfolio_categories, $blnFeatured);

		if ($intTotal < 1)
		{
			return;
		}


		$total = $intTotal - $offset;

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
			$objCustomers = \PortfolioCustomerModel::findPublishedByPids($this->portfolio_categories, $blnFeatured, $limit, $offset);
		}
		else
		{
			$objCustomers = \PortfolioCustomerModel::findPublishedByPids($this->portfolio_categories, $blnFeatured, 0, $offset);
		}

		// No items found
		if ($objCustomers !== null) {
			$this->Template->customers = $this->parseCustomers($objCustomers);
		}

		$this->Template->portfolio_categories = $this->portfolio_categories;

	}
}
