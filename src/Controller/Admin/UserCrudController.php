<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
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
            ->setEntityLabelInSingular('Usuario')
            ->setEntityLabelInPlural('Usuarios');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->setPermission(Action::NEW, 'ROLE_ADMIN');
    }

    /**
     * {@inheritdoc}
     */
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('username')
            ->setLabel('Usuario')
            ->onlyWhenCreating();

        yield TextField::new('email')
            ->setLabel('Email');

        yield ChoiceField::new('roles')
            ->setLabel('Roles')
            ->setPermission('ROLE_ADMIN')
            ->hideOnIndex()
            ->allowMultipleChoices()
            ->setChoices([
                'Usuario' => 'ROLE_USER',
                'Administrador' => 'ROLE_ADMIN',
            ]);

        yield BooleanField::new('isGoogleAuthenticatorActivated')
            ->setLabel('Activada')
            ->hideOnForm()
            ->renderAsSwitch(false);
    }
}
