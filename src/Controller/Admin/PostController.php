<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Security\Core\Security;

class PostController extends EasyAdminController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
        $result = parent::createListQueryBuilder($entityClass, $sortDirection, $sortField, $dqlFilter);
        if ($this->security->isGranted('ROLE_AUTHOR') && method_exists($entityClass, 'getAuthor')) {
            $result->andWhere('entity.author = :user');
            $result->setParameter('user', $this->getUser());
        }
        return $result;
    }

    protected function createSearchQueryBuilder($entityClass, $searchQuery, array $searchableFields, $sortField = null, $sortDirection = null, $dqlFilter = null)
    {
        $result = parent::createListQueryBuilder($entityClass, $sortDirection, $sortField, $dqlFilter);
        if ($this->security->isGranted('ROLE_AUTHOR') && method_exists($entityClass, 'getAuthor')) {
            $result->andWhere('entity.author = :user');
            $result->setParameter('user', $this->getUser());
        }
        return $result;
    }

}
