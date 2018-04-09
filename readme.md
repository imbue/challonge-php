# Challonge PHP wrapper

Simple [Challonge](https://api.challonge.com/v1) API wrapper, in PHP.

## Installation

```
composer require imbue/challonge-php
```

## Usage

```
$challonge = new Challonge('api_key');
```

```
// Retrieve a set of tournaments created with your account.
$tournaments = $challonge->getTournaments();

// Retrieve a single tournament
$tournament = $challonge->getTournament('tournament_id');
```

```
// Create a new tounament
$tourament = $challonge->createTournament(
    [
        'tournament' => [
            'name' => 'Tournament name',
            'url' => 'imbues_new_tournament',
            ...
    ]
]);

// Update an existing tournament
$tournament = $challonge->updateTournament($tournament,
    [
        'tournament' => [
            'name' => 'New tournament name',
            ...
        ]
    ]
);

// for the full list of available parameters visit: https://api.challonge.com/v1/documents/tournaments/create
```

## List of available methods

### Tournaments
```
getTournaments();
getTournament($tournament);
createTournament($params);
updateTournament($tournament, $params);
deleteTournament($tournament);
```

### Participants
```
getParticipants($tournament);
getParticipant($tournament. $participant);
createParticipant($tournament, $params);
updateParticipant($tournament, $participant, $params);
randomizeParticipants($tournament);
```

### Matches
```
getMatches($tournament);
getMatch($tournament, $match);
updateMatch($tournament, $match, $params);
```