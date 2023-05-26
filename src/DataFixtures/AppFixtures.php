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
        $team1 = new Team();
        $team1->setName('Sample Team');
        $team1->setCountry('Sample Country');
        $team1->setMoneyBalance(1000000);
        // Add more fixtures as needed...

        $player1 = new Player();
        $player1->setName('Sample Player');
        $player1->setSurName('Sample SurName');
        $player1->setTeam($team1);

        $manager->persist($team1);
        $manager->persist($player1);


        $team2 = new Team();
        $team2->setName('Sample Team 2');
        $team2->setCountry('Sample Country 2');
        $team2->setMoneyBalance(1000000);

        $player2 = new Player();
        $player2->setName('Sample Player 2');
        $player2->setSurName('Sample SurName 2');
        $player2->setTeam($team2);

        $manager->persist($team2);
        $manager->persist($player2);

        $manager->flush();
    }
}
