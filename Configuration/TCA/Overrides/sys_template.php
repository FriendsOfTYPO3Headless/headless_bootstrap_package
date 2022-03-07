<?php

/*
 * This file is part of the "headless_bootstrap_package" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

defined('TYPO3') or die();

call_user_func(function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'headless_bootstrap_package',
        'Configuration/TypoScript',
        'Headless Boostrap Package'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'headless_bootstrap_package',
        'Configuration/TypoScript/BoostrapPackage',
        'Headless Boostrap Package: Boostrap Package Constants'
    );
});
