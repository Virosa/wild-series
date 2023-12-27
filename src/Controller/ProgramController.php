<?php

namespace App\Controller;

use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use App\Repository\CommentRepository;
use App\Entity\Program;
use App\Entity\Episode;
use App\Entity\Season;
use App\Entity\Comment;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use App\Form\ProgramType;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\ProgramDuration;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\User\UserInterface;


#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();
        return $this->render(
            'program/index.html.twig',
            [
                'programs' => $programs,
            ]);

    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, MailerInterface $mailer,
    EntityManagerInterface $entityManager, SluggerInterface $slugger) : Response
        {
       
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $slug = $slugger->slug($program->getTitle());
                $program->setSlug($slug);
                $entityManager->persist($program);
                $entityManager->flush();

                $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('your_email@example.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('Program/newProgramEmail.html.twig', [
                'program' => $program
                ]
            ));

                $mailer->send($email);

              $this->addFlash('success', 'The new program has been edited');
              return $this->redirectToRoute('program_index');
            }

            return $this->render('program/new.html.twig', [
                'form' => $form,
            ]);
        }

    //#[Route('/show/{id<^[0-9]+$>}', name: 'show')]
    #[Route('/program/{slug}/', name: 'show', methods: ['GET'])]
    //public function show(int $id, ProgramRepository $programRepository):Response
    public function show(Program $program, SluggerInterface $slugger, ProgramDuration $programDuration): Response
    {
        $slug = $slugger->slug($program->getTitle());
        $program->setSlug($slug);

        $duration = $programDuration->calculate($program);
    /*{
        //$program = $programRepository->findOneBy(['id' => $id]);
        //$program = $programRepository->find($id);

       if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }*/
        return $this->render('program/show.html.twig', [
            'program' => $program, 
            'duration' => $duration,
        ]);
    }

   /* #[Route('/{programId}/seasons/{seasonId}', name:'season_show' )]
    public function showSeason(int $programId, int $seasonId, ProgramRepository $programRepository, SeasonRepository $seasonRepository): Response

    {
        $program = $programRepository->findOneBy(['id' => $programId]);
        if (!$program) {
            throw $this->createNotFoundException(
            'Programme non trouvé'
            );
        }
        $season = $seasonRepository->findOneBy(['id'=> $seasonId, 'program'=>$program]);
        if (!$season) {
            throw $this->createNotFoundException(
                'saison non trouvée'
            );
        }
        return $this->render(
            'program/season_show.html.twig',
            [
                'program' => $program,
                'season' => $season,
            ]);*/
        
            #[Route("/program/{slug}/season/{season}", name:'season_show')]
            public function showProgramSeason ( Program $program, Season $season, SluggerInterface $slugger): Response
            {
            
                $slug = $slugger->slug($program->getTitle());
                $program->setSlug($slug);
                return $this->render('program/season_show.html.twig', [
                'program' => $program,
                'season' => $season,
            ]);
            }

            #[Route("/program/{programId}/season/{seasonId}/episode/{episodeId}", name: 'episode_show', methods: ['GET', 'POST'])]
            public function showEpisode(
                //Program $program, Season $season, Episode $episode): Response
                #[MapEntity(mapping: ['programId' => 'id'])] Program $program,
                #[MapEntity(mapping: ['seasonId' => 'id'])] Season $season,
                #[MapEntity(mapping: ['episodeId' => 'id'])] Episode $episode,
                SluggerInterface $slugger, CommentRepository $commentRepository,
                Request $request,
                EntityManagerInterface $entityManager,
                ): Response
            {
                // Charger les commentaires associés à l'épisode
                $comments = $commentRepository->findBy(['episode' => $episode], ['createdAt' => 'ASC']);
                // Vérifier si l'utilisateur est connecté
                $user = $this->getUser();
                // Si l'utilisateur est connecté, créer et traiter le formulaire de commentaire
                if ($user) {
                    $comment = new Comment();
                    $comment->setEpisode($episode);
                    $comment->setAuthor($user);

                    $commentForm = $this->createForm(CommentType::class, $comment);
                    $commentForm->handleRequest($request);
                    if ($commentForm->isSubmitted() && $commentForm->isValid()) {
                        // Enregistrer le commentaire dans la base de données
                        $entityManager->persist($comment);
                        $entityManager->flush();

                        // Rediriger vers la même page de l'épisode après la soumission du commentaire
                        return $this->redirectToRoute('program_episode_show', [
                            'programId' => $program->getId(),
                            'seasonId' => $season->getId(),
                            'episodeId' => $episode->getId(),
                        ], Response::HTTP_SEE_OTHER);

                    }
                } else {
                    // L'utilisateur n'est pas connecté, $commentForm sera null, ne sera pas visible.
                    $commentForm = null;
                }

                    $slug = $slugger->slug($program->getTitle());
                    $program->setSlug($slug);
                    return $this->render('program/episode_show.html.twig', [
                        'program' => $program,
                        'season' => $season,
                        'episode' => $episode,
                        'comments' => $comments,  // Passer les commentaires à la vue
                        'commentForm' => $commentForm,
                    ]);

            }

            //#[Route('/all', name: 'index', methods: ['GET'])]
            #[Route('/all', name: 'program_index_all', methods: ['GET'])]
            public function indexAllAdmin(ProgramRepository $programRepository): Response
           {
               return $this->render('program/indexAllProgram.html.twig', [
                   'programs' => $programRepository->findAll(),
               ]);
           }
    }


