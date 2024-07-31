<?php

namespace App\Controller\API;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class RecipesController extends AbstractController
{

    #[Route('/api/recipes', methods: ['GET'])]
    public function index(RecipeRepository $repository)
    {
        $recettes = $repository->findAll();
        return $this->json($recettes, 200, [], [
            'groups' =>['recipes.index']
        ]);
    }

    #[Route('/api/recipes', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $rececette = new Recipe();
        $rececette->setCreatedAt(new \DateTimeImmutable());
        $rececette->setUpdatedAt(new \DateTimeImmutable());
        $content = $request->getContent();
        $recipe = $serializer->deserialize($content, Recipe::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $rececette
        ]);
        $em->persist($recipe);
        $em->flush();
        return $this->json($recipe, 201, [], [
            'groups' =>['recipes.create']
        ]);
    }
}
