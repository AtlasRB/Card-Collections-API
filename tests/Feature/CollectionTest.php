<?php

namespace Tests\Feature;

use App\Models\Card;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CollectionTest extends TestCase
{
    use DatabaseMigrations;

    //Tests for get
    //Test for getting all cards
    public function test_getAllCards(): void
    {
        Card::factory()->count(4)->create();

        $response = $this->getJson('/api/card');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->whereType('message', 'string')
                    ->has('data', 4, function (AssertableJson $json) {
                        $json->hasAll(['id', 'name', 'number', 'type', 'collected'])
                            ->whereAllType([
                                'id' => 'integer',
                                'name' => 'string',
                                'number' => 'integer',
                                'type' => 'string',
                                'collected' => 'integer'
                            ]);
                    });
            });
    }
    //Test for getting a single card
    public function test_getSingleCard(): void
    {
        Card::factory()->create();

        $response = $this->getJson('/api/card/1');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->whereType('message', 'string')
                    ->has('data', function (AssertableJson $json) {
                        $json->hasAll(['id', 'name', 'number', 'type', 'collected'])
                            ->whereAllType([
                                'id' => 'integer',
                                'name' => 'string',
                                'number' => 'integer',
                                'type' => 'string',
                                'collected' => 'integer'
                            ]);
                    });
            });
    }

    //Tests for put
    //Test if data is invalid
    public function test_updateCard_invalidData(): void
    {
        Card::factory()->create();
        $response = $this->putJson('/api/card/1', []);
        $response->assertInvalid(['name']);
    }
    //Test if update is successful
    public function test_updateCard_success(): void
    {
        Card::factory()->create();
        $response = $this->putJson('/api/card/1', [
            'name' => 'testing',
            'number' => 2,
            'type' => 'fire',
            'collected' => 1
        ]);

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->whereType('message', 'string');
            });

        $this->assertDatabaseHas('cards', [
            'name' => 'testing',
            'number' => 2,
            'type' => 'fire',
            'collected' => 1
        ]);
    }
    //Test if update id is not found or doesn't exist
    public function test_updateCard_notFound(): void
    {
        $response = $this->putJson('/api/card/1', [
            'name' => 'testing',
            'number' => 2,
            'type' => 'fire',
            'collected' => 1
        ]);

        $response->assertStatus(404)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message'])
                    ->whereType('message', 'string');
            });
    }

    //Tests for post
    //Test if data is invalid
    public function test_createCard_invalidData(): void
    {
        $response = $this->postJson('/api/card', []);
        $response->assertInvalid(['name', 'number', 'type', 'collected']);
    }
    //Test if creation was a success
    public function test_createCertification_success(): void
    {
        $response = $this->postJson('/api/card', [
            'name' => 'testing',
            'number' => 7,
            'type' => 'water',
            'collected' => 1
        ]);

        $response->assertStatus(201)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->whereType('message', 'string');
            });

        $this->assertDatabaseHas('cards', [
            'name' => 'testing',
            'number' => 7,
            'type' => 'water',
            'collected' => 1
        ]);
    }

    //Tests for delete
    //Test if delete worked
    public function test_deleteContract_success(): void
    {
        $contract = Card::factory()->create();

        $response = $this->deleteJson('api/card/1');

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message'])
                    ->whereType('message', 'string');
            });

        $this->assertDatabaseMissing('cards', [
            'name' => $contract->name
        ]);
    }
}
