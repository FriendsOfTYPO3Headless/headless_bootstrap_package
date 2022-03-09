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

class CardGroupElementTest extends BaseContentTypeTest
{
    public function testCardGroupContentElement(): void
    {
        $response = $this->executeFrontendSubRequest(
            new InternalRequest('https://website.local/')
        );

        self::assertEquals(200, $response->getStatusCode());

        $fullTree = json_decode((string)$response->getBody(), true);

        $contentElement = $fullTree['content']['colPos0'][10];

        $this->checkFlexform($contentElement);
        $this->checkItems($contentElement);

        $this->checkDefaultContentFields($contentElement, 11, 1, 'card_group', 0, 'SysCategory1Title,SysCategory2Title');
        $this->checkAppearanceFields($contentElement, 'layout-1', 'Frame', 'SpaceBefore', 'SpaceAfter', 'embedded', 'primary', '1', '1');
        $this->checkHeaderFields($contentElement, 'Header', 'Subheader', 1, 'center');
        $this->checkTypoLinkField($contentElement['content']['headerLink']);
        $this->checkBackgroundImageField($contentElement);
        $this->checkBackgroundImageOptions($contentElement, '1', '1', 'blur');
    }

    private function checkFlexform(array $contentElement): void
    {
        self::assertIsArray($contentElement['flexform']);
        self::assertEquals(2, count($contentElement['flexform']));
        self::assertEquals('left', $contentElement['flexform']['align'], 'flexform sorting mismatch');
        self::assertEquals(3, $contentElement['flexform']['columns'], 'flexform sorting mismatch');
    }

    private function checkItems(array $contentElement): void
    {
        self::assertTrue(isset($contentElement['content']['items']), 'items not set');
        self::assertIsArray($contentElement['content']['items']);
        self::assertEquals(3, count($contentElement['content']['items']));

        $itemTestConfig = $this->getItemsTestConfig();

        foreach ($contentElement['content']['items'] as $key => $item) {
            self::assertEquals('Header', $item['header'], 'icon_set mismatch');
            self::assertEquals('Subheader', $item['subheader'], 'icon_set mismatch');
            self::assertArrayNotHasKey('headerLayout', $item);
            self::assertArrayNotHasKey('headerPosition', $item);

            self::assertStringContainsString('<a href="t3://page?uid=2 _blank LinkClass LinkTitle parameter=999', $item['bodytext']);

            $assertConfig = $itemTestConfig[$key];
            self::assertEquals($assertConfig['link_icon_set'], $item['link_icon_set'], 'icon_set mismatch');
            self::assertEquals($assertConfig['link_icon_identifier'], $item['link_icon_identifier'], 'icon_identifier mismatch');

            if ($assertConfig['link_icon']) {
                self::assertEquals('ext_icon.gif', $item['linkIcon']['name'], 'name mismatch');
                self::assertEquals('/typo3conf/ext/headless_bootstrap_package/ext_icon.gif', $item['linkIcon']['previewImage'], 'previewImage mismatch');
                self::assertEquals(16, $item['linkIcon']['height'], 'height mismatch');
                self::assertEquals(16, $item['linkIcon']['width'], 'width mismatch');
            }

            if ($assertConfig['image']) {
                $this->checkFileReferencesField($item, 'image', $assertConfig['image']);
            }
        }
    }

    private function getItemsTestConfig(): array
    {
        return [
            [
                'image' => 0,
                'link_icon' => 0,
                'link_icon_set' => 'EXT:bootstrap_package/Resources/Public/Images/Icons/Ionicons/',
                'link_icon_identifier' => 'EXT:bootstrap_package/Resources/Public/Images/Icons/Ionicons/breaker.svg',
            ],
            [
                'image' => 1,
                'link_icon' => 0,
                'link_icon_set' => '',
                'link_icon_identifier' => '',
            ],
            [
                'image' => 0,
                'link_icon' => 1,
                'link_icon_set' => '',
                'link_icon_identifier' => '',
            ],
        ];
    }
}
