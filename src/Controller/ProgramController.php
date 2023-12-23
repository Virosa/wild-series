<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use App\Entity\Program;
use App\Entity\Episode;
use App\Entity\Season;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use App\Form\ProgramType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\ProgramDuration;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;



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

            #[Route("/program/{programId}/season/{seasonId}/episode/{episodeId}", name: 'episode_show', methods: ['GET'])]
            public function showEpisode( SluggerInterface $slugger,
                //Program $program, Season $season, Episode $episode): Response
                #[MapEntity(mapping: ['programId' => 'id'])] Program $program,
                #[MapEntity(mapping: ['seasonId' => 'id'])] Season $season,
                #[MapEntity(mapping: ['episodeId' => 'id'])] Episode $episode,): Response
            {

                $slug = $slugger->slug($program->getTitle());
                $program->setSlug($slug);
              return $this->render('program/episode_show.html.twig', [
                'program' => $program,
                'season' => $season,
                'episode' => $episode,
              ]);  
            }

    }
