<?php

namespace App\Controller\Admin;

use App\Entity\Conference;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;

class ConferenceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Conference::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // Identyfikator (zazwyczaj ukrywany w formularzu dodawania/edycji)
            IdField::new('id')->hideOnForm(),
            
            // Podstawowe dane tekstowe
            TextField::new('city', 'Miasto'),
            TextField::new('year', 'Rok'),
            
            // Pole logiczne (boolean)
            BooleanField::new('isInternational', 'Międzynarodowa?'),
            
            // Dłuższy tekst z edytorem WYSIWYG
            TextEditorField::new('description', 'Opis'),
            
            // Liczba całkowita
            IntegerField::new('maxAttendees', 'Maksymalna liczba uczestników'),
            
            // Automatyczne generowanie sluga na podstawie nazwy miasta
            SlugField::new('slug')->setTargetFieldName('city'),
            
            // Pola relacyjne (opcjonalne, włącz jeśli chcesz przypisywać prelegentów z poziomu konferencji)
            // AssociationField::new('speakers', 'Prelegenci'),
        ];
    }
}
