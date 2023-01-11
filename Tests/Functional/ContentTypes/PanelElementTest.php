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

class PanelElementTest extends BaseContentTypeTest
{
    public function testPanelContentElement(): void
    {
        $response = $this->executeFrontendSubRequest(
            new InternalRequest('https://website.local/')
        );

        self::assertEquals(200, $response->getStatusCode());

        $fullTree = json_decode((string)$response->getBody(), true);

        $contentElement = $fullTree['content']['colPos0'][0];

        // content element specific tests
        self::assertEquals('secondary', $contentElement['content']['panelClass'], 'panelClass mismatch');
        self::assertEquals('<p><a href="/page1?parameter=999&amp;cHash=bfd4c1935d34c545ca918205373b0a42" title="LinkTitle" target="_blank" class="LinkClass">Link</a></p>', $contentElement['content']['bodytext'], 'bodytext mismatch');
        $this->checkDisabledFields($contentElement);

        // general tests
        $this->checkDefaultContentFields($contentElement, 1, 1, 'panel', 0, 'SysCategory1Title,SysCategory2Title');
        $this->checkAppearanceFields($contentElement, 'layout-1', 'Frame', 'SpaceBefore', 'SpaceAfter', 'embedded', 'primary', '1', '1', 'ruler-before,ruler-after');
        $this->checkHeaderFields($contentElement, 'Header', '', 1, '');
        $this->checkBackgroundImageField($contentElement);
        $this->checkBackgroundImageOptions($contentElement, '1', '1', 'grayscale');
    }

    /**
     * @param array<string, mixed> $contentElement
     */
    private function checkDisabledFields($contentElement): void
    {
        self::assertArrayNotHasKey('subheader', $contentElement['content']);
        self::assertArrayNotHasKey('headerPosition', $contentElement['content']);
        self::assertArrayNotHasKey('headerLink', $contentElement['content']);
    }
}
