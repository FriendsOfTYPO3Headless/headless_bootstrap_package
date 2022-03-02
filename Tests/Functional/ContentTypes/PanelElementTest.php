<?php

/*
 * This file is part of the "headless_bootstrap_package" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

declare(strict_types=1);

use FriendsOfTYPO3Headless\HeadlessBootstrapPackage\Test\Functional\ContentTypes\BaseContentTypeTest;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;

class PanelElementTest extends BaseContentTypeTest
{
    public function testHeaderContentElement()
    {
        $response = $this->executeFrontendRequest(
            new InternalRequest('https://website.local/')
        );

        self::assertEquals(200, $response->getStatusCode());

        $fullTree = json_decode((string)$response->getBody(), true);

        $contentElement = $fullTree['content']['colPos0'][0];

        // content element specific tests
        self::assertEquals($contentElement['content']['panelClass'], 'secondary', 'panelClass mismatch');
        self::assertEquals($contentElement['content']['bodytext'], 'Lorem ipsum dolor sit amet', 'bodytext mismatch');

        // general tests
        $this->checkDefaultContentFields($contentElement, 1, 1, 'panel', 0);
        $this->checkAppearanceFields($contentElement, 'layout-1', 'Frame', 'SpaceBefore', 'SpaceAfter', 'embedded', 'primary', '1', '1');
        $this->checkHeaderFields($contentElement, 'Header', 'SubHeader', 1, 2);
    }
}