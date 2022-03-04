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

class CsvElementTest extends BaseContentTypeTest
{
    public function testHeaderContentElement(): void
    {
        $response = $this->executeFrontendSubRequest(
            new InternalRequest('https://website.local/')
        );

        self::assertEquals(200, $response->getStatusCode());

        $fullTree = json_decode((string)$response->getBody(), true);

        $contentElement = $fullTree['content']['colPos0'][6];

        // content element specific tests
        self::assertEquals('1234567890', $contentElement['content']['date'], 'date mismatch');
        self::assertEquals(59, $contentElement['content']['tableDelimiter'], 'tableDelimiter mismatch');
        self::assertEquals(0, $contentElement['content']['tableEnclosure'], 'tableEnclosure mismatch');
        self::assertEquals(0, $contentElement['content']['tableLayout']['cols'], 'cols mismatch');
        self::assertEquals('striped', $contentElement['content']['tableLayout']['tableClass'], 'tableClass mismatch');
        self::assertEquals(1, $contentElement['content']['tableLayout']['tableHeaderPosition'], 'tableHeaderPosition mismatch');
        self::assertEquals(0, $contentElement['content']['tableLayout']['tableTfoot'], 'tableTfoot mismatch');

        $this->checkMedia($contentElement);

        // general tests
        $this->checkDefaultContentFields($contentElement, 7, 1, 'csv', 0, 'SysCategory1Title,SysCategory2Title');
        $this->checkAppearanceFields($contentElement, 'layout-1', 'Frame', 'SpaceBefore', 'SpaceAfter', 'embedded', 'primary', '1', '1');
        $this->checkHeaderFields($contentElement, 'Header', 'Subheader', 1, 'center');
        $this->checkTypolinkField($contentElement['content']['headerLink']);
        $this->checkBackgroundImageField($contentElement);
        $this->checkBackgroundImageOptions($contentElement, '1', '1', 'blur');
    }

    private function checkMedia(array $contentElement): void
    {
        self::assertCount(1, $contentElement['content']['media']);

        $fileData = $contentElement['content']['media'][0]['file'];
        self::assertIsArray($fileData);

        self::assertEquals('https://website.local/typo3conf/ext/headless_bootstrap_package/Tests/Functional/Fixtures/csv.csv', $fileData['publicUrl']);
        self::assertEquals('MetadataTitle', $fileData['properties']['title']);
        self::assertEquals('MetadataTitle', $fileData['properties']['title']);
        self::assertEquals('text/plain', $fileData['properties']['mimeType']);
        self::assertEquals('text', $fileData['properties']['type']);
        self::assertEquals('csv.csv', $fileData['properties']['filename']);
        self::assertEquals('/typo3conf/ext/headless_bootstrap_package/Tests/Functional/Fixtures/csv.csv', $fileData['properties']['originalUrl']);
        self::assertEquals('125 B', $fileData['properties']['size']);
        self::assertEquals('csv', $fileData['properties']['extension']);
        self::assertEquals('0', $fileData['properties']['dimensions']['width']);
        self::assertEquals('0', $fileData['properties']['dimensions']['height']);

        self::assertEquals(
            'test00;test01;test02;test03;test04
test10;test11;test12;test13;test14
test20;test21;test22;test23;test24
test30;test31;test32;test33;test34
test40;test41;test42;test43;test44',
            $contentElement['content']['media'][0]['bodytext']
        );

        self::assertCount(5, $contentElement['content']['media'][0]['table']);

        /**
         * @var int $rowKey
         * @var string[] $row
         */
        foreach ($contentElement['content']['media'][0]['table'] as $rowKey => $row) {
            self::assertCount(5, $row);
            /**
             * @var int $colKey
             * @var string $value
             */
            foreach ($row as $colKey => $value) {
                self::assertEquals(sprintf('test%d%d', $rowKey, $colKey), $value);
            }
        }
    }
}
