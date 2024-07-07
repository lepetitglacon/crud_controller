<?php

namespace Petitglacon\CrudController\Controller;

use Petitglacon\CategoryTreebuilder\Domain\Repository\CategoryRepository;
use Petitglacon\CrudController\Event\EditActionEvent;
use Petitglacon\CrudController\Event\NewActionEvent;
use Petitglacon\CrudController\Event\UpdateActionEvent;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

class AbstractCrudController extends ActionController
{
    public string $domainObject;

    /** @var \TYPO3\CMS\Extbase\Persistence\Repository */
    public $domainObjectRepository;

    public string $pluginName = '';

    public $data = [];

    public function __construct(
        protected PersistenceManager $persistenceManager,
        protected CategoryRepository $categoryRepository,
    )
    {
        $this->initializeRepositorySettings();
    }

    public function processRequest(RequestInterface $request): ResponseInterface
    {
        // escape __trustedProperties
        /** @var ExtbaseRequestParameters $extbaseRequestParameters */
        $extbaseRequestParameters = $request->getAttribute('extbase');
        $trustedPropertiesToken = $extbaseRequestParameters->getInternalArgument('__trustedProperties');
        if (!empty($trustedPropertiesToken)) {
            $extbaseRequestParameters->setArgument('__trustedProperties', stripslashes($trustedPropertiesToken));
        }

        return parent::processRequest($request);
    }

    protected function initializeRepositorySettings(): void
    {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->domainObjectRepository->setDefaultQuerySettings($querySettings);
    }

    public function indexAction()
    {
        $this->data['objects'] = $this->objectsToArray(
            $this->domainObjectRepository->findAll()->toArray()
        );
        return $this->sendJsonResponse();
    }

    public function showAction(AbstractEntity $domainObject)
    {
        $this->data['object'] = $this->objectsToArray($domainObject);
        return $this->sendJsonResponse();
    }

    public function newAction(?AbstractEntity $domainObject = null)
    {
        if ($domainObject === null) {
            $domainObject = new $this->domainObject();
        }
        $this->data['object'] = $this->objectsToArray($domainObject);

        if (method_exists($domainObject, 'getNewActionForm')) {
            $this->data['form'] = $domainObject->getNewActionForm();
        } else {
            $this->data['form'] = $this->getNewActionForm();
        }

        $this->data['form']['link'] = $this->uriBuilder
            ->reset()
            ->uriFor('create');

        $event = $this->eventDispatcher->dispatch(new NewActionEvent($this->data));
        $this->data = $event->getData();

        return $this->sendJsonResponse();
    }

    public function createAction(AbstractEntity $domainObject)
    {
        $event = $this->eventDispatcher->dispatch(new UpdateActionEvent(
            $this->data,
            null,
            $domainObject
        ));
        $this->data = $event->getData();
        $domainObject = $event->getDomainObject();

        $this->domainObjectRepository->add($domainObject);

        return $this->sendJsonResponse();
    }

    public function editAction(AbstractEntity $domainObject)
    {
        if ($domainObject === null) {
            $domainObject = new $this->domainObject();
        }
        $this->data['object'] = $this->objectsToArray($domainObject);

        if (method_exists($domainObject, 'getNewActionForm')) {
            $this->data['form'] = $domainObject->getEditActionForm();
        } else {
            $this->data['form'] = $this->getEditActionForm($domainObject);
        }

        $this->data['form']['link'] = $this->uriBuilder
            ->reset()
            ->uriFor('update', ['domainObject' => $domainObject->getUid()]);

        $event = $this->eventDispatcher->dispatch(new EditActionEvent(
            $this->data,
        ));
        $this->data = $event->getData();
        $domainObject = $event->getDomainObject();

        return $this->sendJsonResponse();
    }

    public function updateAction(AbstractEntity $domainObject)
    {
        $event = $this->eventDispatcher->dispatch(new UpdateActionEvent(
            $this->data,
            null,
            $domainObject
        ));
        $this->data = $event->getData();
        $domainObject = $event->getDomainObject();

        $this->domainObjectRepository->update($domainObject);

        return $this->sendJsonResponse();
    }

    public function deleteAction(AbstractEntity $domainObject)
    {
        $event = $this->eventDispatcher->dispatch(new UpdateActionEvent(
            $this->data,
            null,
            $domainObject
        ));
        $this->data = $event->getData();
        $domainObject = $event->getDomainObject();

        $this->domainObjectRepository->remove($domainObject);
        $this->persistenceManager->persistAll();
        return $this->sendJsonResponse();
    }

    protected function getNewActionForm()
    {
        return [
            'elements' => [
                "$this->pluginName[domainObject][title]" => [],
                "$this->pluginName[domainObject][pid]" => [],
                "$this->pluginName[__trustedProperties]" => $this->mvcPropertyMappingConfigurationService
                    ->generateTrustedPropertiesToken(
                        [
                            "$this->pluginName[domainObject][title]",
                            "$this->pluginName[domainObject][pid]"
                        ],
                        $this->pluginName
                    )
            ]
        ];
    }

    protected function getEditActionForm($domainObject)
    {
        return [
            'elements' => [
                "$this->pluginName[domainObject][__identity]" => $domainObject->getUid(),
                "$this->pluginName[domainObject][title]" => [],
                "$this->pluginName[domainObject][pid]" => [],
                "$this->pluginName[__trustedProperties]" => $this->mvcPropertyMappingConfigurationService
                    ->generateTrustedPropertiesToken(
                        [
                            "$this->pluginName[domainObject][title]",
                            "$this->pluginName[domainObject][pid]",
                            "$this->pluginName[domainObject][__identity]"
                        ],
                        $this->pluginName
                    )
            ]
        ];
    }

    protected function objectsToArray(AbstractEntity|array $objects)
    {
        if (is_array($objects)) {
            return array_map(function ($el) {
                return $el->toArray();
            }, $objects);
        } else {
            return $objects->toArray();
        }
    }

    protected function sendJsonResponse($success = true)
    {
        $this->data['status'] = $success;
        return $this->jsonResponse(json_encode($this->data));
    }
}