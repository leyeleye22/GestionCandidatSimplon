<?php
use Tests\TestCase;
use App\Models\Formation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\putJson;

uses(RefreshDatabase::class, Hash::class);

class FormationTest extends TestCase
{
    public function testFormationList(){
        $formation = [
            'nom_formation' => 'Formation de test',
            'description' => 'Description de la formation de test',
            'duree'=>6
        ];


        $response = $this->getJson('/api/listeFormations', ['formation' => $formation]);
        $response->assertStatus(200);

    }

    public function testAjoutFormation(){
        // Login en tant qu'utilisateur admin
        $loginResponse = $this->postJson('api/login', [
            'email' => 'admin4@gmail.com',
            'password' => 'passer1234'
        ]);

        //Récupérer le token après la connexion
        $token = $loginResponse->json()['authorization']['token'];

        $formationCreate = Formation::create([
            'nom_formation' => 'formation test',
            'description' => 'description formation',
            'duree'=>6
        ]);

        $response = $this->postJson('/api/formation/create', ['formation' => $formationCreate], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200);

    }

    

   


}