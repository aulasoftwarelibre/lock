<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission('USER_EDIT')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
        ;
    }


    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('username')
            ->onlyWhenCreating()
        ;
        yield TextField::new('email');
        yield ChoiceField::new('roles')
            ->setPermission('ROLE_ADMIN')
            ->hideOnIndex()
            ->allowMultipleChoices()
            ->setChoices([
                'Usuario' => 'ROLE_USER',
                'Delegado' => 'ROLE_ADMIN',
                'Administrador' => 'ROLE_SUPER_ADMIN'
            ]);
    }
}
