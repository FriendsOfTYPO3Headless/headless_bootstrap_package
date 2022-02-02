<?php

declare(strict_types=1);

namespace FriendsOfTYPO3Headless\HeadlessBootstrapPackage\DataProcessing;

use BK2K\BootstrapPackage\DataProcessing\CsvFileProcessor as BaseCsvFileProcessor;
use FriendsOfTYPO3\Headless\DataProcessing\FilesProcessor;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class CsvFileProcessor extends BaseCsvFileProcessor
{
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        $processedData = parent::process($cObj, $contentObjectConfiguration, $processorConfiguration, $processedData);

        $filesProcessor = GeneralUtility::makeInstance(FilesProcessor::class);

        $processedFilesData = $filesProcessor->process(
            $cObj,
            $contentObjectConfiguration,
            ['references.' => ['fieldName' => 'media']],
            []
        );

        foreach ($processedData['file'] as $key => $value) {
            $processedData['file'][$key]['file'] = $processedFilesData['media'][$key];
        }

        return $processedData;
    }
}
