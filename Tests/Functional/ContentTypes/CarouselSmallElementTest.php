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

class CarouselSmallElementTest extends BaseContentTypeTest
{
    public function testCarouselSmallContentElement(): void
    {
        $response = $this->executeFrontendSubRequest(
            new InternalRequest('https://website.local/')
        );

        self::assertEquals(200, $response->getStatusCode());

        $fullTree = \json_decode((string)$response->getBody(), true);

        $contentElement = $fullTree['content']['colPos0'][21];

        // content element specific tests
        $this->checkFlexform($contentElement);
        $this->checkItems($contentElement);

        // general tests
        $this->checkDefaultContentFields($contentElement, 22, 1, 'carousel_small', 0, 'SysCategory1Title,SysCategory2Title');
        $this->checkAppearanceFields($contentElement, 'layout-1', 'Frame', 'SpaceBefore', 'SpaceAfter', 'embedded', 'primary', '1', '1');
        $this->checkHeaderFields($contentElement, 'Header', 'Subheader', 1, 'center');
        $this->checkTypolinkField($contentElement['content']['headerLink']);
        $this->checkBackgroundImageField($contentElement);
        $this->checkBackgroundImageOptions($contentElement, '1', '1', 'grayscale');
    }

    private function checkFlexform(array $contentElement): void
    {
        self::assertIsArray($contentElement['flexform']);
        self::assertCount(4, $contentElement['flexform']);
        self::assertEquals(1234, $contentElement['flexform']['interval']);
        self::assertEquals('slide', $contentElement['flexform']['transition']);
        self::assertEquals(1, $contentElement['flexform']['wrap']);
        self::assertEquals(1, $contentElement['flexform']['show_nav_title']);
    }

    private function checkItems(array $contentElement): void
    {
        self::assertTrue(isset($contentElement['content']['items']), 'items not set');
        self::assertIsArray($contentElement['content']['items']);
        self::assertEquals(1, count($contentElement['content']['items']));

        foreach ($contentElement['content']['items'] as $item) {
            self::assertEquals('Header', $item['header']);
            self::assertEquals('1', $item['headerLayout']);
            self::assertEquals('h1', $item['headerClass']);

            self::assertEquals('Subheader', $item['subheader']);
            self::assertEquals('1', $item['subheaderLayout']);
            self::assertEquals('h2', $item['subheaderClass']);

            self::assertEquals('center', $item['headerPosition']);
            self::assertEquals('ButtonText', $item['buttonText']);
            self::assertEquals('secondary', $item['layout']);
            self::assertEquals('NavTitle', $item['navTitle']);

            $this->checkTypoLinkField($item['link']);
            $this->checkFileReferencesField($item, 'backgroundImage');
            $this->checkFileReferencesField($item, 'image');
        }
    }
}
