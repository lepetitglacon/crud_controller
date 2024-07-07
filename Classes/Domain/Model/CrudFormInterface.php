<?php

namespace Petitglacon\CrudController\Domain\Model;

interface CrudFormInterface
{

    function getNewActionForm(): array;
    function getEditActionForm(): array;

}