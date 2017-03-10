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
 * Class ModulePortfolioClientDetail
 *
 * @copyright  2014
 * @author     Hamid Abbaszadeh
 * @package    Devtools
 */
class ModulePortfolioClientDetail extends \ModulePortfolio
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_portfolio_client_detail';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['portfolio_client_detail'][0]) . ' ###';
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

		$this->portfolio_client_categories = $this->sortOutProtectedClient(deserialize($this->portfolio_client_categories));

		// Do not index or cache the page if no client item has been specified
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

		$this->Template->client = '';
		$this->Template->referer = 'javascript:history.go(-1)';
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
		$this->Template->project_headline = $GLOBALS['TL_LANG']['MSC']['project_headline'];


		// Get the clients item
		$objClient = \PortfolioClientModel::findPublishedByParentAndIdOrAlias(\Input::get('items'), $this->portfolio_client_categories);

		if ($objClient === null)
		{
			// Do not index or cache the page
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			// Send a 404 header
			header('HTTP/1.1 404 Not Found');
			$this->Template->clients = '<p class="error">' . sprintf($GLOBALS['TL_LANG']['MSC']['invalidPage'], \Input::get('items')) . '</p>';
			return;
		}

		$arrClient = $this->parseClient($objClient);
		$this->Template->clients = $arrClient;

		if ($this->project_show == '1')
		{
			// Get the projects items
			$objProjects = \PortfolioProjectModel::findPublishedByClientId($objClient->id);

			$this->Template->projects = '';

			if ($objProjects !== null) {
				$arrProjects = $this->parseProjects($objProjects);
				$this->Template->projects = $arrProjects;
			}
		}

		
		// Overwrite the page title (see #2853 and #4955)
		if ($objClient->title != '')
		{
			$objPage->pageTitle = strip_tags(strip_insert_tags($objClient->title));
		}

		// Overwrite the page description
		if ($objClient->description != '')
		{
			$objPage->description = $this->prepareMetaDescription($objClient->description);
		}


	}
}
