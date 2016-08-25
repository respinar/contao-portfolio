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
 * Class ModulePortfolioProjectDetail
 *
 * @copyright  2014
 * @author     Hamid Abbaszadeh
 * @package    Devtools
 */
class ModulePortfolioProjectDetail extends \ModulePortfolio
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_portfolio_project_detail';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['portfolio_project_detail'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Set the item from the auto_item parameter
		if (!isset($_GET['items']) && \Config::get('useAutoItem') && isset($_GET['auto_item']))
		{
			\Input::setGet('items', \Input::get('auto_item'));
		}

		$this->portfolio_project_categories = $this->sortOutProtectedProject(deserialize($this->portfolio_project_categories));

		// Do not index or cache the page if no project item has been specified
		if (!\Input::get('items'))
		{
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache = 0;
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{

		global $objPage;

		$this->Template->project = '';
		$this->Template->referer = 'javascript:history.go(-1)';
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];

		// Get the Project item
		$objProject = \PortfolioProjectModel::findPublishedByParentAndIdOrAlias(\Input::get('items'), $this->portfolio_project_categories);

		if ($objProject === null)
		{
			// Do not index or cache the page
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			// Send a 404 header
			header('HTTP/1.1 404 Not Found');
			$this->Template->projects = '<p class="error">' . sprintf($GLOBALS['TL_LANG']['MSC']['invalidPage'], \Input::get('items')) . '</p>';
			return;
		}

		$arrProject = $this->parseProject($objProject);
		$this->Template->project = $arrProject;

		// Get the Client items
		$objClient = \PortfolioClientModel::findPublishedByParentAndIdOrAlias($objProject->clientID,$this->portfolio_project_categories);

		$this->Template->client = '';

		if ($objClient !== null) {
			$this->Template->client = $this->parseClient($objClient);
		}

		// Overwrite the page title (see #2853 and #4955)
		if ($objProject->title != '')
		{
			$objPage->pageTitle = strip_tags(strip_insert_tags($objProject->title));
		}

		// Overwrite the page description
		if ($objProject->description != '')
		{
			$objPage->description = $this->prepareMetaDescription($objProject->description);
		}

	}
}
