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

class AccordionElementTest extends BaseContentTypeTest
{
    public function testAccordionContentElement(): void
    {
        $response = $this->executeFrontendSubRequest(
            new InternalRequest('https://website.local/')
        );

        self::assertEquals(200, $response->getStatusCode());

        $fullTree = \json_decode((string)$response->getBody(), true);

        $contentElement = $fullTree['content']['colPos0'][1];

        // content element specific tests
        self::assertEquals(1, $contentElement['flexform']['default_element'], 'flexform default_element mismatch');
        self::assertEquals('1234567890', $contentElement['content']['date'], 'date mismatch');
        $this->checkItems($contentElement);

        // general tests
        $this->checkDefaultContentFields($contentElement, 2, 1, 'accordion', 0, 'SysCategory2Title');
        $this->checkAppearanceFields($contentElement, 'layout-1', 'Frame', 'SpaceBefore', 'SpaceAfter', 'embedded', 'primary', '1', '1');
        $this->checkHeaderFields($contentElement, 'Header', 'Subheader', 1, 1);
        $this->checkTypolinkField($contentElement['content']['headerLink']);
        $this->checkBackgroundImageField($contentElement);
        $this->checkBackgroundImageOptions($contentElement, '1', '1', 'blur');
    }

    private function checkItems(array $contentElement): void
    {
        self::assertTrue(isset($contentElement['content']['items']), 'items not set');
        self::assertIsArray($contentElement['content']['items']);
        self::assertEquals(2, count($contentElement['content']['items']));

        foreach ($contentElement['content']['items'] as $item) {
            self::assertEquals('Header', $item['header'], 'accordion item: header mismatch');
            self::assertEquals('<p><a href="/page1?parameter=999&amp;cHash=bfd4c1935d34c545ca918205373b0a42" title="LinkTitle" target="_blank" class="LinkClass">Link</a></p>', $item['bodytext']);
            self::assertEquals('left', $item['mediaorient'], 'accordion item: mediaorient mismatch');
            self::assertEquals(2, $item['imagecols'], 'accordion item: imagecols mismatch');
            self::assertEquals(0, $item['imageZoom'], 'accordion item: imageZoom mismatch');

            $this->checkFileReferencesField($item, 'media');
        }
    }
}
