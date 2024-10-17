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

class SocialLinksElementTest extends BaseContentTypeTest
{
    public function testSocialLinksContentElement(): void
    {
        $response = $this->executeFrontendSubRequest(
            new InternalRequest('https://website.local/')
        );

        self::assertEquals(200, $response->getStatusCode());

        $fullTree = json_decode((string)$response->getBody(), true);

        $contentElement = $fullTree['content']['colPos0'][9];

        // content element specific tests
        $this->checkChannels($contentElement);

        // general tests
        $this->checkDefaultContentFields($contentElement, 10, 1, 'social_links', 0, 'SysCategory1Title,SysCategory2Title');
        $this->checkAppearanceFields($contentElement, 'layout-1', 'Frame', 'SpaceBefore', 'SpaceAfter', 'embedded', 'primary', '1', '1', 'ruler-before,ruler-after');
        $this->checkHeaderFields($contentElement, 'Header', 'Subheader', 1, 'center');
        $this->checkTypoLinkField($contentElement['content']['headerLink']);
        $this->checkBackgroundImageField($contentElement);
        $this->checkBackgroundImageOptions($contentElement, '1', '1', 'blur');
    }

    /**
     * @param array<string, mixed> $contentElement
     */
    private function checkChannels(array $contentElement): void
    {
        $channels = $contentElement['content']['items']['channels'];

        self::assertCount(11, $channels);
        self::assertEquals('https://www.facebook.com/myaccount', $channels['facebook']['url']);
        self::assertEquals('https://www.twitter.com/myaccount', $channels['twitter']['url']);
        self::assertEquals('https://www.instagram.com/myaccount', $channels['instagram']['url']);
        self::assertEquals('https://www.github.com/myaccount', $channels['github']['url']);
        self::assertEquals('https://www.linkedin.com/myaccount', $channels['linkedin']['url']);
        self::assertEquals('https://www.xing.com/myaccount', $channels['xing']['url']);
        self::assertEquals('https://www.youtube.com/myaccount', $channels['youtube']['url']);
        self::assertEquals('https://www.vk.com/myaccount', $channels['vk']['url']);
        self::assertEquals('https://www.vimeo.com/myaccount', $channels['vimeo']['url']);
        self::assertEquals('https://www.rss.com/feed.xml', $channels['rss']['url']);
    }
}
