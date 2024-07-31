<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Security\Voter\RecipeVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

//#[Route('admin/recettes', name: 'admin.recipe.')]

class RecipeController extends AbstractController
{
    #[Route('/admin/recettes', name: 'admin.recipe.index')]
    #[IsGranted(RecipeVoter::LIST)]
    public function index(RecipeRepository $repository, Request $request, Security $security): Response
    {
        $userId = $security->getUser()->getId();
        $canListAll = $security->isGranted(RecipeVoter::LIST_ALL);
        $recipes = $repository->paginateRecipes($request, $canListAll ? null : $userId);
        return $this->render('admin/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/recettes', name: 'recipe.index')]
    public function home(RecipeRepository $repository, Request $request): Response
    {
        // Get the paginated recipes
        $paginator = $repository->paginateRecipe($request);

        return $this->render('recipe/index.html.twig', [
            'recipes' => $paginator,
        ]);
    }

    #[Route('/admin/recette/{slug}-{id}', name: 'admin.recipe.show', requirements: ['id'=> Requirement::DIGITS, 'slug' => Requirement::ASCII_SLUG])]
    public function AdminShow(RecipeRepository $recipeRepository, int $id): Response
    {
        $recipe = $recipeRepository->find($id);

        if (!$recipe) {
            throw $this->createNotFoundException('The recipe does not exist');
        }

        return $this->render('admin/recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/recette/{slug}-{id}', name: 'recipe.show', requirements: ['id'=> Requirement::DIGITS, 'slug' => Requirement::ASCII_SLUG])]
    public function show(RecipeRepository $recipeRepository, int $id): Response
    {
        $recipe = $recipeRepository->find($id);

        if (!$recipe) {
            throw $this->createNotFoundException('The recipe does not exist');
        }

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    //create a new recipe
    #[Route('/admin/recettes/create', name: 'admin.recipe.create')]
    #[IsGranted(RecipeVoter::CREATE)]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'recipe created successful');

            return $this->redirectToRoute('admin.recipe.index');
        }

        return $this->render('admin/recipe/create.html.twig', [
            'recipe' => $recipe,
            'form' => $form
        ]);
    }

    //modification of data and generate form to this one
    #[Route('/admin/recettes/{id}', name: 'admin.recipe.edit', requirements: ['id' => Requirement::DIGITS], methods: ['GET', 'POST'])]
    #[IsGranted(RecipeVoter::EDIT, subject: 'recipe')]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        //verification of submitted and valid format data to the form
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'recipe updated successful');

            //redirection to the recipe.show page
            return $this->redirectToRoute('admin.recipe.index');
        }

        return $this->render('admin/recipe/edit.html.twig',[
            'recipe' => $recipe,
            'form' => $form
        ]);
    }

    //delete a recipe
    #[Route('/{id}', name: 'admin.recipe.delete', requirements: ['id' => Requirement::DIGITS])]
    #[IsGranted(RecipeVoter::EDIT, subject: 'recipe')]
    public function delete(Recipe $recipe, EntityManagerInterface $em): RedirectResponse
    {
           $em->remove($recipe);
           $em->flush();
           $this->addFlash('success', 'recipe deleted successful');

           return $this->redirectToRoute('admin.recipe.index');
    }
}
