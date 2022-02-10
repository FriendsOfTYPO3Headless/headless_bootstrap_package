<?php

/*
 * This file is part of the "headless_bootstrap_package" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

defined('TYPO3_MODE') || die();

call_user_func(function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'headless_bootstrap_package',
        'Configuration/TypoScript/ContentElement',
        'Headless Boostrap Package: Content Elements'
    );
});
