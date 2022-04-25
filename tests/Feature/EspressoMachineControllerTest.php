<?php

namespace Tests\Feature;

use App\Models\EspressoMachine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EspressoMachineControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create an espresso machine with no payload
     * Created with default from db
     * @return void
     */
    public function testEspressoMachineIsCreatedSuccessfullyWithDefaults()
    {
        $payload = [];

        $this->json('post',route('espresso-machine.config'), $payload)
            ->assertHeader('Content-Type','application/json')
            ->assertJsonStructure(['id','water_container_level', 'water_container_capacity','beans_container_level','beans_container_capacity'])
            ->assertStatus(200);
    }

    /**
     * Create an espresso machine with payload
     * Data is provided for all possible parameters
     * @return void
     */
    public function testEspressoMachineIsCreatedSuccessfullyWithPayload()
    {
        $payload = [
            'water_container_level'=> 0.15,
            'water_container_capacity'=> 50.10,
            'beans_container_level' => 89,
            'beans_container_capacity' => 100,
        ];

        $this->json('post',route('espresso-machine.config'), $payload)
            ->assertHeader('Content-Type','application/json')
            ->assertJsonStructure(['id','water_container_level', 'water_container_capacity','beans_container_level','beans_container_capacity'])
            ->assertStatus(200);
    }

    /**
     * Try to create an espresso machine with level higher then capacity
     * @return void
     */
    public function testEspressoMachineCreateWithHigherLevelThenCapacity()
    {
        $payload = [
            'water_container_level'=> 6,
            'water_container_capacity'=> 5,
            'beans_container_level' => 150,
            'beans_container_capacity' => 100,
        ];

        $this->json('post',route('espresso-machine.config'), $payload,['Accept' => 'application/json'])
            ->assertHeader('Content-Type','application/json')
            ->assertJsonValidationErrors(['beans_container_capacity','water_container_capacity'],'error')
            ->assertStatus(400);
    }

    /**
     * Create an espresso machine with no allowed values
     * @return void
     */
    public function testEspressoMachineCreateWithInvalidData(){

        $payload = [
            'water_container_level'=> -50,
            'water_container_capacity'=>-50,
            'beans_container_level' => -89.15,
            'beans_container_capacity' => -98,
        ];

        $this->json('post',route('espresso-machine.config'), $payload)
            ->assertHeader('Content-Type','application/json')
            ->assertJsonValidationErrors(['water_container_level','water_container_capacity','beans_container_level','beans_container_capacity'],'error')
            ->assertStatus(400);
    }

    /**
     * Successfully fill water container an espresso machine
     * @return void
     */
    public function testEspressoMachineFillWithWaterSuccessfully(){

        $espressomachine = EspressoMachine::factory()->create();
        $id = $espressomachine->id;
        $capacity = $espressomachine->water_container_capacity;
        $level = $espressomachine->water_container_level;
        $payload = [
            'water'=> ($capacity - $level)
        ];

        $this->json('post',route('espresso-machine.add-water',['modelEspressoMachine' => $id]), $payload)
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(200);
    }

    /**
     * Fill a water container with more water then water container can handle
     * @return void
     */
    public function testFillWaterContainerTooMuch(){

        $espressomachine = EspressoMachine::factory()->create();
        $id = $espressomachine->id;
        $capacity = $espressomachine->water_container_capacity;
        $level = $espressomachine->water_container_level;
        $payload = [
            'water'=> ($capacity + 1 )
        ];

        $this->json('post',route('espresso-machine.add-water',['modelEspressoMachine' => $id]), $payload)
            ->assertHeader('Content-Type','application/json')
            ->assertJson([
                'error' => 'Added too much. Container is too full.',
            ])
            ->assertStatus(400);
    }

    /**
     * Fill a full water container
     * @return void
     */
    public function testFillFullWaterContainer(){

        $espressomachine = EspressoMachine::factory()->create(['water_container_capacity' => 10 , 'water_container_level' => 10]);
        $id = $espressomachine->id;

        $payload = [
            'water'=> 1
        ];

        $this->json('post',route('espresso-machine.add-water',['modelEspressoMachine' => $id]), $payload)
            ->assertHeader('Content-Type','application/json')
            ->assertJson([
                'error' => 'Container is already filled',
            ])
            ->assertStatus(400);
    }

    /**
     * Successfull fill beans container
     * @return void
     */
    public function testFillBeansContainerSuccessfully(){

        $espressomachine = EspressoMachine::factory()->create(['beans_container_capacity' => 10 , 'beans_container_level' => 0]);
        $id = $espressomachine->id;

        $payload = [
            'beans'=> 2
        ];

        $this->json('post',route('espresso-machine.add-beans',['modelEspressoMachine' => $id]), $payload)
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(200);

    }

    /**
     * Fill a full beans container
     * @return void
     */
    public function testFillFullBeansContainer(){

        $espressomachine = EspressoMachine::factory()->create(['beans_container_capacity' => 10 , 'beans_container_level' => 10]);
        $id = $espressomachine->id;

        $payload = [
            'beans'=> 1
        ];

        $this->json('post',route('espresso-machine.add-beans',['modelEspressoMachine' => $id]), $payload)
            ->assertJson([
                'error' => 'Container is already filled',
            ])
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(400);
    }

    /**
     * Fill a beans container with too much beans.
     * This give an error
     * @return void
     */
    public function testFillBeansContainerTooMuch(){

        $espressomachine = EspressoMachine::factory()->create(['beans_container_capacity' => 10 , 'beans_container_level' => 9]);
        $id = $espressomachine->id;

        $payload = [
            'beans'=> 2
        ];

        $this->json('post',route('espresso-machine.add-beans',['modelEspressoMachine' => $id]), $payload)
            ->assertJson([
                'error' => 'Added too much. Container is too full.',
            ])
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(400);
    }

    /**
     * Check overview of espresso machines in case no machines exist
     * @return void
     */
    public function testOverviewEspressoMachinesNoExist(){

        $this->json('get', route('espresso-machine.index'))
            ->assertJsonCount(0, null)
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(200);

    }

    /**
     * Check over of espresso machine in case machines exist
     * @return void
     */
    public function testOverviewEspressoMachinesSeveralExist(){

        EspressoMachine::factory(5)->create();

        $this->json('get',route('espresso-machine.index'))
            ->assertJsonCount(5, null)
            ->assertJsonStructure([
                '*' => ['id','beans_container_capacity','beans_container_level','water_container_capacity','water_container_level']
            ])
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(200);
    }

    /**
     * Get status of an existing espresso machine
     * @return void
     */
    public function testGetStatusEspressoMachineSuccessfully(){

        $espressomachine = EspressoMachine::factory()->create();

        $this->json('get',route('espresso-machine.status',['modelEspressoMachine' => $espressomachine->id]))
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(200);
    }

    /**
     * Get status of a non-existing espresso mochine
     * @return void
     */
    public function testGetStatusEspressoMachineModelNotExist(){

        $this->json('get',route('espresso-machine.status',['modelEspressoMachine' => 1]))
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(404);
    }

    /**
     * Get one espresso from a machine with a filled containers
     * @return void
     */
    public function testGetOneEspressoSuccessfully(){
        $data = [
            'water_container_level'=> 10,
            'water_container_capacity'=> 10,
            'beans_container_level' => 10,
            'beans_container_capacity' => 10,
        ];

        $espressomachine = EspressoMachine::factory()->create($data);
        $this->json('get',route('espresso-machine.make-one',['modelEspressoMachine' => $espressomachine->id]))
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(200);
    }

    /**
     * Get one espresso from a machine with water container empty
     * @return void
     */
    public function testGetOneEspressoUnsuccessfullyNoWater(){
        $data = [
            'water_container_level'=> 0,
            'water_container_capacity'=> 10,
            'beans_container_level' => 10,
            'beans_container_capacity' => 10,
        ];
        $espressomachine = EspressoMachine::factory()->create($data);

        $this->json('get',route('espresso-machine.make-one',['modelEspressoMachine' => $espressomachine->id]))
            ->assertJson([
                'error' => 'No Water. Please fill the machine',
            ])
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(400);
    }

    /**
     * Get one espresso from a machine with beans container empty
     * @return void
     */
    public function testGetOneEspressoUnsuccessfullyNoBeans(){
        $data = [
            'water_container_level'=> 10,
            'water_container_capacity'=> 10,
            'beans_container_level' => 0,
            'beans_container_capacity' => 10,
        ];
        $espressomachine = EspressoMachine::factory()->create($data);
        $this->json('get',route('espresso-machine.make-one',['modelEspressoMachine' => $espressomachine->id]))
            ->assertJson([
                'error' => 'No Beans. Please fill the machine',
            ])
            ->assertHeader('Content-Type','application/json')
            ->assertStatus(400);
    }

}
