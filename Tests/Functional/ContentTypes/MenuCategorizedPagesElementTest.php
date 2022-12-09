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

class MenuCategorizedPagesElementTest extends BaseContentTypeTest
{
    public function testMenuCategorizedPagesElement(): void
    {
        $response = $this->executeFrontendSubRequest(
            new InternalRequest('https://website.local/')
        );

        self::assertEquals(200, $response->getStatusCode());

        $fullTree = json_decode((string)$response->getBody(), true);

        $contentElement = $fullTree['content']['colPos0'][25];

        // content element specific tests
        self::assertEquals(1, $contentElement['accessibility']['bypass']);
        self::assertEquals('Skip navigation', $contentElement['accessibility']['bypassText']);

        // general tests
        $this->checkDefaultContentFields($contentElement, 26, 1, 'menu_categorized_pages', 0, 'SysCategory1Title,SysCategory2Title');
        $this->checkAppearanceFields($contentElement, 'layout-1', 'Frame', 'SpaceBefore', 'SpaceAfter', 'embedded', 'primary', '1', '1', 'ruler-before,ruler-after');
        $this->checkHeaderFields($contentElement, 'Header', 'Subheader', 1, 'center');
        $this->checkBackgroundImageField($contentElement);
        $this->checkBackgroundImageOptions($contentElement, '1', '1', 'grayscale');

        $this->checkMenu($contentElement);
    }

    /**
     * @param array<string, mixed> $contentElement
     */
    private function checkMenu(array $contentElement): void
    {
        self::assertIsArray($contentElement['content']['menu']);
        self::assertCount(3, $contentElement['content']['menu']);

        foreach ($contentElement['content']['menu'] as $page) {
            self::assertCount(8, $page);
            self::assertArrayHasKey('title', $page);
            self::assertArrayHasKey('link', $page);
            self::assertArrayHasKey('target', $page);
            self::assertArrayHasKey('active', $page);
            self::assertArrayHasKey('current', $page);
            self::assertArrayHasKey('spacer', $page);
            self::assertArrayHasKey('hasSubpages', $page);
            self::assertArrayHasKey('media', $page);
        }
    }
}
