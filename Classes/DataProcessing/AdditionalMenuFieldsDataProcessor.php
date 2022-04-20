<?php

/*
 * This file is part of the "headless_bootstrap_package" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FriendsOfTYPO3Headless\HeadlessBootstrapPackage\DataProcessing;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/*
  * add in MenuProcessor dataProcessing e.g.:
   42 = FriendsOfTYPO3Headless\HeadlessBootstrapPackage\DataProcessing\AdditionalMenuFieldsDataProcessor
   42 {
        fieldName = subtitle
        as = subheader
    }
  */
class AdditionalMenuFieldsDataProcessor implements DataProcessorInterface
{
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $fieldName = $cObj->stdWrapValue('fieldName', $processorConfiguration ?? []);
        if ($fieldName === '') {
            return $processedData;
        }

        $targetVariableName = $cObj->stdWrapValue('as', $processorConfiguration, $fieldName);

        $processedData[$targetVariableName] = $cObj->data[$fieldName];

        return $processedData;
    }
}
