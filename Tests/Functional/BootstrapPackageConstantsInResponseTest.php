<?php

/*
 * This file is part of the "headless_bootstrap_package" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FriendsOfTYPO3Headless\HeadlessBootstrapPackage\Tests\Functional;

use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;

class BootstrapPackageConstantsInResponseTest extends BaseTest
{
    public function testBootstrapPackageConstants(): void
    {
        $response = $this->executeFrontendSubRequest(
            new InternalRequest('https://website.local/')
        );

        self::assertEquals(200, $response->getStatusCode());

        $fullTree = \json_decode((string)$response->getBody(), true);

        self::assertIsArray($fullTree['bootstrapPackage']);

        $config = $fullTree['bootstrapPackage'];

        $this->checkLogoData($config);
        $this->checkFaviconData($config);
        $this->checkThemeData($config);
        $this->checkTrackingData($config);
        $this->checkMetaData($fullTree['meta']);
    }

    /**
     * @param array<string, mixed> $config
     */
    private function checkLogoData(array $config): void
    {
        self::assertCount(6, $config['logo']);
        self::assertArrayhasKey('file', $config['logo']);
        self::assertArrayhasKey('fileInverted', $config['logo']);
        self::assertArrayhasKey('height', $config['logo']);
        self::assertArrayhasKey('width', $config['logo']);
        self::assertArrayhasKey('alt', $config['logo']);
        self::assertArrayhasKey('linktitle', $config['logo']);
    }

    /**
     * @param array<string, mixed> $config
     */
    private function checkFaviconData(array $config): void
    {
        self::assertCount(1, $config['favicon']);
        self::assertArrayHasKey('file', $config['favicon']);
    }

    /**
     * @param array<string, mixed> $config
     */
    private function checkThemeData(array $config): void
    {
        self::assertArrayHasKey('googleFont', $config['theme']);
        self::assertArrayHasKey('enable', $config['theme']['googleFont']);
        self::assertArrayHasKey('font', $config['theme']['googleFont']);
        self::assertArrayHasKey('weight', $config['theme']['googleFont']);

        self::assertArrayHasKey('navigation', $config['theme']);
        self::assertArrayHasKey('style', $config['theme']['navigation']);
        self::assertArrayHasKey('type', $config['theme']['navigation']);
        self::assertArrayHasKey('icon', $config['theme']['navigation']);
        self::assertArrayHasKey('enable', $config['theme']['navigation']['icon']);
        self::assertArrayHasKey('width', $config['theme']['navigation']['icon']);
        self::assertArrayHasKey('height', $config['theme']['navigation']['icon']);

        self::assertIsArray($config['theme']['navigation']['dropdown']);
        self::assertArrayHasKey('icon', $config['theme']['navigation']['dropdown']);
        self::assertArrayHasKey('enable', $config['theme']['navigation']['dropdown']['icon']);
        self::assertArrayHasKey('width', $config['theme']['navigation']['dropdown']['icon']);
        self::assertArrayHasKey('height', $config['theme']['navigation']['dropdown']['icon']);

        self::assertArrayHasKey('subnavigation', $config['theme']);
        self::assertArrayHasKey('enable', $config['theme']['subnavigation']['icon']);
        self::assertArrayHasKey('width', $config['theme']['subnavigation']['icon']);
        self::assertArrayHasKey('height', $config['theme']['subnavigation']['icon']);

        self::assertArrayHasKey('breadcrumb', $config['theme']);
        self::assertArrayHasKey('enable', $config['theme']['breadcrumb']);
        self::assertArrayHasKey('enableLevel', $config['theme']['breadcrumb']);
        self::assertArrayHasKey('icon', $config['theme']['breadcrumb']);
        self::assertCount(3, $config['theme']['breadcrumb']['icon']);
        self::assertArrayHasKey('enable', $config['theme']['breadcrumb']['icon']);
        self::assertArrayHasKey('width', $config['theme']['breadcrumb']['icon']);
        self::assertArrayHasKey('height', $config['theme']['breadcrumb']['icon']);

        self::assertArrayHasKey('meta', $config['theme']);
        self::assertArrayHasKey('enable', $config['theme']['meta']);
        self::assertArrayHasKey('navigationValue', $config['theme']['meta']);
        self::assertArrayHasKey('navigationType', $config['theme']['meta']);
        self::assertArrayHasKey('includeNotInMenu', $config['theme']['meta']);

        self::assertArrayHasKey('contact', $config['theme']);
        self::assertArrayHasKey('enable', $config['theme']['contact']);
        self::assertArrayHasKey('label', $config['theme']['contact']);

        self::assertArrayHasKey('data', $config['theme']['contact']);
        self::assertArrayHasKey('title', $config['theme']['contact']['data']);
        self::assertArrayHasKey('addressLine1', $config['theme']['contact']['data']);
        self::assertArrayHasKey('addressLine2', $config['theme']['contact']['data']);
        self::assertArrayHasKey('addressLine3', $config['theme']['contact']['data']);
        self::assertArrayHasKey('country', $config['theme']['contact']['data']);
        self::assertArrayHasKey('phone', $config['theme']['contact']['data']);
        self::assertArrayHasKey('fax', $config['theme']['contact']['data']);
        self::assertArrayHasKey('email', $config['theme']['contact']['data']);
        self::assertArrayHasKey('www', $config['theme']['contact']['data']);

        self::assertArrayHasKey('button', $config['theme']['contact']);
        self::assertArrayHasKey('colorClass', $config['theme']['contact']['button']);
        self::assertArrayHasKey('label', $config['theme']['contact']['button']);
        self::assertArrayHasKey('pageUid', $config['theme']['contact']['button']);

        self::assertArrayHasKey('language', $config['theme']);
        self::assertArrayHasKey('enable', $config['theme']['language']);
        self::assertArrayHasKey('languageValue', $config['theme']['language']);

        self::assertArrayHasKey('socialmedia', $config['theme']);
        self::assertArrayHasKey('enable', $config['theme']['socialmedia']);
        self::assertArrayHasKey('channels', $config['theme']['socialmedia']);
        self::assertCount(12, $config['theme']['socialmedia']['channels']);

        foreach ($config['theme']['socialmedia']['channels'] as $channel) {
            self::assertArrayHasKey('label', $channel);
            self::assertArrayHasKey('url', $channel);
        }

        self::assertArrayHasKey('copyright', $config['theme']);
        self::assertCount(2, $config['theme']['copyright']);
        self::assertArrayHasKey('enable', $config['theme']['copyright']);
        self::assertArrayHasKey('text', $config['theme']['copyright']);
    }

    /**
     * @param array<string, mixed> $config
     */
    public function checkTrackingData(array $config): void
    {
        self::assertCount(1, $config['tracking']);
        self::assertArrayHasKey('google', $config['tracking']);
        self::assertCount(1, $config['tracking']['google']);
        self::assertArrayHasKey('tagManagerContainerId', $config['tracking']['google']);
    }

    /**
     * @param array<string, mixed> $metaData
     */
    public function checkMetaData(array $metaData): void
    {
        self::assertEquals('width=device-width, initial-scale=1, minimum-scale=1', $metaData['viewport']);
        self::assertEquals('index,follow', $metaData['robots']);
        self::assertEquals('no', $metaData['appleMobileWebAppCapable']);
        self::assertEquals('IE=edge', $metaData['compatible']);
        self::assertEquals('testSiteVerificationData', $metaData['googleSiteVerification']);
    }
}
