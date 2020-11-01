<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\OrganizationUnit;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class OrganizationUnitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrganizationUnit::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            'name',
            AssociationField::new('members'),
        ];
    }
}
