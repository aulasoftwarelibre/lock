<?php

namespace App\Controller\Admin;

use App\Entity\OrganizationUnit;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrganizationUnitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrganizationUnit::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            "name",
            AssociationField::new('members')
        ];
    }
}
