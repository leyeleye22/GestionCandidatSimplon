<?php
use Tests\TestCase;
use App\Models\Formation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class, Hash::class);

class CandidatureTest extends TestCase
{
    public function testCandidatureList(){
        // Login en tant qu'utilisateur admin
        $loginResponse = $this->postJson('api/login', [
            'email' => 'admin4@gmail.com',
            'password' => 'passer1234'
        ]);

        //Récupérer le token après la connexion
        $token = $loginResponse->json()['authorization']['token'];

        $candidature = [
            'user_id' => 1,
            'formation_id' => 3,
            'statut'=>'accepter'
        ];

        
        $response = $this->getJson('/api/listeCandidatures', ['candidature' => $candidature]);

        $response->assertStatus(200);

    }
}