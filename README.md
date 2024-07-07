# TYPO3 Abstract CRUD Controller (POC)

This extension gives example on how to create Abstract CRUD Controller to use with any AbstractEntity using Extbase.

It is a POC so you'll need to change some configuration and classes to make it work with your domainObjects (Documentation inc)

The main example is the CategoryController, that extends from AbstractCrudController by changing its domainObject class and Repo

For now it only works with ext:headless but a fluid port would not be hard to do (Not scheduled ATM)

## Controller Features
- configurable domainObject / Repository
- index, show, new, create, edit, update, delete, methods
- form handling
  - domainObject can implement `CrudFormInterface` to get the form definition for new and edit methods
  - or override parent methods
- Events
  - TCEMain hook sends `UpdateActionEvent` PSR event
  - all methods have an event to change the current object or the data sent to the view

## Extbase problems
Extbase reflexion has hard time finding domainObject using strong type as method params that require AbstractEntity
```php
// won't work because showAction is not compatible with parent method that requires an AbstractEntity
public function showAction(Category $domainObject)
{
    return parent::showAction($domainObject);
}
```

```php
// won't work because ReflexionService does not handle union types
// @see vendor/typo3/cms-extbase/Classes/Reflection/ClassSchema.php l.398
public function showAction(Category|AbstractEntity $domainObject)
{
    return parent::showAction($domainObject);
}
```

So the only solution (for now) 

```php
/**
 * @param Petitglacon\CategoryTreebuilder\Domain\Model\Category $domainObject
 */
public function showAction($domainObject)
{
    return parent::showAction($domainObject);
}
```