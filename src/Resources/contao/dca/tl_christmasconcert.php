<?php
/**
 * WBGym
 *
 * Copyright (C) 2008-2013 Webteam Weinberg-Gymnasium Kleinmachnow
 *
 * @package 	WGBym
 * @author 		Markus Mielimonka <mmi.github@t-online.de>
 * @license 	http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Table tl_christmasconcert
 */
$GLOBALS['TL_DCA']['tl_christmasconcert'] = [
	// Config
	'config' => [
		'dataContainer'		=> 'Table',
		'enableVersioning'	=> true,
		'sql' 				=> [
			'keys' => [
				'id' => 'primary',
                'leader' => 'index'
			]
		],
		'onsubmit_callback' => [['tl_christmasconcert', 'onSubmit']]
	],

	// List
	'list' => [
		'sorting' => [
			'mode'					=> 1,
			'fields'				=> ['name', 'duration'],
			'flag'					=> 1,
			'panelLayout'			=> 'filter,sort;search,limit'
		],
		'label' => [
			'fields'				=> ['name', 'leader', 'duration'],
			'showColumns'			=> true,
			'label_callback'		=> ['tl_christmasconcert', 'replaceIds']
		],
		'global_operations' => [
			'all' => [
				'label'				=> &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'				=> 'act=select',
				'class'				=> 'header_edit_all',
				'attributes'		=> 'onclick="Backend.getScrollOffset();" accesskey="e"'
			]
		],
		'operations' => [
			'edit' => [
				'label'				=> &$GLOBALS['TL_LANG']['tl_christmasconcert']['edit'],
				'href'				=> 'act=edit',
				'icon'				=> 'edit.gif'
			],
			'delete' => [
				'label'				=> &$GLOBALS['TL_LANG']['tl_christmasconcert']['delete'],
				'href'				=> 'act=delete',
				'icon'				=> 'delete.gif',
				'attributes'			=> 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			],
			'show' => [
				'label'				=> &$GLOBALS['TL_LANG']['tl_christmasconcert']['show'],
				'href'				=> 'act=show',
				'icon'				=> 'show.gif'
			]
		]
	],

	// Palettes
	'palettes' => [
		'__selector__'			=> [''],
		'default'				=> 'name,description;leader,members;duration;notes,confirmed'
	],

	// Subpalettes
	'subpalettes' => [
		''						=> ''
	],

	// Fields
	'fields' => [
		'id' => [
			'sql'				=> "int(10) unsigned NOT NULL auto_increment"
		],
		'tstamp' => [
			'sql'				=> "int(10) unsigned NOT NULL default '0'"
		],
		'name' => [
			'label'				=> &$GLOBALS['TL_LANG']['tl_christmasconcert']['name'],
			'exclude'			=> false,
			'inputType'			=> 'text',
			'search'			=> true,
			'sorting'			=> true,
			'eval'				=> array('mandatory' => true, 'tl_class' => 'long'),
			'sql'				=> "varchar(255) NOT NULL default ''"
		],
		'description' => [
			'label'				=> &$GLOBALS['TL_LANG']['tl_christmasconcert']['description'],
			'exclude'			=> false,
			'inputType'			=> 'textarea',
			'search'			=> true,
			'sorting'			=> true,
			'eval'				=> ['mandatory' => true],
			'sql'				=> "TEXT NOT NULL"
		],
		'leader' => [
			'label'				=> &$GLOBALS['TL_LANG']['tl_christmasconcert']['leader'],
			'exclude'			=> false,
			'serach'			=> true,
			'inputType'			=> 'select',
			'options_callback'	=> ['WBGym\WBGym', 'studentList'],
			'foreignKey'		=> 'tl_member.username',
			'eval'				=> ['chosen' => true, 'includeBlankOption' => false, 'tl_class' => 'w50', 'mandatory' => true],
			'sql'				=> "INT(10) unsigned NOT NULL default '0'"
		],
		'members' => [
			'label'				=> &$GLOBALS['TL_LANG']['tl_christmasconcert']['members'],
			'exclude'			=> false,
			'search'			=> true,
			'inputType'			=> 'text',
			'default'			=> '',
			'load_callback'		=> [['tl_christmasconcert', 'loadSerialized']],
			'save_callback'		=> [['tl_christmasconcert', 'saveSerialized']],
			'eval'				=> ['tl_class' => 'w50', 'nospace' => true],
			'sql'				=> "BLOB NOT NULL default ''"
		],
		'numMembers' => [
			'exclude'			=> false,
			'search'			=> false,
			'inputType'			=> 'natural',
			'eval'				=> ['hideInput' => true],
			'sql'				=> "int(10) unsigned NOT NULL default '1'"
		],
		'notes' => [
			'label'				=> &$GLOBALS['TL_LANG']['tl_christmasconcert']['notes'],
			'exclude'			=> false,
			'inputType'			=> 'textarea',
			'eval'				=> ['tl_class' => 'long'],
			'sql'				=> "TEXT NOT NULL default ''"
		],
		'duration' => [
			'label'				=> &$GLOBALS['TL_LANG']['tl_christmasconcert']['duration'],
			'exclude'			=> false,
			'search'			=> true,
            'sorting'           => true,
			'inputType'			=> 'text',
			'maxval'			=> 30,
			'minval'			=> 5,
			'eval' 				=> ['mandatory' => true, 'rgxp'=>'natural'],
			'sql'				=> "int(10) unsigned NOT NULL default '5'"
		],
		'confirmed' => [
			'label'				=> &$GLOBALS['TL_LANG']['tl_christmasconcert']['confirmed'],
			'exclude'			=> true,
			'filter'			=> true,
			'search'			=> false,
			'inputType'			=> 'checkbox',
            'default'           => false,
            'eval'              => ['tl_class' => 'm12'],
			'sql'				=> "int(1) NOT NULL default '0'"
		]

	]
];

