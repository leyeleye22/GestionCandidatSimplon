<?php
use Tests\TestCase;
use App\Models\Formation;
use function Pest\Laravel\getJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\postJson;
use App\Models\UtilisateurFormation;
use Illuminate\Support\Facades\Hash;
use function Pest\Laravel\deleteJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class, Hash::class);

class testCandidaterFormationTest extends TestCase
{

    public function testCandidaterFormation(){
        // Login en tant qu'utilisateur candidat
        $loginResponse = $this->postJson('api/login', [
            'email' => 'test@gmail.com',
            'password' => 'passer1234'
        ]);

        //Récupérer le token après la connexion
        $token = $loginResponse->json()['authorization']['token'];

        $candidatureCreate = UtilisateurFormation::create([
            'user_id'=>1,
            'formation_id'=>5,
            'statut'=>'refuser'
        ]);

        $response = $this->postJson('/api/candidater/create', ['candidater' => $candidatureCreate], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200);

    }

    public function testAccepterCanditature(){
        // Login en tant qu'utilisateur candidat
        $loginResponse = $this->postJson('api/login', [
            'email' => 'admin@gmail.com',
            'password' => 'passer1234'
        ]);

        //Récupérer le token après la connexion
        $token = $loginResponse->json()['authorization']['token'];

        $candidatureCreate = UtilisateurFormation::create([
            'user_id'=>1,
            'formation_id'=>5,
            'statut'=>'refuser'
        ]);

        $accepterCanditature=$candidatureCreate->id;
        
        $candidatureCreate = UtilisateurFormation::find($accepterCanditature);
        $response = $this->postJson("/api/accepterCandidature/$accepterCanditature", ['candidater' => $candidatureCreate], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200);

    }
}