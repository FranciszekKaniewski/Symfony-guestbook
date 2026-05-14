<?php

namespace App\Service;

use App\Entity\Conference;
use Symfony\Component\String\Slugger\SluggerInterface;

class ConferenceService
{
    public function __construct(
        private SluggerInterface $slugger
    ) {}

    public function generateSlug(Conference $conference): string
    {
        $source = $conference->getCity() . '-' . $conference->getYear();

        return (string) $this->slugger->slug($source)->lower();
    }
}