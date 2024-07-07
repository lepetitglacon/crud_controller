<?php


(static function (): void {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    // extension name, matching the PHP namespaces (but without the vendor)
        'CrudController',
        // arbitrary, but unique plugin name (not visible in the backend)
        'Category',
        // plugin title, as visible in the drop-down in the backend, use "LLL:" for localization
        'Category',
    );
})();