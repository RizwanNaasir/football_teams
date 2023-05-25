<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Team;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use Closure;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class TeamController extends AbstractController
{
    #[Route('/team', name: 'team')]
    public function index(EntityManagerInterface $entity, PaginatorInterface $paginator, Request $request,): Response
    {
        $query = $entity->getRepository(Team::class)
            ->createQueryBuilder('t')
            ->orderBy('t.id', 'desc');
        $search = $request->query->all()['query']['search'] ?? null;
        if (isset($search)) {
            $query->where('t.name LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }
        $query = $paginator->paginate(
            $query->getQuery(),
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
        ], context: $this->normalizer());
    }

    /**
     * @return Closure[]
     */
    public function normalizer(): array
    {
        return [AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
            return $object->getId();
        }];
    }

    #[Route('/team/{id}', name: 'team_show', methods: ['GET'])]
    public function show(Team $team): JsonResponse
    {
        return $this->json([
            'data' => $team
        ], context: $this->normalizer());
    }

    #[Route('/players', name: 'players', methods: ['GET'])]
    public function players(Request $request, EntityManagerInterface $entity): JsonResponse
    {
        $search = $request->query->all()['query']['search'] ?? null;
        $teamId = $request->query->get('team_id');
        /** @var PlayerRepository $playerRepository */
        $playerRepository = $entity->getRepository(Player::class);
        $players = $playerRepository->getPlayers($search, $teamId);
        return $this->json(data: [
            'data' => $players->getResult()
        ], context: $this->normalizer());
    }

    #[Route('/team/{id}/buy-player', name: 'team_buy', methods: ['POST'])]
    public function buyPlayer(Team $team, Request $request, EntityManagerInterface $entity): JsonResponse
    {
        $playerId = $request->toArray()['playerId'];
        $amount = $request->toArray()['amount'];
        /** @var PlayerRepository $playerRepository */
        $playerRepository = $entity->getRepository(Player::class);
        $player = $playerRepository->find($playerId);
        if ($player->getTeam() === $team) {
            return $this->json([
                'message' => 'Player already owned by the team'
            ], status: Response::HTTP_BAD_REQUEST);
        }
        if ($team->getMoneyBalance() < $amount) {
            return $this->json([
                'message' => 'Team does not have enough funds'
            ], status: Response::HTTP_BAD_REQUEST);
        }
        $team->setMoneyBalance($team->getMoneyBalance() - $amount);
        $player->getTeam()->setMoneyBalance($player->getTeam()->getMoneyBalance() + $amount);
        $team->addPlayer($player);
        $player->setTeam($team);
        $entity->flush();
        return $this->json([
            'message' => 'Player bought successfully'
        ]);
    }

    #[Route('/team/{id}/sell-players', name: 'team_sell', methods: ['POST'])]
    public function sellPlayers(Team $team, Request $request, EntityManagerInterface $entity): JsonResponse
    {
        $playerIds = $request->toArray()['playerIds'];
        $amount = $request->toArray()['amount'];
        $targetTeamId = $request->toArray()['targetTeamId'];
        $targetTeam = $entity->getRepository(Team::class)->find($targetTeamId);
        /** @var PlayerRepository $playerRepository */
        $playerRepository = $entity->getRepository(Player::class);
        $players = $playerRepository->findBy(['id' => $playerIds]);
        $team->setMoneyBalance($team->getMoneyBalance() + $amount);
        $targetTeam->setMoneyBalance($targetTeam->getMoneyBalance() - $amount);

        foreach ($players as $player) $player->setTeam($targetTeam);

        $entity->flush();

        return $this->json([
            'message' => 'Players sold successfully'
        ]);
    }

    #[Route('/team/add', name: 'team_add', methods: ['POST'])]
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
        ], context: $this->normalizer());
    }

    #[Route('/marketplace', name: 'team_edit', methods: ['GET'])]
    public function marketplace(): Response
    {
        return $this->render('base.html.twig');
    }
}