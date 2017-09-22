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
 * Namespace
 */
namespace Respinar\Portfolio\Frontend\Module;


/**
 * Class ModulePortfolioProjectList
 *
 * @copyright  2014
 * @author     Hamid Abbaszadeh
 * @package    Devtools
 */
class ModulePortfolioProjectList extends \ModulePortfolio
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_portfolio_project_list';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['portfolio_project_list'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->portfolio_project_categories = $this->sortOutProtectedProject(deserialize($this->portfolio_project_categories));

		// No portfolio categories available
		if (!is_array($this->portfolio_project_categories) || empty($this->portfolio_project_categories))
		{
			return '';
		}

		// Show the project detail if an item has been selected
		if ($this->project_detailModule > 0 && (isset($_GET['items']) || ($GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))))
		{
			return $this->getFrontendModule($this->project_detailModule, $this->strColumn);
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

		// Handle featured projects
		if ($this->projects_featured == 'feature_projects')
		{
			$blnFeatured = true;
		}
		elseif ($this->projects_featured == 'unfeature_projects')
		{
			$blnFeatured = false;
		}
		else
		{
			$blnFeatured = null;
		}

		$this->Template->projects = array();
		$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['emptyProjectList'];

		$intTotal = \PortfolioProjectModel::countPublishedByPids($this->portfolio_project_categories,$blnFeatured);

		// Return if no Projects were found
		if ($intTotal < 1)
		{
			return;
		}

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
			$objProjects = \PortfolioProjectModel::findPublishedByPids($this->portfolio_project_categories, $blnFeatured, $limit, $offset);
		}
		else
		{
			$objProjects = \PortfolioProjectModel::findPublishedByPids($this->portfolio_project_categories, $blnFeatured, 0, $offset);
		}

		// No items found
		if ($objProjects !== null) {
			$this->Template->projects = $this->parseProjects($objProjects);
		}

		$this->Template->portfolio_project_categories = $this->portfolio_project_categories;

	}
}
