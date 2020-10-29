<?php

namespace App\Controller\Admin;

use App\Entity\Secret;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class SecretCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Secret::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            "site",
            "account",
            "password",
            AssociationField::new("organizationUnit")
        ];
    }
}
