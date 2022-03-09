<?php

/*
 * This file is part of the "headless_bootstrap_package" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FriendsOfTYPO3Headless\HeadlessBootstrapPackage\Tests\Functional;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

abstract class BaseTest extends FunctionalTestCase
{
    protected $coreExtensionsToLoad = [
        'install'
    ];

    protected $testExtensionsToLoad = [
        'typo3conf/ext/headless',
        'typo3conf/ext/bootstrap_package',
        'typo3conf/ext/headless_bootstrap_package',
    ];

    /**
     * set up objects
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->importDataSet(__DIR__ . '/Fixtures/pages.xml');

        $this->setUpFrontendRootPage(
            1,
            [
                'constants' => [
                    'EXT:headless/Configuration/TypoScript/constants.typoscript',
                    'EXT:headless_bootstrap_package/Configuration/TypoScript/BootstrapPackage/constants.typoscript',
                    'EXT:headless_bootstrap_package/Configuration/TypoScript/constants.typoscript',
                    'EXT:headless_bootstrap_package/Tests/Functional/Fixtures/bootstrap_package/Configuration/TypoScript/constants.typoscript',
                ],
                'setup' => [
                    'EXT:headless/Configuration/TypoScript/setup.typoscript',
                    'EXT:headless_bootstrap_package/Configuration/TypoScript/setup.typoscript',
                ]
            ]
        );

        $siteConfigDir = Environment::getConfigPath() . '/sites/headlessBootstrapPackage';

        mkdir($siteConfigDir, 0777, true);

        file_put_contents($siteConfigDir . '/config.yaml', "rootPageId: 1\nbase: /\nbaseVariants: { }\nlanguages: { }\nroutes: { }\n");
    }

    protected function checkDefaultContentFields($contentElement, $id, $pid, $type, $colPos = 0, $categories = '')
    {
        self::assertEquals($id, $contentElement['id'], 'id mismatch');
        self::assertEquals($pid, $contentElement['pid'], 'pid mismatch');
        self::assertEquals($type, $contentElement['type'], 'type mismatch');
        self::assertEquals($colPos, $contentElement['colPos'], 'colPos mismatch');
        self::assertEquals($categories, $contentElement['categories'], 'categories mismatch');
    }

    protected function checkAppearanceFields(
        $contentElement,
        $layout = 'default',
        $frameClass = 'default',
        $spaceBefore = '',
        $spaceAfter = '',
        $frameLayout = '',
        $backgroundColor = '',
        $sectionIndex = '',
        $linkToTop = ''
    ): void {
        $contentElementAppearance = $contentElement['appearance'];

        self::assertEquals($layout, $contentElementAppearance['layout'], 'layout mismatch');
        self::assertEquals($frameClass, $contentElementAppearance['frameClass'], 'frameClass mismatch');
        self::assertEquals($spaceBefore, $contentElementAppearance['spaceBefore'], 'spaceBefore mismatch');
        self::assertEquals($spaceAfter, $contentElementAppearance['spaceAfter'], 'spaceAfter mismatch');
        self::assertEquals($frameLayout, $contentElementAppearance['frameLayout'], 'frameLayout mismatch');
        self::assertEquals($backgroundColor, $contentElementAppearance['backgroundColor'], 'backgroundColor mismatch');
        self::assertEquals($sectionIndex, $contentElementAppearance['sectionIndex'], 'sectionIndex mismatch');
        self::assertEquals($linkToTop, $contentElementAppearance['linkToTop'], 'linkToTop mismatch');
    }

    protected function checkHeaderFields($contentElement, $header = '', $subheader = '', $headerLayout = 0, $headerPosition = '')
    {
        $contentElementContent = $contentElement['content'];

        self::assertEquals($header, $contentElementContent['header'], 'header mismatch');
        self::assertEquals($subheader, $contentElementContent['subheader'], 'subheader mismatch');
        self::assertEquals($headerLayout, $contentElementContent['headerLayout'], 'headerLayout mismatch');
        self::assertEquals($headerPosition, $contentElementContent['headerPosition'], 'headerPosition mismatch');
        self::assertTrue(isset($contentElementContent['headerLink']), 'headerLink not set');
    }

    protected function checkHeaderFieldsLink($contentElement, $link, $urlPrefix, $target)
    {
        $contentElementHeaderFieldsLink = $contentElement['content']['headerLink'];

        self::assertIsArray($contentElementHeaderFieldsLink, 'headerLink not an array');
        self::assertEquals($link, $contentElementHeaderFieldsLink['linkText'], 'link mismatch');
        self::assertStringStartsWith($urlPrefix, $contentElementHeaderFieldsLink['href'], 'url mismatch');
        self::assertEquals($target, $contentElementHeaderFieldsLink['target'], 'target mismatch');
    }
}
