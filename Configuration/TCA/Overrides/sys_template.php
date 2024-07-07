<?php


call_user_func(function()
{
    $extensionKey = 'crud_controller';

    /**
     * Default TypoScript
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $extensionKey,
        'Configuration/TypoScript',
        'Some descriptive title'
    );
});