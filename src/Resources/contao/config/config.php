<?php

/**
 * WBGym
 *
 * Copyright (C) 2018 Webteam Weinberg-Gymnasium Kleinmachnow
 *
 * @package 	WGBym
 * @author 		Markus Mielimonka <mmi.github@t-online.de>
 * @license 	http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/*
 * Back end modules
 */

$GLOBALS['BE_MOD']['wbgym']['christmasconcert'] = ['tables'	=> ['tl_christmasconcert']];

/*
 * Front end modules
 */
$GLOBALS['FE_MOD']['wbgym']['wb_christmasconcert']	= 'WBGym\ModuleChristmasconcert';

?>
