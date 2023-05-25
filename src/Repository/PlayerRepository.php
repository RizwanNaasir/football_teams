<?php

namespace App\Repository;

use App\Entity\Player;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Player>
 *
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function save(Player $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Player $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Player[] Returns an array of Player objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Player
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function createOrGetPlayer(array $playerData, Team $team): ?Player
    {
        $player = $this->findOneBy(['name' => $playerData['name'], 'surname' => $playerData['surName']]);

        if (!$player) {
            $player = new Player();
            $player->setTeam($team);
            $player->setName($playerData['name']);
            $player->setSurName($playerData['surName']);
            $this->save($player, true);
        }

        return $player;
    }

    public function getPlayers(mixed $search, $teamId = null): Query
    {
        $query = $this->createQueryBuilder('p')
            ->leftJoin('p.team', 't')
            ->addSelect('t')
            ->orderBy('p.id', 'desc');
        if (isset($teamId)) {
            $query->where('t.id != :teamId')
                ->setParameter('teamId', $teamId);
        }
        if (isset($search)) {
            $query->where('p.name LIKE :search')
                ->orWhere('p.surname LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        return $query->getQuery();
    }
}
