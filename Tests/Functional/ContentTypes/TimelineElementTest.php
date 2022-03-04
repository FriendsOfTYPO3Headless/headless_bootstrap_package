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
    public function testHeaderContentElement(): void
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
        self::assertEquals(3, count($contentElement['content']['items']));

        $itemTestConfig = $this->getItemsTestConfig();

        foreach ($contentElement['content']['items'] as $key => $item) {
            $assertConfig = $itemTestConfig[$key];

            self::assertEquals('Header', $item['header'], 'accordion item: header mismatch');
            self::assertStringContainsString('<a href="t3://page?uid=2 _blank LinkClass LinkTitle parameter=999', $item['bodytext']);

            self::assertEquals($assertConfig['icon_set'], $item['icon_set'], 'icon_set mismatch');
            self::assertEquals($assertConfig['icon_identifier'], $item['icon_identifier'], 'icon_identifier mismatch');

            if ($assertConfig['icon_file']) {
                self::assertEquals('ext_icon.gif', $item['icon_file']['name'], 'name mismatch');
                self::assertEquals('/typo3conf/ext/headless_bootstrap_package/ext_icon.gif', $item['icon_file']['previewImage'], 'previewImage mismatch');
                self::assertEquals(16, $item['icon_file']['height'], 'height mismatch');
                self::assertEquals(16, $item['icon_file']['width'], 'width mismatch');
            }

            if ($assertConfig['image']) {
                $this->checkFileReferencesField($item, 'image');
            }
        }
    }

    private function getItemsTestConfig(): array
    {
        return [
            [
                'image' => 0,
                'icon_file' => 0,
                'icon_set' => 'EXT:bootstrap_package/Resources/Public/Images/Icons/Ionicons/',
                'icon_identifier' => 'EXT:bootstrap_package/Resources/Public/Images/Icons/Ionicons/android-note.svg',
            ],
            [
                'image' => 1,
                'icon_file' => 0,
                'icon_set' => '',
                'icon_identifier' => '',
            ],
            [
                'image' => 0,
                'icon_file' => 1,
                'icon_set' => '',
                'icon_identifier' => '',
            ],
        ];
    }
}
