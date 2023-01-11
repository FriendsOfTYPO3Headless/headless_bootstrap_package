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

class GalleryElementTest extends BaseContentTypeTest
{
    public function testGalleryElement(): void
    {
        $response = $this->executeFrontendSubRequest(
            new InternalRequest('https://website.local/')
        );

        self::assertEquals(200, $response->getStatusCode());

        $fullTree = json_decode((string)$response->getBody(), true);

        $contentElement = $fullTree['content']['colPos0'][24];

        // content element specific tests
        self::assertEquals('2009-02-13', $contentElement['content']['date'], 'date mismatch');
        self::assertEquals('25', $contentElement['content']['imageorient'], 'imageorient mismatch');
        self::assertEquals('1.3333333333333', $contentElement['content']['aspectRatio'], 'aspectRatio mismatch');
        self::assertEquals('1', $contentElement['content']['imageZoom'], 'imageZoom mismatch');
        self::assertEquals('10', $contentElement['content']['itemsPerPage'], 'itemsPerPage mismatch');
        self::assertEquals('extension', $contentElement['content']['filelinkSorting'], 'filelinkSorting mismatch');
        $this->checkItems($contentElement);

        // general tests
        $this->checkDefaultContentFields($contentElement, 25, 1, 'gallery', 0, 'SysCategory1Title,SysCategory2Title');
        $this->checkAppearanceFields($contentElement, 'layout-1', 'Frame', 'SpaceBefore', 'SpaceAfter', 'embedded', 'primary', '1', '1', 'ruler-before,ruler-after');
        $this->checkHeaderFields($contentElement, 'Header', 'Subheader', 1, 'center');
        $this->checkTypoLinkField($contentElement['content']['headerLink']);
        $this->checkBackgroundImageField($contentElement);
        $this->checkBackgroundImageOptions($contentElement, '1', '1', 'grayscale');
    }

    /**
     * @param array<string, mixed> $contentElement
     */
    private function checkItems(array $contentElement): void
    {
        self::assertTrue(isset($contentElement['content']['items']), 'items not set');
        self::assertIsArray($contentElement['content']['items']);
        self::assertEquals(2, count($contentElement['content']['items']));
    }
}
