<?php

(static function (): void {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'CrudController',
        'Category',
        'Category',
    );
})();