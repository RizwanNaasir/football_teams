# Football Team Management App

This is a football team management application built with Symfony PHP Framework and Vue.js. The application allows you
to manage football teams, their players, and perform buying/selling transactions between teams.

## Requirements

- PHP 8.1 or higher
- Composer
- Node.js 18.0 or higher

Installation
Clone the repository:

```bash 
git clone https://github.com/RizwanNaasir/football_teams_inter_task.git
```

## Install backend dependencies:

```bash
cd football_teams_inter_task
composer install
```

## Configure the database connection:

Copy the .env file and configure the database connection settings.

## Create the database:

```bash
php bin/console doctrine:database:create
```

Run the database migrations to create the necessary tables:

```php
php bin/console doctrine:schema:update --force
```

Optionally load the fixtures to populate the database with sample data:

```php
php bin/console doctrine:fixtures:load --no-interaction
```

## Install frontend dependencies:

```bash
npm install && npm run dev
```

Run the Application:

Start the Symfony development server:
```bash
symfony server:start
```
Access the Application:

Open your web browser and visit http://localhost:8000 to access the football team management application.

## Usage
The application provides the following features:

Team Listing: View a paginated list of all teams, displaying their name, country, and money balance.
Player Listing: View a list of all players, displaying their name, surname, and the team they belong to.
Add New Team and Players: Add a new team along with its players.
Buy/Sell Players: Perform buying/selling transactions between teams by specifying the player, the buying/selling team, and the transaction amount.
Use the provided UI components to navigate through the application and interact with the teams and players.

## Testing
To run the unit tests for the application, use the following command:

```bash
php bin/phpunit
```
Contributing
Contributions are welcome! If you find any issues or have suggestions for improvement, please open an issue or submit a pull request.

License
This project is licensed under the MIT License.

Credits
This application was created by [Rizwan Nasir].