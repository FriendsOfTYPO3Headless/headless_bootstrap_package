<?php

/*
 * This file is part of the "headless_bootstrap_package" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FriendsOfTYPO3Headless\HeadlessBootstrapPackage\DataProcessing;

use BK2K\BootstrapPackage\DataProcessing\IconsDataProcessor as BaseIconsDataProcessor;
use BK2K\BootstrapPackage\Icons\FileIcon;
use BK2K\BootstrapPackage\Icons\SvgIcon;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class IconsDataProcessor extends BaseIconsDataProcessor
{
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        $processedData = parent::process($cObj, $contentObjectConfiguration, $processorConfiguration, $processedData);

        $targetVariableName = (string)$cObj->stdWrapValue('as', $processorConfiguration, 'icon');

        $icon = $processedData[$targetVariableName];

        if (null === $icon) {
            return $processedData;
        }

        switch (\get_class($icon)) {
            case FileIcon::class:
                $icon = [
                    'name' => $icon->getName(),
                    'previewImage' => $icon->getFile()->getPublicUrl(),
                    'height' => $icon->getHeight(),
                    'width' => $icon->getWidth(),
                ];

                break;
            case SvgIcon::class:
                $icon = [
                    'previewImage' => PathUtility::getPublicResourceWebPath($icon->getIdentifier()),
                    'name' => $icon->getName(),
                    'height' => $icon->getHeight(),
                    'width' => $icon->getWidth(),
                ];

                break;
        }

        $processedData[$targetVariableName] = $icon;

        return $processedData;
    }
}
