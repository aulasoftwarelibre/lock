<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\OrganizationUnit;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrganizationUnitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrganizationUnit::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission('ORGANIZATION_UNIT_MEMBER')
            ->setEntityLabelInSingular('Grupo')
            ->setEntityLabelInPlural('Grupos');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ->setPermission(Action::DETAIL, 'ROLE_USER')
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN');
    }

    /**
     * {@inheritdoc}
     */
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name')
            ->setLabel('Nombre');

        $component = $pageName === Action::DETAIL
            ? ArrayField::new('members')
            : AssociationField::new('members');

        yield $component
            ->setLabel('Miembros');
    }
}
