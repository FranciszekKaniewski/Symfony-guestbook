<?php

namespace App\EventListener;

use App\Entity\Conference;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Conference::class)]
class ConferenceListener
{
    public function __construct(
        private SluggerInterface $slugger
    ) {}

    public function prePersist(Conference $conference): void
    {
        // Doctrine przekaże nam encję Conference tuż przed jej zapisem w bazie!
        
        // Generujemy slug, łącząc miasto i rok
        $source = $conference->getCity() . '-' . $conference->getYear();
        
        // Tworzymy przyjazny adres URL
        $slug = $this->slugger->slug($source)->lower();
        
        // Ustawiamy slug w encji
        $conference->setSlug((string) $slug);
    }
}