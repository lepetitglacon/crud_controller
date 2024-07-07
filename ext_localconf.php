<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use Petitglacon\CrudController\Controller\CategoryController;

// Hook TCEMain
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['Petitglacon\CrudController\Hook\TceMain'] = 'Petitglacon\CrudController\Hook\TceMain';



ExtensionUtility::configurePlugin(
    // extension name, matching the PHP namespaces (but without the vendor)
    'CrudController',
    // arbitrary, but unique plugin name (not visible in the backend)
    'Category',
    // all actions
    [
        CategoryController::class => 'index,show,new,create,edit,update,delete',
    ],
    // non-cacheable actions
    [
        CategoryController::class => 'index,show,new,create,edit,update,delete',
    ],
);