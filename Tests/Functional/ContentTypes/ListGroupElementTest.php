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

class ListGroupElementTest extends BaseContentTypeTest
{
    public function testListGroupContentElement(): void
    {
        $response = $this->executeFrontendSubRequest(
            new InternalRequest('https://website.local/')
        );

        self::assertEquals(200, $response->getStatusCode());

        $fullTree = json_decode((string)$response->getBody(), true);

        $contentElement = $fullTree['content']['colPos0'][16];

        // content element specific tests
        self::assertArrayNotHasKey('flexform', $contentElement);

        $this->checkBodytext($contentElement);

        // general tests
        $this->checkDefaultContentFields($contentElement, 17, 1, 'listgroup', 0, 'SysCategory1Title,SysCategory2Title');
        $this->checkAppearanceFields($contentElement, 'layout-1', 'Frame', 'SpaceBefore', 'SpaceAfter', 'embedded', 'primary', '1', '1');
        $this->checkHeaderFields($contentElement, 'Header', 'Subheader', 1, 'center');
        $this->checkTypoLinkField($contentElement['content']['headerLink']);
        $this->checkBackgroundImageField($contentElement);
        $this->checkBackgroundImageOptions($contentElement, '1', '1', 'grayscale');
    }

    private function checkBodytext(array $contentElement): void
    {
        self::assertIsArray($contentElement['content']['bodytext']);
        self::assertCount(4, $contentElement['content']['bodytext']);
        foreach ($contentElement['content']['bodytext'] as $key => $item) {
            self::assertEquals(sprintf('Item %s', $key), $item);
        }
    }
}
