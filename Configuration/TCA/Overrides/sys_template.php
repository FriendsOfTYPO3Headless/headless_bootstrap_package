<?php

defined('TYPO3_MODE') || die();

call_user_func(function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'headless_bootstrap_package',
        'Configuration/TypoScript/ContentElement',
        'Headless Boostrap Package: Content Elements'
    );
});
