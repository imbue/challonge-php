# Challonge PHP wrapper

Simple [Challonge](https://api.challonge.com/v1) API wrapper, in PHP.

## Installation

```bash
composer require imbue/challonge-php
```

## Usage

```php
$challonge = new Challonge('api_key');
```

```php
// Retrieve a set of tournaments created with your account.
$tournaments = $challonge->getTournaments();

// Retrieve a single tournament.
$tournament = $challonge->getTournament('tournament_id');
```

```php
// Create a new tounament.
$tourament = $challonge->createTournament([
    'tournament' => [
        'name' => 'Tournament name',
        'url' => 'imbues_new_tournament',
        ...
    ]
]);

// for the full list of available params visit: https://api.challonge.com/v1/documents/tournaments/create
```