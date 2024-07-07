<?php

namespace Petitglacon\CrudController\EventListener;

use Petitglacon\CrudController\Event\UpdateActionEvent;

class UpdateActionListener
{

    public function __invoke(UpdateActionEvent $event): void
    {
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($event, '$event');
        exit;
    }

}