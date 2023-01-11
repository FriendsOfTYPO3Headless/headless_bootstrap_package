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

class IconGroupElementTest extends BaseContentTypeTest
{
    public function testIconGroupContentElement(): void
    {
        $response = $this->executeFrontendSubRequest(
            new InternalRequest('https://website.local/')
        );

        self::assertEquals(200, $response->getStatusCode());

        $fullTree = json_decode((string)$response->getBody(), true);

        $contentElement = $fullTree['content']['colPos0'][19];

        // content element specific tests
        self::assertEquals('2009-02-13', $contentElement['content']['date'], 'date mismatch');
        $this->checkFlexform($contentElement);
        $this->checkItems($contentElement);

        // general tests
        $this->checkDefaultContentFields($contentElement, 20, 1, 'icon_group', 0, 'SysCategory1Title,SysCategory2Title');
        $this->checkAppearanceFields($contentElement, 'layout-1', 'Frame', 'SpaceBefore', 'SpaceAfter', 'embedded', 'primary', '1', '1', 'ruler-before,ruler-after');
        $this->checkHeaderFields($contentElement, 'Header', 'Subheader', 1, 'center');
        $this->checkTypoLinkField($contentElement['content']['headerLink']);
        $this->checkBackgroundImageField($contentElement);
        $this->checkBackgroundImageOptions($contentElement, '1', '1', 'grayscale');
    }

    /**
     * @param array<string, mixed> $contentElement
     */
    private function checkFlexform(array $contentElement): void
    {
        self::assertIsArray($contentElement['flexform']);
        self::assertEquals(3, count($contentElement['flexform']));
        self::assertEquals('left', $contentElement['flexform']['align'], 'flexform sorting mismatch');
        self::assertEquals(3, $contentElement['flexform']['columns'], 'flexform sorting mismatch');
        self::assertEquals('left-center', $contentElement['flexform']['icon_position'], 'flexform icon_position mismatch');
    }

    /**
     * @param array<string, mixed> $contentElement
     */
    private function checkItems(array $contentElement): void
    {
        self::assertCount(1, $contentElement['content']['items']);

        foreach ($contentElement['content']['items'] as $item) {
            self::assertEquals('<p><a href="/page1?parameter=999&amp;cHash=bfd4c1935d34c545ca918205373b0a42" title="LinkTitle" target="_blank" class="LinkClass">Link</a></p>', $item['bodytext']);
            self::assertEquals('Header', $item['header']);
            self::assertEquals('Subheader', $item['subheader']);

            $this->checkTypoLinkField($item['link']);

            self::assertEquals('typo3conf/ext/bootstrap_package/Resources/Public/Images/Icons/Ionicons/beaker.svg', $item['icon']['previewImage'], 'name mismatch');
            self::assertEquals('beaker', $item['icon']['name'], 'name mismatch');
            self::assertEquals(16, $item['icon']['height'], 'height mismatch');
            self::assertEquals(16, $item['icon']['width'], 'width mismatch');
        }
    }
}
