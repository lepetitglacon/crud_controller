<?php

namespace Petitglacon\CrudController\Hook;

use Petitglacon\CrudController\Event\UpdateActionEvent;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TceMain
{

    function processDatamap_preProcessFieldArray(array $fields, string $table, int $uid, DataHandler &$tceMain)
    {
        $eventDispatcher = GeneralUtility::makeInstance(EventDispatcherInterface::class);
        $event = $eventDispatcher->dispatch(
            new UpdateActionEvent($fields, $table),
        );
        $fields = $event->getData();
    }

}