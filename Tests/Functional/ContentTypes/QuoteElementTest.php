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

class QuoteElementTest extends BaseContentTypeTest
{
    public function testHeaderContentElement(): void
    {
        $response = $this->executeFrontendSubRequest(
            new InternalRequest('https://website.local/')
        );

        self::assertEquals(200, $response->getStatusCode());

        $fullTree = json_decode((string)$response->getBody(), true);

        $contentElement = $fullTree['content']['colPos0'][4];

        // content element specific tests
        self::assertEquals('A super wise and reliable source', $contentElement['content']['quoteSource'], 'quote_source mismatch');
        self::assertIsArray($contentElement['content']['quoteLink'], 'quote_link not an array');
        self::assertEquals('/page1?parameter=999&amp;cHash=bfd4c1935d34c545ca918205373b0a42', $contentElement['content']['quoteLink']['href'], 'quoteLink href mismatch');
        self::assertEquals('Lorem ipsum dolor sit amet', $contentElement['content']['bodytext'], 'bodytext mismatch');

        // general tests
        $this->checkDefaultContentFields($contentElement, 5, 1, 'quote', 0, 'SysCategory1Title,SysCategory2Title');
        $this->checkAppearanceFields($contentElement, 'layout-1', 'Frame', 'SpaceBefore', 'SpaceAfter', 'embedded', 'primary', '1', '1');
        $this->checkHeaderFields($contentElement, '', '', 0, '', false);
        $this->checkBackgroundImageField($contentElement);
        $this->checkBackgroundImageOptions($contentElement, '1', '1', 'grayscale');
    }
}
