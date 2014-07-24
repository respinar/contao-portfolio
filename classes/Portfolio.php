<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package News
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class Portfolio
 *
 * Provide methods regarding portfolio archives.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Portfolio
 */
class Portfolio extends \Frontend
{

	/**
	 * Add portfolio items to the indexer
	 * @param array
	 * @param integer
	 * @param boolean
	 * @return array
	 */
	public function getSearchablePages($arrPages, $intRoot=0, $blnIsSitemap=false)
	{
		$arrRoot = array();

		if ($intRoot > 0)
		{
			$arrRoot = $this->Database->getChildRecords($intRoot, 'tl_page');
		}

		$time = time();
		$arrProcessed = array();

		// Get all portfolio archives
		$objArchive = \NewsArchiveModel::findByProtected('');

		// Walk through each archive
		if ($objArchive !== null)
		{
			while ($objArchive->next())
			{
				// Skip portfolio archives without target page
				if (!$objArchive->jumpTo)
				{
					continue;
				}

				// Skip portfolio archives outside the root nodes
				if (!empty($arrRoot) && !in_array($objArchive->jumpTo, $arrRoot))
				{
					continue;
				}

				// Get the URL of the jumpTo page
				if (!isset($arrProcessed[$objArchive->jumpTo]))
				{
					$objParent = \PageModel::findWithDetails($objArchive->jumpTo);

					// The target page does not exist
					if ($objParent === null)
					{
						continue;
					}

					// The target page has not been published (see #5520)
					if (!$objParent->published || ($objParent->start != '' && $objParent->start > $time) || ($objParent->stop != '' && $objParent->stop < $time))
					{
						continue;
					}

					// The target page is exempt from the sitemap (see #6418)
					if ($blnIsSitemap && $objParent->sitemap == 'map_never')
					{
						continue;
					}

					// Set the domain (see #6421)
					$domain = ($objParent->rootUseSSL ? 'https://' : 'http://') . ($objParent->domain ?: \Environment::get('host')) . TL_PATH . '/';

					// Generate the URL
					$arrProcessed[$objArchive->jumpTo] = $domain . $this->generateFrontendUrl($objParent->row(), (($GLOBALS['TL_CONFIG']['useAutoItem'] && !$GLOBALS['TL_CONFIG']['disableAlias']) ?  '/%s' : '/items/%s'), $objParent->language);
				}

				$strUrl = $arrProcessed[$objArchive->jumpTo];

				// Get the items
				$objArticle = \NewsModel::findPublishedDefaultByPid($objArchive->id);

				if ($objArticle !== null)
				{
					while ($objArticle->next())
					{
						$arrPages[] = $this->getLink($objArticle, $strUrl);
					}
				}
			}
		}

		return $arrPages;
	}


	/**
	 * Return the link of a portfolio
	 * @param object
	 * @param string
	 * @param string
	 * @return string
	 */
	protected function getLink($objItem, $strUrl, $strBase='')
	{
		switch ($objItem->source)
		{
			// Link to an external page
			case 'external':
				return $objItem->url;
				break;

			// Link to an internal page
			case 'internal':
				if (($objTarget = $objItem->getRelated('jumpTo')) !== null)
				{
					return $strBase . $this->generateFrontendUrl($objTarget->row());
				}
				break;

			// Link to an article
			case 'article':
				if (($objArticle = \ArticleModel::findByPk($objItem->articleId, array('eager'=>true))) !== null && ($objPid = $objArticle->getRelated('pid')) !== null)
				{
					return $strBase . ampersand($this->generateFrontendUrl($objPid->row(), '/articles/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objArticle->alias != '') ? $objArticle->alias : $objArticle->id)));
				}
				break;
		}

		// Link to the default page
		return $strBase . sprintf($strUrl, (($objItem->alias != '' && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objItem->alias : $objItem->id));
	}

}
