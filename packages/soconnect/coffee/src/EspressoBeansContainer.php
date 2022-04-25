<?php

namespace SoConnect\Coffee;

use App\Models\EspressoMachine;
use SoConnect\Coffee\BeansContainer as BeansContainerInterFace;

class EspressoBeansContainer implements BeansContainerInterFace
{
    /**
     * @var EspressoMachine
     */
    protected EspressoMachine $espressoMachine;

    /**
     * @param EspressoMachine $espressoMachine
     */
    public function __construct(EspressoMachine $espressoMachine)
    {
        $this->espressoMachine = $espressoMachine;
    }

    /**
     * @return int
     */
    public function getCapacity() : int
    {
        return $this->espressoMachine->beans_container_capacity;
    }

    /**
     * fills the container
     *
     * @param int $numSpoons
     * @throws ContainerFullException
     */
    public function addBeans(int $numSpoons): void
    {
        $level = $this->getBeans();

        if ($this->getCapacity() == $level) {
            throw ContainerFullException::alreadyFilled();
        }
        elseif ($this->getCapacity() < $level + $numSpoons) {
            throw ContainerFullException::tooFull();
        }

        //update model
        $this->espressoMachine->beans_container_level = $level + $numSpoons;
        $this->espressoMachine->save();
    }

    /**
     * Use the beans from the container
     * @param int $numSpoons
     * @return int
     */
    public function useBeans(int $numSpoons): int
    {
        //update model
        $this->espressoMachine->beans_container_level = $this->espressoMachine->beans_container_level - $numSpoons;
        //store model
        $this->espressoMachine->save();

        return $numSpoons;
    }

    /**
     * Method to get the beans level of the container
     * @return int
     */
    public function getBeans(): int
    {
        return $this->espressoMachine->beans_container_level;
    }

}
