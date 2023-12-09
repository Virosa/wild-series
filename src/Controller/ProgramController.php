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


    //#[Route('/show/{id<^[0-9]+$>}', name: 'show')]
    #[Route('/program/{id}/', name: 'show')]
    //public function show(int $id, ProgramRepository $programRepository):Response
    public function show(Program $program): Response
    {
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
        
            #[Route("/program/{program}/season/{season}", name:'season_show')]
            public function showProgramSeason(Program $program, Season $season): Response
            {
            return $this->render('program/season_show.html.twig', [
                'program' => $program,
                'season' => $season,
            ]);
            }

            #[Route("/program/{programId}/season/{seasonId}/episode/{episodeId}", name: 'episode_show', methods: ['GET'])]
            public function showEpisode(
                //Program $program, Season $season, Episode $episode): Response
                #[MapEntity(mapping: ['programId' => 'id'])] Program $program,
                #[MapEntity(mapping: ['seasonId' => 'id'])] Season $season,
                #[MapEntity(mapping: ['episodeId' => 'id'])] Episode $episode,): Response
            {
              return $this->render('program/episode_show.html.twig', [
                'program' => $program,
                'season' => $season,
                'episode' => $episode,
              ]);  
            }

    }
