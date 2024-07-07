<?php

namespace Petitglacon\CrudController\Event;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class UpdateActionEvent extends AbstractEvent
{
    public function __construct(
        protected array           $data = [],
        protected ?string         $table = null,
        protected ?AbstractEntity $domainObject = null,
    )
    {
        parent::__construct($this->data);
    }

    public function getTable(): ?string
    {
        return $this->table;
    }

    public function setTable(?string $table): void
    {
        $this->table = $table;
    }

    public function getDomainObject(): ?AbstractEntity
    {
        return $this->domainObject;
    }

    public function setDomainObject(?AbstractEntity $domainObject): void
    {
        $this->domainObject = $domainObject;
    }


}