<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Team;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    #[Route('/team', name: 'team')]
    public function index(EntityManagerInterface $entity, PaginatorInterface $paginator , Request $request,): Response
    {
        $query = $entity->getRepository(Team::class)->createQueryBuilder('t')->orderBy('t.id','desc')->getQuery();

        $query = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Get the current page from the request
            $request->query->getInt('perPage', 10) // Number of items per page
        );
        return $this->getPaginatedResults($query);
    }

    /**
     * @param PaginationInterface $query
     * @return JsonResponse
     */
    public function getPaginatedResults(PaginationInterface $query): JsonResponse
    {
        return $this->json([
            'data' => $query->getItems(),
            'pagination' => [
                'total' => $query->getTotalItemCount(),
                'pages' => $query->getPageCount(),
                'page' => $query->getCurrentPageNumber(),
                'perPage' => $query->getItemNumberPerPage()
            ]
        ]);
    }

    #[Route('/team/add', name: 'team_add',methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $entity): Response
    {
        $teamFromRequest = $request->toArray();
        /** @var TeamRepository $teamRepository */
        $teamRepository = $entity->getRepository(Team::class);
        $team = $teamRepository->creatOrGetTeam($teamFromRequest);
        foreach ($teamFromRequest['players'] as $player) {
            /** @var PlayerRepository $playerRepository */
            $playerRepository = $entity->getRepository(Player::class);
            $playerObj = $playerRepository->createOrGetPlayer($player, $team);
            if (isset($playerObj)) {
                $team->addPlayer($playerObj);
            }
        }
        return $this->json([
            'message' => 'Team added successfully',
            'data' => $team
        ]);
    }
}