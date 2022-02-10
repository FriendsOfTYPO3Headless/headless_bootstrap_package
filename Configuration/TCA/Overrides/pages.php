<?php

/*
 * This file is part of the "headless_bootstrap_package" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

// Register PageTS

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    'headless_bootstrap_package',
    'Configuration/TsConfig/Page/TCEFORM.tsconfig',
    'Headless Bootstrap Package: TCEFORM'
);