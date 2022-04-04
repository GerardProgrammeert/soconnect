<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormRequestAddWater;
use App\Http\Requests\FormRequestAddBeans;
use App\Models\EspressoMachine as modelEspressoMachine;
use SoConnect\Coffee\ContainerFullException;
use SoConnect\Coffee\EspressoWaterContainer;
use SoConnect\Coffee\EspressoBeansContainer;
use SoConnect\Coffee\NoBeansException;
use SoConnect\Coffee\NoWaterException;
use SoConnect\Coffee\EspressoMachine;

/**
 * Remarks
 * General Exceptions are handled in App/Exceptions/Handler
 * like ModelNotFoundException and Exception
 */
class EspressoController extends Controller
{
    /**
     * Creates one espresso
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function one(modelEspressoMachine $modelEspressoMachine)
    {
      try{
            $espressoMachine = new EspressoMachine(new EspressoWaterContainer($modelEspressoMachine), new EspressoBeansContainer($modelEspressoMachine));
            $result = $espressoMachine->makeEspresso();
            return response()->json([$result],200);
        }
        catch(NoBeansException | NoWaterException $e) {
            return response()->json(['error'=> $e->getMessage()],400);
        }
    }

    /**
     * Creates a double espresso
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function double(modelEspressoMachine $modelEspressoMachine)
    {
        try{
            $espressoMachine = new EspressoMachine(new EspressoWaterContainer($modelEspressoMachine), new EspressoBeansContainer($modelEspressoMachine));
            $result = $espressoMachine->makeDoubleEspresso();
            return response()->json([$result],200);
        }
        catch(NoBeansException | NoWaterException $e) {
            return response()->json(['error'=> $e->getMessage()],400);
        }
    }

    /**
     * Method to add water to the machine
     * @param modelEspressoMachine $modelEspressoMachine
     * @param FormRequestAddWater $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addWater(modelEspressoMachine $modelEspressoMachine, FormRequestAddWater $request)
    {
        ///validate data
        $validated = $request->validated();

        try {
            $espressoMachine = new EspressoMachine(new EspressoWaterContainer($modelEspressoMachine), new EspressoBeansContainer($modelEspressoMachine));
            $espressoMachine->addWater($validated['water']);
            return response()->json($modelEspressoMachine->toArray());
        }
        catch(ContainerFullException $e){
            return response()->json(['error' => $e->getMessage()],400);
        }

    }

    /**
     * Method to add beans
     * @param modelEspressoMachine $modelEspressoMachine
     * @param FormRequestAddBeans $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addBeans(modelEspressoMachine $modelEspressoMachine, FormRequestAddBeans $request)
    {
        //validate data
        $validated = $request->validated();

        try {
            $espressoMachine = new EspressoMachine(new EspressoWaterContainer($modelEspressoMachine), new EspressoBeansContainer($modelEspressoMachine));
            $espressoMachine->addBeans($validated['beans']);
            return response()->json($modelEspressoMachine->toArray());
        }
        catch(ContainerFullException $e){
            return response()->json(['error' => $e->getMessage()],400);
        }
    }

    /**
     * Method to show to status of machine
     * @param modelEspressoMachine $modelEspressoMachine
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(modelEspressoMachine $modelEspressoMachine)
    {
        $espressoMachine = new EspressoMachine(new EspressoWaterContainer($modelEspressoMachine), new EspressoBeansContainer($modelEspressoMachine));
        return response()->json($espressoMachine->getStatus());
    }
}
