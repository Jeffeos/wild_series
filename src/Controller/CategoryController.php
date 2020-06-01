<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CategoryController
 * @Route("/category", name="category_")
 * @package App\Controller
 */
class CategoryController extends AbstractController
{
    /**
     * Add a new category of programs to DB
     *
     * @Route("/add", name="add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request) : \Symfony\Component\HttpFoundation\Response
    {
        // Create a form to Add a new program
        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $data = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data);
            $entityManager->flush();
        }

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render(
            'category/add.html.twig', [
                'form' => $form->createView(),
                'categories' => $categories,
                ]
        );
    }
}