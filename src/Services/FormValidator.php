<?php
/**
 * WBGym
 *
 * Copyright (C) 2015 Webteam Weinberg-Gymnasium Kleinmachnow
 *
 * @package 	WGBym
 * @author 		Markus Mielimonka <mmi.github@t-online.de>
 * @license 	http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/*
Namespace
 */
namespace Wbgym\ChristmasconcertBundle\Services;

use Input;
use System;
use WBGym\WBGym;

/**
 * validation service for the christmasconcert module
 */
final class FormValidator extends System {

    public function __construct()
    {
        $this->Import('FrontendUser', 'User');
    }

    public function processForm():array
    {
        $arrErrors = [];

        # require nullterminators for type(cast)-checks

        if (!$this->isDuartionValid(Input::post('duration') ?? 0)) $arrErrors['duration'] = 'Ungültige Vorführungslänge!';
        if ($arrErrors['memeber'] = $this->areMembersValid(Input::post('member') ?? []))
            $arrErrors['membersGeneral'] = 'Ungültige Mitgliderauswahl!';
        if (!$this->isDescriptionValid(Input::post('description')) ?? '') $arrErrors['description'] = 'Beschreibung benötigt!';

        $arrErrors['name'] = $this->getNameError(Input::post('name') ?? '');

        return $arrErrors;
    }

    private function isDuartionValid(int $duration):bool
    {
        if ($duration >= 5 && $duration <= 30) return true;
        return false;
    }
    private function isMemberValid(int $memberId):bool
    {
        # will return null, if the user is not found
        if (!is_null(WBGym::student($memberId))) return true;
        return false;
    }

    private function areMembersValid(array $members):array
    {
        $arrMembersErrors = [];
        foreach ($members as $key => $member) {
            if ($member == '') { unset($members[$key]); continue; }
            if (!$this->isMemberValid($member ? $member : 0)) $arrMembersErrors[$key] = 'Ungültiges Mitglied!';
        }
        return $arrMembersErrors;
    }

    private function getNameError(string $name):?string
    {
        $maxLength = 256;
        if (strlen($name) >= $maxLength) return 'Name zu lang!';
        elseif (strlen($name) == 0) return 'Name zu kurz!';
        return Null;
    }
    private function isDescriptionValid(string $description):bool
    {
        if (strlen($description) > 0) return true;
        return false;
    }

    public function getNumMember():int
    {
        return count(array_filter((array)Input::post('member'), function ($elem) { return $elem != '';}));
    }

    public function fetchArray():array {
        return [
                'member' => array_filter((array)Input::post('member'), function ($elem) { return $elem != '';}),
                'numMembers' => $this->getNumMember(),
                'name' => Input::post('name'),
                'description' => Input::post('description'),
                'duration' => Input::post('duration'),
                'leader' => $this->User->id,
        ];
    }

}
