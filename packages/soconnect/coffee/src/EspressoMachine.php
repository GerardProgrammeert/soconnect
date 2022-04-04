<?php

namespace SoConnect\Coffee;
/*
use SoConnect\Coffee\EspressoMachineInterface;
use SoConnect\Coffee\WaterContainer;
use SoConnect\Coffee\BeansContainer;
use SoConnect\Coffee\ContainerFullException;
use SoConnect\Coffee\NoBeansException;
use SoConnect\Coffee\NoWaterException;
*/

class EspressoMachine implements EspressoMachineInterface
{

    /**
     * sets litres of water use per espresso
     * @var float
     */
    protected float $waterPerEspresso = 0.05;

    /**
     * sets spoons of beans used per espresso
     * @var float
     */
    protected int $beansPerEspresso = 1;

    /**
     * implementation of waterContainer
     * @var \SoConnect\Coffee\WaterContainer
     */
    protected WaterContainer $waterContainer;

    /**
     * * implementation of beansContainer
     * @var \SoConnect\Coffee\BeanContainer
     */
    protected BeansContainer $beansContainer;


    /**
     * @param \SoConnect\Coffee\WaterContainer $waterContainer
     * @param \SoConnect\Coffee\BeansContainer $beansContainer
     */
    public function __construct(WaterContainer $waterContainer, BeansContainer $beansContainer)
    {
        //set the ingredients containers
        $this->waterContainer = $waterContainer;
        $this->beansContainer = $beansContainer;
    }

    /**
     * Check whether there are enough beans to make espresso
     *
     * @param int $needed
     * @return bool
     */
    public function hasBeans(int $needed): bool
    {
        $level = $this->beansContainer->getBeans();

        if ($level < $needed) {
            return false;
        }

        return true;
    }


    /**
     * Check whether there is enough water to make espresso
     *
     * @param float $needed
     * @return bool
     *
     */
    public function hasWater(float $needed): bool
    {
        $waterLevel = $this->waterContainer->getWater();

        if ($waterLevel < $needed) {
            return false;
        }

        return true;
    }


    /**
     * Runs the process for making Espresso
     *
     * @return float amount of litres of coffee made
     *
     * @throws NoBeansException
     * @throws NoWaterException
     */
    public function makeEspresso(): float
    {
        //check water level
        if (!$this->hasWater($this->waterPerEspresso)) {
            throw new NoWaterException("No Water. Please fill the machine");
        }

        //check beans level
        if (!$this->hasBeans($this->beansPerEspresso)) {
            throw new NoBeansException("No Beans. Please fill the machine");
        }

        //update both containers
        $this->waterContainer->useWater($this->waterPerEspresso);
        $this->beansContainer->useBeans($this->beansPerEspresso);

        return 0.05;
    }


    /**
     * Runs the process for making Double Espresso
     *
     * @return float of litres of coffee made
     *
     * @throws NoBeansException
     * @throws NoWaterException
     */
    public function makeDoubleEspresso(): float
    {
        //check water level
        if (!$this->hasWater(2 * $this->waterPerEspresso)) {
            throw NoWaterException::waterRequired();
        }

        //check beans level
        if (!$this->hasBeans(2 * $this->beansPerEspresso)) {
            throw NoBeansException::beansRequired();
        }

        //update both containers
        $this->waterContainer->useWater(2 * $this->waterPerEspresso);
        $this->beansContainer->useBeans(2 * $this->beansPerEspresso);

        return 0.10;
    }

    /**
     * This method controls what is displayed on the screen of the machine
     * Returns ONE of the following human readable statuses in the following preference order:
     *
     * - Add beans and water
     * - Add beans
     * - Add water
     * - {int} Espressos left
     *
     * @return string
     */
    public function getStatus(): string
    {
        //set minimal required ingredients before signalling
        $minNeededWater = 2 * $this->waterPerEspresso;
        $minNeededBeans = 2 * $this->beansPerEspresso;

        if (!$this->hasBeans($minNeededBeans) && !$this->hasWater($minNeededWater)) {
            return "Add beans and water";
        } elseif (!$this->hasBeans($minNeededBeans)) {
            return "Add beans";
        } elseif (!$this->hasWater($minNeededWater)) {
            return "Add water";
        } else {
            return $this->calEspressosLeft() . " Espresso ('s) left";
        }

    }

    /**
     * Calculates how many espresso's can be made with current levels
     *
     * @return int
     */
    public function calEspressosLeft(): int
    {
        //get curent levels
        $beansLevel = $this->beansContainer->getBeans();
        $waterLevel = $this->waterContainer->getWater();

        //calculates the espresso can be made per ingredients
        $numEspressosWithBeans = floor($beansLevel / $this->beansPerEspresso);
        $numEspressosWithWater = floor($waterLevel / $this->waterPerEspresso);

        //return smallest possible number of espresso 's
        if ($numEspressosWithBeans < $numEspressosWithWater) {
            return $numEspressosWithBeans;
        }

        return $numEspressosWithWater;
    }

    /**
     * Adds beans to the container
     *
     * @param int $numSpoons number of spoons of beans
     *
     * @return void
     *
     */
    public function addBeans(int $numSpoons): void
    {
        $this->beansContainer->addBeans($numSpoons);
    }

    /**
     * Adds water to the coffee machine's water tank
     *
     * @param float $litres number of litres of water
     *
     * @return void
     */
    public function addWater(float $litresWater): void
    {
        $this->waterContainer->addWater($litresWater);
    }

}
