<?php

use App\Models\Candidat;
use function Pest\Laravel\postJson;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Utilisez la fonction de configuration pour définir les façades
uses(RefreshDatabase::class);
test('testunitaire', function () {
    expect(true)->toBeTrue();
    $user = [
        'nom' => 'Fall Rew',
        'prenom' => 'Pape Hamady',
        'email' => 'adamarahma@gmail.com',
        'telephone' => '56789',
        'dateNaissance' => '2000-12-12',
        'adresse' => 'Pikine',
        'formationDesiree' => 'Dev',
        'accepted' => 'isAccept',
        'role' => 'candidat',
        'password' => Hash::make('password'),
    ];
    postJson('api/register', [
        'nom' => $user['nom'],
        'prenom' => $user['prenom'],
        'email' => $user['email'],
        'telephone' => $user['telephone'],
        'dateNaissance' => $user['dateNaissance'],
        'adresse' => $user['adresse'],
        'formationDesiree' => $user['formationDesiree'],
        'accepted' => $user['accepted'],
        'role' => $user['role'],
        'password' => $user['password'],
    ])->assertStatus(200)->assertJsonStructure(['message']);
});
