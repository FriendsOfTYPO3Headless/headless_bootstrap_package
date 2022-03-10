<?php

/*
 * This file is part of the "headless_bootstrap_package" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FriendsOfTYPO3Headless\HeadlessBootstrapPackage\Tests\Functional\ContentTypes;

use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;

class MenuCardListElementTest extends BaseContentTypeTest
{
    public function testMenuCardListContentElement(): void
    {
        $response = $this->executeFrontendSubRequest(
            new InternalRequest('https://website.local/')
        );

        self::assertEquals(200, $response->getStatusCode());

        $fullTree = json_decode((string)$response->getBody(), true);

        $contentElement = $fullTree['content']['colPos0'][13];

        // content element specific tests
        self::assertEquals(1, $contentElement['accessibility']['bypass']);
        self::assertEquals('Skip navigation', $contentElement['accessibility']['bypassText']);
        $this->checkFlexform($contentElement);
        $this->checkItems($contentElement);

        // general tests
        $this->checkDefaultContentFields($contentElement, 14, 1, 'menu_card_list', 0, 'SysCategory1Title,SysCategory2Title');
        $this->checkAppearanceFields($contentElement, 'layout-1', 'Frame', 'SpaceBefore', 'SpaceAfter', 'embedded', 'primary', '1', '1');
        $this->checkHeaderFields($contentElement, 'Header', 'Subheader', 1, 'center');
        $this->checkBackgroundImageField($contentElement);
        $this->checkBackgroundImageOptions($contentElement, '1', '1', 'grayscale');
    }

    private function checkFlexform($contentElement): void
    {
        self::assertCount(2, $contentElement['flexform']);
        self::assertEquals('left', $contentElement['flexform']['align']);
        self::assertEquals('2', $contentElement['flexform']['columns']);
    }

    private function checkItems($contentElement): void
    {
        self::assertIsArray($contentElement['content']['items']);
        self::assertCount(3, $contentElement['content']['items']);

        foreach ($contentElement['content']['items'] as $page) {
            self::assertCount(9, $page);
            self::assertArrayHasKey('title', $page);
            self::assertArrayHasKey('link', $page);
            self::assertArrayHasKey('target', $page);
            self::assertArrayHasKey('active', $page);
            self::assertArrayHasKey('current', $page);
            self::assertArrayHasKey('spacer', $page);
            self::assertArrayHasKey('hasSubpages', $page);
            self::assertArrayHasKey('thumbnail', $page);
            self::assertIsArray($page['thumbnail']);
            self::assertArrayHasKey('nav_icon', $page);
        }
    }
}
