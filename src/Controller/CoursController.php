<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Entity\Professeur;
use App\Enum\Module;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\CoursRepository;
use App\Repository\ProfesseurRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Cours;


class CoursController extends AbstractController
{
    #[Route('/cours', name: 'app_cours')]
    public function index(): Response
    {
        $htmlContent = file_get_contents('/home/bouba/Documents/examensymfony/src/Views/Cours/index.html');

        return new Response($htmlContent);
    }


    #[Route('/api/cours', name: 'api_cours')]
    public function apiCours(CoursRepository $coursRepository, PaginatorInterface $paginator, Request $request): JsonResponse
    {
        // Récupérer la page courante depuis la requête (par défaut, page 1)
        $page = $request->query->getInt('page', 1);
        $limit = 5;  // Nombre d'articles par page

        // Créer une requête pour récupérer tous les articles
        $queryBuilder = $coursRepository->createQueryBuilder('a');

        // Utiliser KNP Paginator pour paginer les résultats
        $pagination = $paginator->paginate(
            $queryBuilder,   // La requête
            $page,           // La page actuelle
            $limit           // Nombre d'articles par page
        );

        // Extraire les articles et les informations de pagination
        $cours = $pagination->getItems();
        $totalItems = $pagination->getTotalItemCount();
        $totalPages = $pagination->count();

        // Préparer les données à retourner
        $data = [
            'cours' => array_map(function ($cour) {
                return [
                    'id' => $cour->getId(),
                    'nomCours' => $cour->getNomCours(),
                    'module' => $cour->getModule(),
                ];
            }, $cours),
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $totalItems,
            ]
        ];

        // Retourner les données sous forme de JSON
        return $this->json($data);
    }

    #[Route('/api/Module', name: 'api_modules', methods: ['GET'])]
    public function apiModules(): JsonResponse
    {
        $modules = Module::cases();
        $modulesData = array_map(function (Module $module) {
            return [
                'value' => $module->value,
                'name' => $module->name,
            ];
        }, $modules);

        return new JsonResponse(['modules' => $modulesData]);
    }

    #[Route('/api/Professeur', name: 'api_professeur')]
    public function apiProfesseur(ProfesseurRepository $professeurRepository): JsonResponse
    {
        $professeurs = $professeurRepository->findAll();
        $data = [
            'professeurs' => array_map(function ($professeur) {
                return [
                    'id' => $professeur->getId(),
                    'nom' => $professeur->getNom(),
                ];
            }, $professeurs),
        ];
        return $this->json($data);
    }

    #[Route('/api/Classe', name: 'app_classe')]
    public function apiClasse(ClasseRepository $classeRepository): JsonResponse
    {
        $classes = $classeRepository->findAll();

        $data = [
            'classes' => array_map(function ($classe) {
                return [
                    'id' => $classe->getId(),
                    'nomClasse' => $classe->getNomClasse(),
                ];
            }, $classes),
        ];
        return $this->json($data);
    }

    #[Route('/api/add/cours', name: 'app_cours2')]
    public function addCours(Request $request, CoursRepository $coursRepository, ClasseRepository $classeRepository, ProfesseurRepository $professeurRepository, EntityManagerInterface $entityManager): Response
    {
        $data = $request->request->all();
        $cours = new Cours();
        $nomCours = $data['nomCours'];
        $moduleId = $data['ModuleId'];
        $classeId = $data['ClasseId'];
        $professeurId = $data['ProfesseurId'];
        // $classe = $classeRepository->find($classeId);
        $professeur = $professeurRepository->find($professeurId);
        $module = Module::from($moduleId);
        $cours->setNomCours($nomCours);
        // $cours->setClasse($classe);
        $cours->setProfesseur($professeur);
        $cours->setModule($module);
        $entityManager->persist($cours);
        $entityManager->flush();
        return $this->json($cours);
    }

    #[Route('/cours/store', name: 'app_cours3')]
    public function store(): Response
    {
        $htmlContent = file_get_contents('/home/bouba/Documents/examensymfony/src/Views/Cours/form.html');

        return new Response($htmlContent);
    }
}
