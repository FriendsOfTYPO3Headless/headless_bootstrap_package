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

class TimelineElementTest extends BaseContentTypeTest
{
    public function testTimelineContentElement(): void
    {
        $response = $this->executeFrontendSubRequest(
            new InternalRequest('https://website.local/')
        );

        self::assertEquals(200, $response->getStatusCode());

        $fullTree = json_decode((string)$response->getBody(), true);

        $contentElement = $fullTree['content']['colPos0'][3];

        // content element specific tests
        self::assertEquals('asc', $contentElement['flexform']['sorting'], 'flexform sorting mismatch');
        $this->checkItems($contentElement);

        // general tests
        $this->checkDefaultContentFields($contentElement, 4, 1, 'timeline', 0, 'SysCategory2Title');
        $this->checkAppearanceFields($contentElement, 'layout-1', 'Frame', 'SpaceBefore', 'SpaceAfter', 'embedded', 'primary', '1', '1');
        $this->checkHeaderFields($contentElement, 'Header', 'Subheader', 1, 'center');
        $this->checkTypoLinkField($contentElement['content']['headerLink']);
        $this->checkBackgroundImageField($contentElement);
        $this->checkBackgroundImageOptions($contentElement, '1', '1', 'blur');
    }

    private function checkItems(array $contentElement): void
    {
        self::assertTrue(isset($contentElement['content']['items']), 'items not set');
        self::assertIsArray($contentElement['content']['items']);
        self::assertEquals(1, count($contentElement['content']['items']));

        foreach ($contentElement['content']['items'] as $item) {
            self::assertArrayHasKey('id', $item);
            self::assertEquals('2022-03-10 12:00:00', $item['date']);
            self::assertEquals('Header', $item['header'], 'accordion item: header mismatch');
            self::assertEquals('<p><a href="/page1?parameter=999&amp;cHash=bfd4c1935d34c545ca918205373b0a42" title="LinkTitle" target="_blank" class="LinkClass">Link</a></p>', $item['bodytext']);

            $this->checkFileReferencesField($item, 'image');
        }
    }
}
