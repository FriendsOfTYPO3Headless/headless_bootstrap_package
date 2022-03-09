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

class AudioElementTest extends BaseContentTypeTest
{
    public function testAudioContentElement(): void
    {
        $response = $this->executeFrontendSubRequest(
            new InternalRequest('https://website.local/')
        );

        self::assertEquals(200, $response->getStatusCode());

        $fullTree = json_decode((string)$response->getBody(), true);

        $contentElement = $fullTree['content']['colPos0'][5];
        // content element specific tests
        self::assertEquals('1234567890', $contentElement['content']['date'], 'date mismatch');
        $this->checkAssets($contentElement);

        // general tests
        $this->checkDefaultContentFields($contentElement, 6, 1, 'audio', 0, 'SysCategory1Title,SysCategory2Title');
        $this->checkAppearanceFields($contentElement, 'layout-1', 'Frame', 'SpaceBefore', 'SpaceAfter', 'embedded', 'primary', '1', '1');
        $this->checkHeaderFields($contentElement, 'Header', 'Subheader', 1, 'center');
        $this->checkTypolinkField($contentElement['content']['headerLink']);
        $this->checkBackgroundImageField($contentElement);
        $this->checkBackgroundImageOptions($contentElement, '1', '1', 'blur');
    }

    private function checkAssets(array $contentElement): void
    {
        self::assertCount(1, $contentElement['content']['items']);

        foreach ($contentElement['content']['items'] as $item) {
            self::assertEquals('https://website.local/typo3conf/ext/headless_bootstrap_package/Tests/Functional/Fixtures/audio.mp3', $item['publicUrl']);
            self::assertEquals('MetadataTitle', $item['properties']['title']);
            self::assertEquals('MetadataTitle', $item['properties']['title']);
            self::assertEquals('audio/mpeg', $item['properties']['mimeType']);
            self::assertEquals('audio', $item['properties']['type']);
            self::assertEquals('audio.mp3', $item['properties']['filename']);
            self::assertEquals('/typo3conf/ext/headless_bootstrap_package/Tests/Functional/Fixtures/audio.mp3', $item['properties']['originalUrl']);
            self::assertEquals('62 KB', $item['properties']['size']);
            self::assertEquals('mp3', $item['properties']['extension']);
            self::assertEquals('0', $item['properties']['dimensions']['width']);
            self::assertEquals('0', $item['properties']['dimensions']['height']);
        }
    }
}
