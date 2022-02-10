<?php

/*
 * This file is part of the "headless_bootstrap_package" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FriendsOfTYPO3Headless\HeadlessBootstrapPackage\Utility;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageLayoutResolver;

class BackendLayoutSerializer
{
    /**
     * @param string $content Empty string (no content to process)
     * @param array $conf TypoScript configuration
     *
     * @return string HTML output, showing the current server time
     */
    public function serialize(string $content, array $conf): string
    {
        $rootline = $GLOBALS['TSFE']->rootLine;
        $page = $GLOBALS['TSFE']->page ?? [];

        $pagelayout = GeneralUtility::makeInstance(PageLayoutResolver::class)->getLayoutForPage($page, $rootline);
        $pageTsLayoutPrefix = 'pagets__';

        $layoutConfig = [];

        //TODO: Impement Database based Backend-Layouts?
        //TODO: Implement rowspan (nested rows in cols)
        if (0 === strpos($pagelayout, $pageTsLayoutPrefix)) {
            $layoutName = substr($pagelayout, \strlen($pageTsLayoutPrefix));
            $pageTsConfig = BackendUtility::getPagesTSconfig($GLOBALS['TSFE']->page['uid']);
            $backendLayoutTsConfig = $pageTsConfig['mod.']['web_layout.']['BackendLayouts.'][$layoutName . '.']['config.']['backend_layout.'];

            $gridsize = 12;
            $layoutColCount = (int)$backendLayoutTsConfig['colCount'];
            $layoutColCountFactor = $gridsize / $layoutColCount;

            if (\is_int($layoutColCountFactor)) {
                foreach ($backendLayoutTsConfig['rows.'] as $row) {
                    $rowConfig = [];

                    foreach ($row['columns.'] as $col) {
                        $rowConfig[] = [
                            'type'          => 'col',
                            'contentColPos' => $col['colPos'] ? 'colPos' . $col['colPos'] : '',
                            'colPos'        => $col['colPos'] ?? '',
                            'size'          => (!empty($col['colspan']) ? (int)$col['colspan'] * $layoutColCountFactor : ''),
                            'tag'           => $col['tag'] ?? 'div',
                        ];
                    }

                    $layoutConfig[] = [
                        'type'     => 'row',
                        'tag'      => $row['tag'] ?? 'div',
                        'children' => $rowConfig,
                    ];
                }
            } else {
                $layoutConfig = [
                    'error' => 'BackendLayout colCount is not Bootstrap compatible.',
                ];
            }
        } else {
            $layoutConfig = [
                'error' => 'BackendLayout config not found.',
            ];
        }

        return json_encode($layoutConfig, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    }
}
