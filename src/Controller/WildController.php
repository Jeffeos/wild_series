<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Repository\ProgramRepository;
use App\Repository\EpisodeRepository;
use App\Service\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Season;
use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Episode;
use App\Form\ProgramSearchType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/wild", name="wild_")
 */
class WildController extends AbstractController
{
    /**
     * Show all rows from Program's entity
     *
     * @Route("/", name="index")
     * @param Request $request
     * @return Response A response Instance
     */
    public function index(Request $request) :Response
    {

        // Create a form to retrieve Programs
        $form = $this->createForm(ProgramSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();

            $programs = $this->getDoctrine()
                ->getRepository(Program::class)
                ->findBy(['title' => ucwords($data['searchField'])]);
        } else {
            // retrieve all programs from all categories
            $programs = $this->getDoctrine()
                ->getRepository(Program::class)
                ->findAll();
            if (!$programs) {
                throw $this->createNotFoundException(
                    'No program found in program\'s table.'
                );
            }
        }

        return $this->render(
            'wild/index.html.twig', [
                'programs' => $programs,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Getting a program with a formatted slug for title
     *
     * @param string $slug The slugger
     * @route("/show/{slug}",
     *     requirements={"slug"="^[a-z\d\-]+$"},
     *     defaults={"slug"= "Aucune série sélectionnée, veuillez choisir une série"},
     *     name="show")
     * @return Response
     */
    public function showByProgram(?string $slug) :Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }
        // Use getSeasons() of program.php
        $seasons = $program->getSeasons();

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
            'slug'  => $slug,
        ]);
    }

    /**
     * Getting programs based on their category
     *
     * @Route("/category/{categoryName}", name="show_category")
     * @param string $categoryName
     * @return Response
     */
    public function showByCategory(string $categoryName) :Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findBy(['name' => mb_strtolower($categoryName)]);

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(array('category' => $category), array('id' => 'ASC'), 3);

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program with '.$categoryName.' category, found in program\'s table.'
            );
        }

        return $this->render('wild/category.html.twig', [
            'programs' => $programs,
            'category'  => $category,
        ]);
    }

    /**
     * Getting episodes based on the season selected
     *
     * @Route("/season/{seasonId}", name="show_season")
     * @param integer $seasonId
     * @return Response
     */
    public function showBySeason(int $seasonId) :Response
    {
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id' => $seasonId]);

        $program = $season->getProgram();
        $episodes = $season->getEpisodes();

        return $this->render('wild/season.html.twig', [
            'program'=> $program,
            'season' => $season,
            'episodes' => $episodes
        ]);
    }

    /**
     * Getting episode info from episode number
     *
     * @Route("/episode/{id}", name="episode")
     * @param Episode $episode
     * @return Response
     */
    public function showEpisodes(Episode $episode) :Response
    {
        $season = $episode->getSeason();
        $program = $season->getProgram();

        return $this->render('wild/episode.html.twig', [
            'episode' => $episode,
            'season' => $season,
            'program' => $program
        ]);
    }
}