<?php

namespace Petitglacon\CrudController\Event;

abstract class AbstractEvent
{
    public function __construct(
        protected array $data
    )
    {}

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }
}