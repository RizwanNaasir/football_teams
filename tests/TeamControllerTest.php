<?php

namespace App\Tests;


use App\Entity\Player;
use App\Entity\Team;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TeamControllerTest extends WebTestCase
{
    public function testHomePage()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
    }

    public function testMarketPlace()
    {
        $client = static::createClient();
        $client->request('GET', '/marketplace');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
    }

    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/team');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        /**
         * @response {
         * it will return json like this
         * data: [],
         * pagination: {
         * total: 0,
         * pages: 0,
         * page: 0,
         * perPage: 0
         * }
         * }
         * */
        $responseData = $client->getResponse()->getContent();
        $this->assertJson($responseData);
        $responseData = json_decode($responseData, true);
        $this->assertArrayHasKey('pagination', $responseData);
        $this->assertArrayHasKey('total', $responseData['pagination']);
        $this->assertArrayHasKey('pages', $responseData['pagination']);
        $this->assertArrayHasKey('page', $responseData['pagination']);
        $this->assertArrayHasKey('perPage', $responseData['pagination']);
    }

    /**
     * @throws Exception
     */
    public function testShow()
    {
        $client = static::createClient();
        $teamId = $this->createTeam()->getId();
        $client->request('GET', '/team/' . $teamId);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertEquals($teamId, $responseData['data']['id']);
    }

    /**
     * @throws Exception
     */
    private function createTeam(): Team
    {
        $container = static::getContainer();
        $entityManager = $container->get('doctrine')->getManager();
        // Create a new team entity
        $team = new Team();
        // Set the properties of the team entity
        // For example:
        $team->setName($this->generateFakeText());
        $team->setCountry($this->generateFakeText());
        $team->setMoneyBalance(1000000);

        // Persist and flush the team entity to the database
        $entityManager->persist($team);
        $entityManager->flush();

        return $team;
    }

    function generateFakeText($length = 100): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $text = '';

        $characterCount = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $randomIndex = rand(0, $characterCount - 1);
            $text .= $characters[$randomIndex];
        }

        return $text;
    }

    /**
     * @throws Exception
     */
    public function testPlayers()
    {
        $client = static::createClient();
        $teamId = $this->createTeam()->getId();
        $client->request('GET', '/players', ['query' => ['team_id' => $teamId]]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $responseData);
    }

    /**
     * @throws Exception
     */
    public function testBuyPlayer()
    {
        $client = static::createClient();

        // Create a team in the database
        $team = $this->createTeam();

        // Create a player in the database
        $player = $this->createPlayer();

        // Set the necessary request data
        $requestData = [
            'playerId' => $player->getId(),
            'amount' => 100000,
        ];

        // Send a POST request to buy the player
        $client->request('POST', '/team/' . $team->getId() . '/buy-player', [], [], [], json_encode($requestData));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Player bought successfully', $responseData['message']);
    }

    /**
     * @throws Exception
     */
    private function createPlayer(?Team $team = null): Player
    {
        $container = static::getContainer();
        $entityManager = $container->get('doctrine')->getManager();
        // Create a new player entity
        $player = new Player();
        // Set the properties of the player entity
        // For example:
        //generate random name for player
        $player->setName($this->generateFakeText(10));
        $player->setSurName($this->generateFakeText(10));
        $player->setTeam($team ?? $this->createTeam());

        // Persist and flush the player entity to the database
        $entityManager->persist($player);
        $entityManager->flush();

        return $player;
    }

    /**
     * @throws Exception
     */
    public function testSellPlayers()
    {
        $client = static::createClient();

        // Create two teams in the database
        $team1 = $this->createTeam();
        $team2 = $this->createTeam();

        // Create two players in the database, assign them to team1
        $player1 = $this->createPlayer($team1);
        $player2 = $this->createPlayer($team1);

        // Set the necessary request data
        $requestData = [
            'playerIds' => [$player1->getId(), $player2->getId()],
            'amount' => 50000,
            'targetTeamId' => $team2->getId(),
        ];

        // Send a POST request to sell the players
        $client->request('POST', '/team/' . $team1->getId() . '/sell-players', [], [], [], json_encode($requestData));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Players sold successfully', $responseData['message']);
    }

    public function testAdd()
    {
        $client = static::createClient();

        // Set the necessary request data
        $requestData = [
            'name' => 'New Team',
            'country' => 'Country',
            'moneyBalance' => 1000000,
            'players' => [
                [
                    'name' => 'Player 1',
                    'surName' => 'Surname 1',
                ],
                [
                    'name' => 'Player 2',
                    'surName' => 'Surname 2',
                ],
            ],
        ];

        // Send a POST request to add the team
        $client->request('POST', '/team/add', [], [], [], json_encode($requestData));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Team added successfully', $responseData['message']);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertEquals($requestData['name'], $responseData['data']['name']);
        $this->assertEquals($requestData['country'], $responseData['data']['country']);
        $this->assertEquals($requestData['moneyBalance'], $responseData['data']['moneyBalance']);
        $this->assertArrayHasKey('players', $responseData['data']);
        $this->assertCount(2, $responseData['data']['players']);
    }

}
