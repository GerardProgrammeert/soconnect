<?php

namespace Tests\Feature;

use App\Models\EspressoMachine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class EspressoMachineControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     * @return void
     */
    public function testEspressoMachineIsCreatedSuccessfullyWithDefaults()
    {
        $payload = [];

        $this->json('post','/api/espresso-machine/config', $payload)
            ->assertHeader('Content-Type','application/json')
            ->assertJsonStructure(['id','water_container_level', 'water_container_capacity','beans_container_level','beans_container_capacity'])
            ->assertStatus(200);
    }
    public function testEspressoMachineIsCreatedSuccessfullyWithPayload()
    {
        $payload = [
            'water_container_level'=> 0.15,
            'water_container_capacity'=> 50.10,
            'beans_container_level' => 89,
            'beans_container_capacity' => 100,
        ];

        $this->json('post','/api/espresso-machine/config', $payload)
            ->assertHeader('Content-Type','application/json')
            ->assertJsonStructure(['id','water_container_level', 'water_container_capacity','beans_container_level','beans_container_capacity'])
            ->assertStatus(200);
    }

    public function testEspressoMachineCreateWithHigherLevelThenCapacity()
    {
        $payload = [
            'water_container_level'=> 6,
            'water_container_capacity'=> 5,
            'beans_container_level' => 150,
            'beans_container_capacity' => 100,
        ];

        $this->json('post','/api/espresso-machine/config', $payload,['Accept' => 'application/json'])
            ->assertHeader('Content-Type','application/json')
            ->assertJsonValidationErrors(['beans_container_capacity','water_container_capacity'],'error')
            ->assertStatus(400);
    }

    public function testEspressoMachineCreateWithInvalidData(){

        $payload = [
            'water_container_level'=> -50,
            'water_container_capacity'=>-50,
            'beans_container_level' => -89.15,
            'beans_container_capacity' => -98,
        ];

        $this->json('post','/api/espresso-machine/config', $payload)
            ->assertHeader('Content-Type','application/json')
            ->assertJsonValidationErrors(['water_container_level','water_container_capacity','beans_container_level','beans_container_capacity'],'error')
            ->assertStatus(400);
    }

    public function testEspressoMachineFillWithWaterSuccesfully(){

        $espressomachine = EspressoMachine::factory()->create();
        $id = $espressomachine->id;
        $capacity = $espressomachine->water_container_capacity;
        $level = $espressomachine->water_container_level;
        $payload = [
            'water'=> ($capacity - $level)
        ];

        $this->json('post','/api/espresso-machine/' . $id .'/add-water', $payload)
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(200);
    }

    public function testFillWaterContainerTooMuch(){

        $espressomachine = EspressoMachine::factory()->create();
        $id = $espressomachine->id;
        $capacity = $espressomachine->water_container_capacity;
        $level = $espressomachine->water_container_level;
        $payload = [
            'water'=> ($capacity + 1 )
        ];

        $this->json('post','/api/espresso-machine/' . $id .'/add-water', $payload)
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(400);
    }

    public function testFillFullWaterContainer(){

        $espressomachine = EspressoMachine::factory()->create(['water_container_capacity' => 10 , 'water_container_level' => 10]);
        $id = $espressomachine->id;

        $payload = [
            'water'=> 1
        ];

        $this->json('post','/api/espresso-machine/' . $id .'/add-water', $payload)
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(400);
    }
    public function testFillBeansContainerSuccesfully(){

        $espressomachine = EspressoMachine::factory()->create(['beans_container_capacity' => 10 , 'beans_container_level' => 0]);
        $id = $espressomachine->id;

        $payload = [
            'beans'=> 2
        ];

        $this->json('post','/api/espresso-machine/' . $id .'/add-beans', $payload)
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(200);

    }
    public function testFillFullBeansContainer(){

        $espressomachine = EspressoMachine::factory()->create(['beans_container_capacity' => 10 , 'beans_container_level' => 10]);
        $id = $espressomachine->id;

        $payload = [
            'beans'=> 1
        ];

        $this->json('post','/api/espresso-machine/' . $id .'/add-beans', $payload)
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(400);
    }

    public function testFillBeansContainerTooMuch(){

        $espressomachine = EspressoMachine::factory()->create(['beans_container_capacity' => 10 , 'beans_container_level' => 9]);
        $id = $espressomachine->id;

        $payload = [
            'beans'=> 2
        ];

        $this->json('post','/api/espresso-machine/' . $id .'/add-beans', $payload)
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(400);
    }

    public function testOverviewEspressoMachinesNoExist(){

        $this->json('get','/api/espresso-machine/')
            ->assertJsonCount(0, null)
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(200);

    }

    public function testOverviewEspressoMachinesSeveralExist(){

        EspressoMachine::factory(5)->create();
        $this->json('get','/api/espresso-machine/')
            ->assertJsonCount(5, null)
            ->assertJsonStructure([
                '*' => ['id','beans_container_capacity','beans_container_level','water_container_capacity','water_container_level']
            ])
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(200);
    }

    public function testGetStatusEspressoMachineSuccesfully(){

        $espressomachine = EspressoMachine::factory()->create();
        $this->json('get','/api/espresso-machine/' . $espressomachine->id . '/status')
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(200);
    }

    public function testGetStatusEspressoMachineModelNotExist(){

        $this->json('get','/api/espresso-machine/1/status')
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(404);
    }

    public function testGetOneEspressoSuccesfully(){
        $data = [
            'water_container_level'=> 10,
            'water_container_capacity'=> 10,
            'beans_container_level' => 10,
            'beans_container_capacity' => 10,
        ];
        $espressomachine = EspressoMachine::factory()->create($data);
        $this->json('get','/api/espresso-machine/'. $espressomachine->id . '/one')
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(200);
    }

    public function testGetOneEspressoUnsuccesfullyNoWater(){
        $data = [
            'water_container_level'=> 0,
            'water_container_capacity'=> 10,
            'beans_container_level' => 10,
            'beans_container_capacity' => 10,
        ];
        $espressomachine = EspressoMachine::factory()->create($data);
        $this->json('get','/api/espresso-machine/'. $espressomachine->id . '/one')
            ->assertJson([
                'error' => 'No Water. Please fill the machine',
            ])
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(400);
    }

    public function testGetOneEspressoUnsuccesfullyNoBeans(){
        $data = [
            'water_container_level'=> 10,
            'water_container_capacity'=> 10,
            'beans_container_level' => 0,
            'beans_container_capacity' => 10,
        ];
        $espressomachine = EspressoMachine::factory()->create($data);
        $this->json('get','/api/espresso-machine/'. $espressomachine->id . '/one')
            ->assertJson([
                'error' => 'No Beans. Please fill the machine',
            ])
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(400);
    }

//https://www.twilio.com/blog/unit-testing-laravel-api-phpunit
//https://github.com/auth0-blog/laravel-testing/blob/main/tests/TestCase.php
//https://auth0.com/blog/testing-laravel-apis-with-phpunit/
//https://www.youtube.com/watch?v=6jQixGjQIB0
}
