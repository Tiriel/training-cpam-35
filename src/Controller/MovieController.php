<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\User;
use App\Form\MovieType;
use App\Movie\Search\Provider\MovieProvider;
use App\Repository\MovieRepository;
use App\Security\Voter\MovieVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/movie')]
class MovieController extends AbstractController
{
    #[Route('', name: 'app_movie_index')]
    public function index(MovieRepository $repository): Response
    {
        return $this->render('movie/index.html.twig', [
            'movies' => $repository->findAll(),
        ]);
    }

    #[IsGranted(MovieVoter::UNDERAGE, 'movie')]
    #[Route('/{id<\d+>}', name: 'app_movie_show', methods: ['GET'])]
    public function show(?Movie $movie, ValidatorInterface $validator): Response
    {
        // $this->denyAccessUnlessGranted(MovieVoter::UNDERAGE, $movie);
        $errors = $validator->validate($movie);
        if (\count($errors) > 0) {
            // Oh noes!
        }

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/omdb/{title}', name: 'app_movie_omdb', methods: ['GET'])]
    public function omdb(string $title, MovieProvider $provider): Response
    {
        $movie = $provider->fetchOne($title);
        $this->denyAccessUnlessGranted(MovieVoter::UNDERAGE, $movie);

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/new', name: 'app_movie_new', methods: ['GET', 'POST'])]
    #[Route('/{id}/edit', name: 'app_movie_edit', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ?Movie $movie = null): Response
    {
        if ($movie) {
            $this->denyAccessUnlessGranted(MovieVoter::EDIT, $movie);
        }
        $movie ??= new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (($user = $this->getUser()) instanceof User) {
                $movie->setCreatedBy($user);
            }
            $entityManager->persist($movie);
            $entityManager->flush();

            return $this->redirectToRoute('app_movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('movie/new.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }
}
