<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

/**
 * Wbgym/ChristmasconcertBundle
 *
 * @author Webteam WBGym <webteam@wbgym.de>
 * @package Christmasconcert Bundle
 * @license LGPL-3.0+
 */

namespace Wbgym\ChristmasconcertBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Wbgym\ChristmasconcertBundle\WbgymChristmasconcertBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

/**
 * Plugin for the Contao Manager.
 */
class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(WbgymChristmasconcertBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class])
        ];
    }
}
