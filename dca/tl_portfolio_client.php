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
 * Table tl_portfolio_client
 */
$GLOBALS['TL_DCA']['tl_portfolio_client'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_portfolio_client_category',
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id'    => 'primary',
				'pid'   => 'index',
				'alias' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'flag'                    => 1,
			'fields'                  => array('title'),
			'headerFields'            => array('title'),
			'panelLayout'             => 'search,limit',
			'child_record_callback'   => array('tl_portfolio_client', 'generateItemRow')
		),
		'label' => array
		(
			'fields'                  => array('title'),
			'format'                  => '%s',
			'label_callback'          => array('tl_portfolio_client', 'addClientsImage')
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_portfolio_client']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_portfolio_client']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_portfolio_client']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_portfolio_client']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_portfolio_client']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_portfolio_client', 'toggleIcon')
			),
			'feature' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_portfolio_client']['feature'],
				'icon'                => 'featured.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleFeature(this,%s)"',
				'button_callback'     => array('tl_portfolio_client', 'iconFeature')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_portfolio_client']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('addEnclosure','published'),
		'default'                     => '{title_legend},title,alias;{image_legend},singleSRC;{description_legend},description;{meta_legend},link,featured;{enclosure_legend:hide},addEnclosure;{publish_legend},published'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'addEnclosure'                => 'enclosure',
		'published'                   => 'start,stop'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'foreignKey'              => 'tl_portfolio_client_category.title',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'eager')
		),
		'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_portfolio_client']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_portfolio_client']['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'alias','unique'=>true,'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
		'singleSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_portfolio_client']['singleSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('mandatory'=>true,'fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true, 'extensions'=>$GLOBALS['TL_CONFIG']['validImageTypes']),
			'sql'                     => "binary(16) NULL"
		),
		'link' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_portfolio_client']['link'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'rgxp'=>'url', 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_portfolio_client']['description'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE'),
			'sql'                     => "text NULL"
		),
		'addEnclosure' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_portfolio_client']['addEnclosure'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'enclosure' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_portfolio_client']['enclosure'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('multiple'=>true, 'fieldType'=>'checkbox', 'filesOnly'=>true, 'isDownloads'=>true, 'extensions'=>Config::get('allowedDownload'), 'mandatory'=>true),
			'sql'                     => "blob NULL"
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_portfolio_client']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true, 'submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'featured' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_portfolio_client']['featured'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true, 'tl_class'=>'w50 m12'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_portfolio_client']['start'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		),
		'stop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_portfolio_client']['stop'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		)
	)
);

/**
 * Provide miscellaneous methods that are used by the data configuration array
 */
class tl_portfolio_client extends Backend
{

	/**
	 * Generate a song row and return it as HTML string
	 * @param array
	 * @return string
	 */
	public function generateItemRow($arrRow)
	{
		$objImage = \FilesModel::findByPk($arrRow['singleSRC']);

		if ($objImage !== null)
		{
			$strImage = \Image::getHtml(\Image::get($objImage->path, '30', '30', 'center_center'));
		}

		return '<div><div style="float:left; margin-right:10px;">'.$strImage.'</div>'. $arrRow['title'] .'</div>';
	}

	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen($this->Input->get('tid')))
		{
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		//if (!$this->User->isAdmin && !$this->User->hasAccess('tl_prices::published', 'alexf'))
		//{
		//	return '';
		//}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}



	public function toggleVisibility($intId, $blnVisible)
	{
		// Check permissions to edit
		$this->Input->setGet('id', $intId);
		$this->Input->setGet('act', 'toggle');
		//$this->checkPermission();

		// Check permissions to publish
		//if (!$this->User->isAdmin && !$this->User->hasAccess('tl_news::published', 'alexf'))
		//{
		//	$this->log('Not enough permissions to publish/unpublish news item ID "'.$intId.'"', 'tl_news toggleVisibility', TL_ERROR);
		//	$this->redirect('contao/main.php?act=error');
		//}

		$this->createInitialVersion('tl_portfolio_client', $intId);

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_portfolio_client']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_portfolio_client']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_portfolio_client SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_portfolio_client', $intId);

	}

	public function iconFeature($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen($this->Input->get('sid')))
		{
			$this->toggleFeature($this->Input->get('sid'), ($this->Input->get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		//if (!$this->User->isAdmin && !$this->User->hasAccess('tl_prices::published', 'alexf'))
		//{
		//	return '';
		//}

		$href .= '&amp;sid='.$row['id'].'&amp;state='.($row['featured'] ? '' : 1);

		if (!$row['featured'])
		{
			$icon = 'featured_.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}



	public function toggleFeature($intId, $blnFeature)
	{
		// Check permissions to edit
		$this->Input->setGet('id', $intId);
		$this->Input->setGet('act', 'featured');
		//$this->checkPermission();

		// Check permissions to publish
		//if (!$this->User->isAdmin && !$this->User->hasAccess('tl_news::published', 'alexf'))
		//{
		//	$this->log('Not enough permissions to publish/unpublish news item ID "'.$intId.'"', 'tl_news toggleVisibility', TL_ERROR);
		//	$this->redirect('contao/main.php?act=error');
		//}

		$this->createInitialVersion('tl_portfolio_client', $intId);

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_portfolio_client']['fields']['featured']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_portfolio_client']['fields']['featured']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnFeature = $this->$callback[0]->$callback[1]($blnFeature, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_portfolio_client SET tstamp=". time() .", featured='" . ($blnFeature ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_portfolio_client', $intId);

	}

}
