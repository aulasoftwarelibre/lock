<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Secret;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SecretCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Secret::class;
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addJsFile('js/admin/select-input.js');
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission('SECRET_SHOW')
            ->setEntityLabelInSingular('Contraseña')
            ->setEntityLabelInPlural('Contraseñas');
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
     * {@inheritDoc}
     */
    public function configureFields(string $pageName): iterable
    {
        yield UrlField::new('site')
            ->setLabel('Sitio web');

        yield TextField::new('account')
            ->setLabel('Usuario')
            ->setTemplatePath('admin/fields/pre.html.twig');

        yield TextField::new('password')
            ->setLabel('Contraseña')
            ->setTemplatePath('admin/fields/pre.html.twig')
            ->hideOnIndex();

        $component = $pageName === Action::DETAIL || $pageName === Action::INDEX
            ? ArrayField::new('organizations')
            : AssociationField::new('organizations');

        yield $component
            ->setLabel('Grupos')
            ->setRequired(true);

        yield TextareaField::new('imageFile')
            ->setLabel('Código QR')
            ->setRequired(false)
            ->setFormType(VichImageType::class)
            ->onlyOnForms();

        yield ImageField::new('image.name')
            ->setLabel('Código QR')
            ->setBasePath('/images/codes')
            ->setCssClass('ea-vich-image')
            ->onlyOnDetail();
    }
}
