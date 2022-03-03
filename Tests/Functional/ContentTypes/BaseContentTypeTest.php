<?php

/*
 * This file is part of the "headless_bootstrap_package" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FriendsOfTYPO3Headless\HeadlessBootstrapPackage\Tests\Functional\ContentTypes;

use FriendsOfTYPO3Headless\HeadlessBootstrapPackage\Tests\Functional\BaseTest;

abstract class BaseContentTypeTest extends BaseTest
{
    /**
     * set up objects
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->importDataSet(__DIR__ . '/../Fixtures/content.xml');
    }

    protected function checkDefaultContentFields($contentElement, $id, $pid, $type, $colPos = 0, $categories = ''): void
    {
        self::assertEquals($id, $contentElement['id'], 'id mismatch');
        self::assertEquals($type, $contentElement['type'], 'type mismatch');
        self::assertEquals($colPos, $contentElement['colPos'], 'colPos mismatch');
        self::assertEquals($categories, $contentElement['categories'], 'categories mismatch');
    }

    protected function checkAppearanceFields(
        $contentElement,
        $layout = 'default',
        $frameClass = 'default',
        $spaceBefore = '',
        $spaceAfter = '',
        $frameLayout = '',
        $backgroundColor = '',
        $sectionIndex = '',
        $linkToTop = ''
    ): void {
        $contentElementAppearance = $contentElement['appearance'];

        self::assertEquals($layout, $contentElementAppearance['layout'], 'layout mismatch');
        self::assertEquals($frameClass, $contentElementAppearance['frameClass'], 'frameClass mismatch');
        self::assertEquals($spaceBefore, $contentElementAppearance['spaceBefore'], 'spaceBefore mismatch');
        self::assertEquals($spaceAfter, $contentElementAppearance['spaceAfter'], 'spaceAfter mismatch');
        self::assertEquals($frameLayout, $contentElementAppearance['frameLayout'], 'frameLayout mismatch');
        self::assertEquals($backgroundColor, $contentElementAppearance['backgroundColor'], 'backgroundColor mismatch');
        self::assertEquals($sectionIndex, $contentElementAppearance['sectionIndex'], 'sectionIndex mismatch');
        self::assertEquals($linkToTop, $contentElementAppearance['linkToTop'], 'linkToTop mismatch');
    }

    protected function checkHeaderFields($contentElement, $header = '', $subheader = '', $headerLayout = 0, $headerPosition = ''): void
    {
        $contentElementContent = $contentElement['content'];

        self::assertIsArray($contentElementContent);
        self::assertEquals($header, $contentElementContent['header'], 'header mismatch');
        self::assertEquals($subheader, $contentElementContent['subheader'], 'subheader mismatch');
        self::assertEquals($headerLayout, $contentElementContent['headerLayout'], 'headerLayout mismatch');
        self::assertEquals($headerPosition, $contentElementContent['headerPosition'], 'headerPosition mismatch');
        self::assertTrue(isset($contentElementContent['headerLink']), 'headerLink not set');
    }

    protected function checkHeaderFieldsLink($contentElement, $link, $urlPrefix, $target): void
    {
        $contentElementHeaderFieldsLink = $contentElement['content']['headerLink'];

        self::assertIsArray($contentElementHeaderFieldsLink, 'headerLink not an array');
        self::assertEquals($link, $contentElementHeaderFieldsLink['linkText'], 'link mismatch');
        self::assertStringStartsWith($urlPrefix, $contentElementHeaderFieldsLink['href'], 'url mismatch');
        self::assertEquals($target, $contentElementHeaderFieldsLink['target'], 'target mismatch');
    }

    protected function checkGalleryContentFields($contentElement): void
    {
        self::assertEquals(600, $contentElement['content']['gallery']['width'], 'width mismatch');
        self::assertEquals(10, $contentElement['content']['gallery']['columnSpacing'], 'columnSpacing mismatch');

        self::assertIsArray($contentElement['content']['gallery']['position'], 'position not set');
        self::assertEquals('center', $contentElement['content']['gallery']['position']['horizontal'], 'position horizontal mismatch');
        self::assertEquals('above', $contentElement['content']['gallery']['position']['vertical'], 'position vertical mismatch');
        self::assertFalse($contentElement['content']['gallery']['position']['noWrap'], 'position noWrap mismatch');

        self::assertIsArray($contentElement['content']['gallery']['count'], 'count not set');
        self::assertEquals(1, $contentElement['content']['gallery']['count']['files'], 'count files mismatch');
        self::assertEquals(1, $contentElement['content']['gallery']['count']['columns'], 'count columns mismatch');
        self::assertEquals(1, $contentElement['content']['gallery']['count']['rows'], 'count rows mismatch');

        self::assertIsArray($contentElement['content']['gallery']['border'], 'border not set');
        self::assertFalse($contentElement['content']['gallery']['border']['enabled'], 'border enabled mismatch');
        self::assertEquals(2, $contentElement['content']['gallery']['border']['width'], 'border width mismatch');
        self::assertEquals(0, $contentElement['content']['gallery']['border']['padding'], 'border padding mismatch');

        self::assertIsArray($contentElement['content']['gallery']['rows'], 'rows not set');
        self::assertCount(1, $contentElement['content']['gallery']['rows'], 'rows count mismatch');
        self::assertIsArray($contentElement['content']['gallery']['rows'][1], 'rows[1] not set');
        self::assertIsArray($contentElement['content']['gallery']['rows'][1]['columns'], 'rows.columns not set');
        self::assertCount(1, $contentElement['content']['gallery']['rows'][1]['columns'], 'rows.columns count mismatch');

        $this->checkGalleryFile($contentElement['content']['gallery']['rows'][1]['columns'][1], '/typo3conf/ext/headless_bootstrap_package/ext_icon.gif', 'image/gif', 'MetadataTitle', 18, 16, 1);
    }

    protected function checkGalleryFile($fileElement, $originalUrl, $mimeType, $title, $width, $height, $autoplay): void
    {
        self::assertTrue(isset($fileElement['publicUrl']), 'publicUrl not set');

        self::assertIsArray($fileElement['properties'], 'properties not set');
        self::assertEquals($originalUrl, $fileElement['properties']['originalUrl'], 'properties originalUrl mismatch');
        self::assertEquals($title, $fileElement['properties']['title'], 'properties title mismatch');
        self::assertEquals($mimeType, $fileElement['properties']['mimeType'], 'properties mimeType mismatch');
        self::assertEquals($autoplay, $fileElement['properties']['autoplay'], 'properties autoplay mismatch');

        self::assertIsArray($fileElement['properties']['dimensions'], 'properties dimensions not set');
        self::assertEquals($width, $fileElement['properties']['dimensions']['width'], 'properties dimensions width mismatch');
        self::assertEquals($height, $fileElement['properties']['dimensions']['height'], 'properties dimensions height mismatch');

        self::assertIsArray($fileElement['properties']['cropDimensions'], 'properties cropDimensions not set');
        self::assertEquals($width, $fileElement['properties']['cropDimensions']['width'], 'properties cropDimensions width mismatch');
        self::assertEquals($height, $fileElement['properties']['cropDimensions']['height'], 'properties cropDimensions height mismatch');
    }

    public function checkFileReferencesField($contentElement, $fieldname): void
    {
        $fileReferenceData = $contentElement[$fieldname];

        self::assertIsArray($fileReferenceData);

        foreach ($fileReferenceData as $data) {
            $this->checkGalleryFile($data, '/typo3conf/ext/headless_bootstrap_package/ext_icon.gif', 'image/gif', 'MetadataTitle', 18, 16, 1);
        }
    }

    public function checkBackgroundImageField($contentElement): void
    {
        $backgroundImage = $contentElement['appearance']['backgroundImage'][0];

        $this->checkGalleryFile($backgroundImage, '/typo3conf/ext/headless_bootstrap_package/ext_icon.gif', 'image/gif', 'MetadataTitle', 18, 16, 1);
    }

    public function checkBackgroundImageOptions(array $contentElement, string $parallax, string $fade, string $filter): void
    {
        $options = $contentElement['appearance']['backgroundImageOptions'];

        self::assertTrue(isset($contentElement['appearance']['backgroundImageOptions']), 'backgroundImageOptions not set');
        self::assertEquals($parallax, $options['parallax'], 'property parallax mismatch');
        self::assertEquals($fade, $options['fade'], 'property fade mismatch');
        self::assertEquals($filter, $options['filter'], 'property filter mismatch');
    }
}
