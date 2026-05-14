<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ConferenceRepository;
use App\Repository\CommentRepository;
use App\Entity\Conference;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\ConferenceType;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\PhotoUploader;

final class ConferenceController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(ConferenceRepository $conferenceRepository): Response
    {
        return $this->render('conference/index.html.twig');
    }

    #[Route('/conference/new', name: 'conference_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $conference = new Conference();
        $form = $this->createForm(ConferenceType::class, $conference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Ręczne generowanie sluga USUNIĘTE!
            // Zrobi to za nas ConferenceListener tuż przed wykonaniem persist.

            $entityManager->persist($conference);
            $entityManager->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('conference/new.html.twig', [
            'conference_form' => $form,
        ]);
    }

    #[Route('/conference/{slug}', name: 'conference')]
    public function show(
        Request $request,
        Conference $conference,
        CommentRepository $commentRepository,
        EntityManagerInterface $entityManager,
        PhotoUploader $photoUploader,
    ): Response {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();
            if ($photo) {
                $filename = $photoUploader->upload($photo);
                $comment->setPhotoFilename($filename);
            }

            $comment->setConference($conference);
            // Daty (createdAt) nie ustawiamy, bo dba o to Lifecycle Callback (PrePersist) w encji
            
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('conference', ['slug' => $conference->getSlug()]);
        }

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentPaginator($conference, $offset);

        return $this->render('conference/show.html.twig', [
            'conference' => $conference,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
            'comment_form' => $form,
        ]);
    }
}