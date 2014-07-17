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
namespace portfolio;


/**
 * Class ModulePortfolioProjectList
 *
 * @copyright  2014
 * @author     Hamid Abbaszadeh
 * @package    Devtools
 */
class ModulePortfolioProjectList extends \Module
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

		$intTotal = \PortfolioProjectModel::countPublishedByPid($this->portfolio_category,$blnFeatured);

		// Return if no Projects were found
		if ($intTotal < 1)
		{
			$this->Template = new \FrontendTemplate('mod_portfolio_project_empty');
			$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['emptyProject'];
			return;
		}

		$objPortfolio = $this->Database->prepare("SELECT * FROM tl_portfolio WHERE id=?")->execute($this->portfolio_category);

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
			$objProjects = \PorfolioProjectModel::findPublishedByPid($this->portfolio_category, $blnFeatured, $limit, $offset);
		}
		else
		{
			$objProjects = \PorfolioProjectModel::findPublishedByPid($this->portfolio_category, $blnFeatured, 0, $offset);
		}

		$strLink = '';

		// Generate a jumpTo link
		if ($objPortfolio->jumpToProject > 0)
		{
			$objJump = \PageModel::findByPk($objPortfolio->jumpToProject);

			if ($objJump !== null)
			{
				$strLink = $this->generateFrontendUrl($objJump->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ? '/%s' : '/items/%s'));
			}
		}

		$size = deserialize($this->imgSize);

		$arrProjectList = array();

		// Generate Projects
		while ($objProjects->next())
		{
			$strImage = '';
			$objImage = \FilesModel::findByPk($objProjects->singleSRC);

			// Add image
			if ($objImage !== null)
			{
				$strImage = \Image::getHtml(\Image::get($objImage->path, $size[0], $size[1], $size[2]));
			}

			$arrProjectList[] = array
			(
				'title' => $objProjects->title,
				'image' => $strImage,
				'link' => strlen($strLink) ? sprintf($strLink, $objProjects->alias) : ''
			);
		}

		$this->Template->projects = $arrProjectList;

	}
}
