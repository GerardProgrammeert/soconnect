<?php

namespace SoConnect\Coffee;

use App\Models\EspressoMachine;
use SoConnect\Coffee\WaterContainer as WaterContainerInterface;

class EspressoWaterContainer implements WaterContainerInterface
{
    /**
     * @var EspressoMachine
     */
    protected $espressoMachine;

    /**
     * @param EspressoMachine $espressoMachine
     */
    public function __construct(EspressoMachine $espressoMachine)
    {
        $this->espressoMachine = $espressoMachine;
    }

    /**
     * Get capacity of water container
     *
     * @return float
     */
    public function getCapacity(): float
    {
        return $this->espressoMachine->water_container_capacity;
    }

    /**
     *
     *
     * @return float
     */
    public function maxAdd(): float
    {
        return $this->getCapacity() - $this->getWater();
    }

    /**
     * Method to add water to the container
     * @param float $litres
     * @throws ContainerFullException
     */
    public function addWater(float $litres): void
    {
        $level = $this->getWater();
        if ($this->getCapacity() < $level + $litres) {
            throw ContainerFullException::tooFull();
        } elseif ($this->getCapacity() == $level) {
            throw ContainerFullException::alreadyFilled();
        }

        //update model
        $this->espressoMachine->water_container_level = $level + $litres;
        $this->espressoMachine->save();

    }

    /**
     * Use $litres from the container
     *
     * @param float $litres float number of litres of water
     *
     * @return float number of litres used
     */
    public function useWater(float $litres): float
    {
        //update model
        $this->espressoMachine->water_container_level = $this->espressoMachine->water_container_level - $litres;
        //store model
        $this->espressoMachine->save();

        return $litres;
    }

    /**
     * Method to get the water level of the container
     * @return float
     */
    public function getWater(): float
    {
        return $this->espressoMachine->water_container_level;
    }

}
