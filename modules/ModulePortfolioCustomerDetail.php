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
 * Class ModulePortfolioCustomerDetail
 *
 * @copyright  2014
 * @author     Hamid Abbaszadeh
 * @package    Devtools
 */
class ModulePortfolioCustomerDetail extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_portfolio_customer_detail';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['customers_detail'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Set the item from the auto_item parameter
		if (!isset($_GET['items']) && $GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))
		{
			\Input::setGet('items', \Input::get('auto_item'));
		}

		// Return if there are no items
		if (!\Input::get('items'))
		{
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

		$objCustomer = $this->Database->prepare("SELECT * FROM tl_portfolio_customer WHERE alias=?")->execute(\Input::get('items'));

		$objPortfolio = $this->Database->prepare("SELECT * FROM tl_portfolio WHERE id=?")->execute($objCustomer->pid);

		// Return if no Customer were found
		if (!$objCustomer->numRows)
		{
			return;
		}

		$objProjects = $this->Database->prepare("SELECT * FROM tl_portfolio_project WHERE pid=?")->execute($objCustomer->id);

		$strLink = '';

		$size = deserialize($this->imgSize);

		$strImage = '';
		$objImage = \FilesModel::findByPk($objCustomer->singleSRC);

		// Add image
		if ($objImage !== null)
		{
			$strImage = \Image::getHtml(\Image::get($objImage->path, $size[0], $size[1], $size[2]));
		}

		$this->Template->title       = $objCustomer->title;
		$this->Template->link        = $objCustomer->link;
		$this->Template->image       = $strImage;
		$this->Template->description = $objCustomer->description;

		$objPage->pageTitle = $objCustomer->title;

		// Generate a jumpTo link
		if ($objPortfolio->jumpToProject > 0)
		{
			$objJump = \PageModel::findByPk($objPortfolio->jumpToProject);

			if ($objJump !== null)
			{
				$strLink = $this->generateFrontendUrl($objJump->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ? '/%s' : '/items/%s'));
			}
		}

		$arrProjectsList = array();

		while ($objProjects->next())
		{

			$strImage = '';
			$objImage = \FilesModel::findByPk($objProjects->singleSRC);

			// Add photo image
			if ($objImage !== null)  
			{ 
				$strImage = \Image::getHtml(\Image::get($objImage->path, '300', '300', 'center_center'));
			}

			if ($this->kitchenware_price) {
				$price   = number_format($objProjects->price);
			}

			$arrProjectsList[] = array
			(
				'title'       => $objProjects->title,
				'year'        => $objProjects->year,
				'duration'    => $objProjects->duration,
				'status'      => $objProjects->status,
				'URL'         => $objProjects->URL,
				'description' => $objProjects->description,
				'image'       => $strImage,
				'link'        => strlen($strLink) ? sprintf($strLink, $objProjects->alias) : ''
			);
		}

		$this->Template->projects = $arrProjectsList;

	}
}
