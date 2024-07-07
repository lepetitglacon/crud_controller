<?php

namespace Petitglacon\CrudController\Controller;

use Petitglacon\CategoryTreebuilder\Domain\Model\Category;
use Petitglacon\CategoryTreebuilder\Domain\Repository\CategoryRepository;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class CategoryController extends AbstractCrudController
{

    public function __construct(
        protected PersistenceManager $persistenceManager,
        protected CategoryRepository $categoryRepository,
    )
    {
        $this->domainObject = Category::class;
        $this->domainObjectRepository = $categoryRepository;
        $this->pluginName = 'tx_crudcontroller_category';
        parent::__construct($this->persistenceManager, $this->categoryRepository);
    }

    /**
     * @param Petitglacon\CategoryTreebuilder\Domain\Model\Category $domainObject
     */
    public function showAction($domainObject)
    {
        return parent::showAction($domainObject);
    }

    /**
     * @param Petitglacon\CategoryTreebuilder\Domain\Model\Category $domainObject
     */
    public function createAction($domainObject)
    {
        return parent::createAction($domainObject);
    }

    /**
     * @param Petitglacon\CategoryTreebuilder\Domain\Model\Category $domainObject
     */
    public function editAction($domainObject)
    {
        return parent::editAction($domainObject);
    }

    /**
     * @param Petitglacon\CategoryTreebuilder\Domain\Model\Category $domainObject
     */
    public function updateAction($domainObject)
    {
        return parent::updateAction($domainObject);
    }

    /**
     * @param Petitglacon\CategoryTreebuilder\Domain\Model\Category $domainObject
     */
    public function deleteAction($domainObject)
    {
        return parent::deleteAction($domainObject);
    }

}