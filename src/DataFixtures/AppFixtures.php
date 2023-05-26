<?php

namespace App\DataFixtures;

use App\Entity\Player;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create a team
        $team = new Team();
        $team->setName('Sample Team');
        $team->setCountry('Sample Country');
        $team->setMoneyBalance(1000000);
        // Add more fixtures as needed...

        $player = new Player();
        $player->setName('Sample Player');
        $player->setSurName('Sample SurName');
        $player->setTeam($team);

        $manager->persist($team);
        $manager->persist($player);
        $manager->flush();
    }
}
