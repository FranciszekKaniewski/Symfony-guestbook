<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // Identyfikator (ukryty podczas dodawania/edycji)
            IdField::new('id')->hideOnForm(),
            
            // Relacja do konferencji
            AssociationField::new('conference', 'Konferencja'),

            // Podstawowe dane autora
            TextField::new('author', 'Autor'),
            EmailField::new('email', 'Adres e-mail'),
            UrlField::new('website', 'Strona WWW'),
            
            // Treść komentarza
            TextareaField::new('text', 'Treść komentarza'),

            // Obsługa pliku ze zdjęciem
            ImageField::new('photoFilename', 'Zdjęcie')
                ->setBasePath('/uploads/photos') // Ścieżka bazowa wyświetlania w przeglądarce
                ->setUploadDir('public/uploads/photos') // Ścieżka zapisu na serwerze (dostosuj jeśli używasz innej)
                ->setUploadedFileNamePattern('[randomhash].[extension]') // Losowa nazwa pliku
                ->setRequired(false),

            // Daty (tylko do odczytu, ukryte w formularzu, bo zarządzają nimi LifecycleCallbacks)
            DateTimeField::new('createdAt', 'Data utworzenia')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Data aktualizacji')->hideOnForm(),
        ];
    }
}