class tl_christmasconcert extends Backend
{
	public function replaceIds($row, $label, DataContainer $dc, $args) {

		$args[1] = WBGym\WBGym::student($row['leader']);
		$args[2] = $row['duration'].' min.';

		return $args;
	}
	public function loadSerialized($value, DataContainer $dc)
	{
		$arrData = unserialize($value);
		$blnFirst = true;
		$strFieldValue = '';
		if (!is_array($arrData) && $arrData) throw new \RuntimeException($GLOBALS['TL_LANG']['tl_christmasconcert']['serializeError'] ?? "tried to load serialized non-array as array");
		foreach ($arrData as $userId) {
			if (!$blnFirst) $strFieldValue .= ',';
			else $blnFirst = false;
			$strFieldValue .= $this->Database->prepare('SELECT username FROM tl_member WHERE id=?')->execute($userId)->fetchAssoc()['username'];
		}
		return $strFieldValue;
	}

	public function saveSerialized(string $value, DataContainer $dc)
	{
		$arrData = $value? \explode(',', $value) : [];
		if (count($arrData) < 2) throw new \Exception($GLOBALS['TL_LANG']['tl_christmasconcert']['toFewMembers'], 1);

		foreach ($arrData as $key => $strName) {
			$curr = $this->Database->prepare('SELECT * FROM tl_member WHERE username=?')->execute($strName);
			if ($curr->count() != 1) throw new \Exception($GLOBALS['TL_LANG']['tl_christmasconcert']['invalidUsername'] ?? "invalid username");
			$curr = $curr->fetchAssoc();
			if ((int)$curr['student'] != 1)
				throw new \Exception($GLOBALS['TL_LANG']['tl_christmasconcert']['onlyStudents'] ?? "only students can participate!");
			$arrData[$key] = $curr['id'];
		}
		return serialize($arrData);
	}
	public function onSubmit(DataContainer $dc)
	{
		$record = $dc->activeRecord;
		$numMembers = count(explode(',', $record->members));
		$this->Database->prepare('UPDATE tl_christmasconcert SET numMembers=? WHERE id=?')->execute($numMembers, $dc->id);
	}
}

?>
