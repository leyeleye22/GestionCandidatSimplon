<?php
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

uses(RefreshDatabase::class, Hash::class);

class AuthenticationTest extends TestCase
{
    public function testAdminCanViewCandidatsList()
    {
        // Créer un utilisateur avec le rôle admin
        $adminUser = User::create([
            'nom' => 'WADE',
            'prenom' => 'Mariam',
            'date_naiss' => '2002-09-18',
            'email' => 'admin8@gmail.com',
            'password' => Hash::make('passer1234'),
            'role' => 'admin',
        ]);

        // Créer un utilisateur avec le rôle candidat
        $candidatCreate = User::create([
            'nom' => 'TEST',
            'prenom' => 'TEST',
            'date_naiss' => '2002-09-18',
            'email' => 'test1@gmail.com',
            'password' => Hash::make('passer1234'),
            'role' => 'candidat',
        ]);
        // Login en tant qu'utilisateur admin
        $loginResponse = $this->postJson('api/login', [
            'email' => $adminUser->email,
            'password' => 'passer1234'// Utilisez le mot de passe en clair
        ]);

         // Login en tant qu'utilisateur candidat
         $loginResponseCandidat = $this->postJson('api/login', [
            'email' => $candidatCreate->email,
            'password' => 'passer1234'// Utilisez le mot de passe en clair
        ]);

        $loginResponse->assertStatus(200);
        $loginResponseCandidat->assertStatus(200);

        // Récupérer le token après la connexion
        //$token = $loginResponse->json()['authorisation']['token'];

    }


}