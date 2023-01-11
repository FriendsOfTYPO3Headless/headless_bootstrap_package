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

class MediaElementTest extends BaseContentTypeTest
{
    public function testMediaContentElement(): void
    {
        $response = $this->executeFrontendSubRequest(
            new InternalRequest('https://website.local/')
        );

        self::assertEquals(200, $response->getStatusCode());

        $fullTree = json_decode((string)$response->getBody(), true);

        $contentElement = $fullTree['content']['colPos0'][23];

        // content element specific tests
        self::assertEquals('extension', $contentElement['content']['filelinkSorting'], 'filelinkSorting mismatch');
        self::assertEquals('1', $contentElement['content']['imagecols'], 'imagecols mismatch');
        self::assertEquals('2009-02-13', $contentElement['content']['date'], 'date mismatch');
        $this->checkItems($contentElement);

        // general tests
        $this->checkDefaultContentFields($contentElement, 24, 1, 'media', 0, 'SysCategory1Title,SysCategory2Title');
        $this->checkAppearanceFields($contentElement, 'layout-1', 'Frame', 'SpaceBefore', 'SpaceAfter', 'embedded', 'primary', '1', '1', 'ruler-before,ruler-after');
        $this->checkHeaderFields($contentElement, 'Header', 'Subheader', 1, 'center');
        $this->checkBackgroundImageField($contentElement);
        $this->checkBackgroundImageOptions($contentElement, '1', '1', 'grayscale');
    }

    /**
     * @param array<string, mixed> $contentElement
     */
    private function checkItems(array $contentElement): void
    {
        self::assertCount(2, $contentElement['content']['items']);

        foreach ($contentElement['content']['items'] as $item) {
            self::assertEquals('https://www.youtube-nocookie.com/embed/zpOVYePk6mM?autohide=1&amp;controls=1&amp;autoplay=1&amp;mute=1&amp;enablejsapi=1&amp;origin=http%3A%2F%2Fwebsite.local', $item['publicUrl']);
            self::assertEquals('video/youtube', $item['properties']['mimeType']);
            self::assertEquals('TYPO3_-_Channel_Intro.youtube', $item['properties']['filename']);
            self::assertEquals('https://www.youtube.com/watch?v=zpOVYePk6mM', $item['properties']['originalUrl']);
            self::assertEquals(1, $item['properties']['autoplay']);
            self::assertEquals('youtube', $item['properties']['extension']);
            self::assertEquals('11 B', $item['properties']['size']);
        }
    }
}
