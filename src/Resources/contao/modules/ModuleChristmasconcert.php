<?php

/**
 * WBGym
 *
 * Copyright (C) 2015 Webteam Weinberg-Gymnasium Kleinmachnow
 *
 * @package 	WGBym
 * @version 	0.3.0
 * @author 		Markus Mielimonka <mmi.github@t-online.de>
 * @license 	http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */
/**
 * Namespace
 */
namespace WBGym;

use Input;
use Module;
use BackendTemplate;
use Contao\System;

class ModuleChristmasconcert extends Module
{
    protected $strTemplate = 'wb_christmasconcert';

    protected const MODES = ['EDIT' => 'EDIT', 'REGISTER' => 'REGISTER'];
    protected $validator;

    public function generate()
    {
        if (TL_MODE == 'BE') {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### WBGym Weihnachtskonzert Anmeldung ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        return parent::generate();
    }

    protected function compile()
    {
        global $objPage;
        if (!FE_USER_LOGGED_IN) {
            # deny frontend access if no user is logged in
            $objHandler = new $GLOBALS['TL_PTY']['error_403']();
            $objHandler->generate($objPage->id);
        }

        # imports:
        $this->Import('FrontendUser', 'User');
        $this->Import('Database', 'Database');

        $this->validator = System::getContainer()->get('wbgym.christmasconcert.forms');

        $this->Template->strUser = WBGym::student($this->User->id);
        $this->Template->arrMember = WBGYm::studentList();

        if (Input::post('SUBMIT') !== null) {
            if ($this->Template->errors = $this->validator->processForm()) {
                $this->Template->mode = Input::post('MODE');
            } else {
                $this->Template->success = $this->saveForm();
            }
            return;
        } elseif ($performance = $this->getUserPerformance()) {
            $this->Template->mode = static::MODES['EDIT'];
            $this->Template->performance = $performance;
        } else {
            # register new one.
            $this->Template->mode = static::MODES['REGISTER'];
        }
    }

    /**
     * gets The performance, where the current user is registered as leader.
     * @return array|false false if no performance is found, the found performance otherwise
     */
    public function getUserPerformance():?array
    {
        $res = $this->Database->prepare('SELECT * FROM tl_christmasconcert WHERE leader=?')->execute($this->User->id);
        if ($res->count() != 1) {
            return Null;
        }
        return $res->fetchAssoc();
    }

    public function saveForm():bool
    {
        $newUsers = [];
        $removedUsers = [];
        $res = Null;

        switch (Input::post('MODE')) {
            case static::MODES['REGISTER']:
                # insert new into db, send mails
                break;
				$this->Database->prepare('INSERT INTO tl_christmasconcert %s')->set(

				);

                break;

            case static::MODES['EDIT']:
                # update existing dataset, compare users, send mails.
				break;
                $res = $this->Database->prepare('SELECT * FROM tl_christmasconcert WHERE leader=?')->execute($this->User->id)->fetchAssoc();
                $oldMembers = unserialize($res['member']);
                foreach (array_diff($oldMembers, $this->validator->getMember()) as $member) {
                    if (in_array($oldMembers, $member)) {
                        # user was removed
                    } else {
                        # user added
                    }
                }

				$this->Database->prepare('UPDATE tl_christmasconcert %s WHERE id=?')->set();
                break;
        }
        if (!$res) {
            return false;
        }
        return true;
    }
}
?>
