<?php
use Tests\TestCase;
use App\Models\User;
use App\Models\Formation;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class, Hash::class);

class CandidatTest extends TestCase
{
    public function testCandidatList(){
        // Login en tant qu'utilisateur admin
        $loginResponse = $this->postJson('api/login', [
            'email' => 'admin4@gmail.com',
            'password' => 'passer1234'
        ]);

        //Récupérer le token après la connexion
        $token = $loginResponse->json()['authorization']['token'];

        $candidat = [
            'nom'=>'test',
            'prenom'=>'test',
            'date_naiss'=>'2000-05-29',
            'email'=>'Hello@gmail.com',
            'password'=>'passer1234',
            'role'=>'candidat',
        ];
        
        $response = $this->getJson('/api/listeCandidats', ['candidat' => $candidat]);

        $response->assertStatus(200);

    }

    
    public function testPrenomEstUneChaineDeCaracteres()
    {
        $candidatCreate = User::create([
            'nom' => 'TEST',
            'prenom' => 1,
            'date_naiss' => '2002-09-18',
            'email' => 'coucou1@gmail.com',
            'password' => Hash::make('passer1234'),
            'role' => 'candidat',
        ]);

        $this->assertIsString($candidatCreate->prenom);
    }
}