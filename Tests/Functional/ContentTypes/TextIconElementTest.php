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

class TextIconElementTest extends BaseContentTypeTest
{
    public function testTexticonContentElement(): void
    {
        $response = $this->executeFrontendSubRequest(
            new InternalRequest('https://website.local/')
        );

        self::assertEquals(200, $response->getStatusCode());

        $fullTree = json_decode((string)$response->getBody(), true);

        $contentElement = $fullTree['content']['colPos0'][18];

        // content element specific tests
        self::assertEquals('<p><a href="/page1?parameter=999&amp;cHash=bfd4c1935d34c545ca918205373b0a42" target="_blank" title="LinkTitle" class="LinkClass">Link</a></p>', $contentElement['content']['bodytext'], 'bodytext mismatch');
        self::assertArrayNotHasKey('flexform', $contentElement);
        $this->checkIcon($contentElement);

        // general tests
        $this->checkDefaultContentFields($contentElement, 19, 1, 'texticon', 0, 'SysCategory1Title,SysCategory2Title');
        $this->checkAppearanceFields($contentElement, 'layout-1', 'Frame', 'SpaceBefore', 'SpaceAfter', 'embedded', 'primary', '1', '1', 'ruler-before,ruler-after');
        $this->checkHeaderFields($contentElement, 'Header', 'Subheader', 1, 'center');
        $this->checkTypolinkField($contentElement['content']['headerLink']);
        $this->checkBackgroundImageField($contentElement);
        $this->checkBackgroundImageOptions($contentElement, '1', '1', 'grayscale');
    }

    /**
     * @param array<string, mixed> $contentElement
     */
    private function checkIcon(array $contentElement): void
    {
        self::assertIsArray($contentElement['content']['icon']);
        self::assertEquals('left', $contentElement['content']['icon']['position']);
        self::assertEquals('square', $contentElement['content']['icon']['type']);
        self::assertEquals('medium', $contentElement['content']['icon']['size']);
        self::assertEquals('EXT:bootstrap_package/Resources/Public/Images/Icons/Ionicons/', $contentElement['content']['icon']['set']);
        self::assertEquals('#FFFFFF', $contentElement['content']['icon']['color']);
        self::assertEquals('#333333', $contentElement['content']['icon']['background']);
        self::assertCount(1, $contentElement['content']['icon']['file']);

        $this->checkFileReferencesField($contentElement['content']['icon'], 'file');
    }
}
