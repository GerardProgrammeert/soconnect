<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormRequestEspressoMachine;
use App\Models\EspressoMachine as modelEspressoMachine;

/**
 * Remarks
 * General Exceptions are handled in App/Exceptions/Handler
 * like ModelNotFoundException and Exception
 */
class EspressoMachineController extends Controller
{

    /**
     * Returns an overview of espresso machines
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(){
        //get all models
        $espressoMachines = modelEspressoMachine::all();
        //return response
        return response()->json($espressoMachines);
    }

    /**
     * store a espresso machine in db
     *
     * @param FormRequestEspressoMachine $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function config(FormRequestEspressoMachine $request)
    {
        //validate input
        $data = $request->validated();

        //create espressomachine /store data in db
        $espressoMachine = new modelEspressoMachine($data);
        $espressoMachine->save();

        //return response
        return response()->json($espressoMachine->refresh()->toArray(),200);
    }

    /**
     * update mode espresso machine
     * @param FormRequestEspressoMachine $request
     * @param modelEspressoMachine $modelEspressoMachine
     * @return \Illuminate\Http\JsonResponse
     */
    public function reconfig(FormRequestEspressoMachine $request, modelEspressoMachine $modelEspressoMachine)
    {
        //validate and update
        $modelEspressoMachine->update($request->validated());
        //return response
        return response()->json($modelEspressoMachine->refresh()->toArray(),200);
    }

    /**
     * Deletes the model from db
     * @param modelEspressoMachine $modelEspressoMachine
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(modelEspressoMachine $modelEspressoMachine)
    {
        //delete
        $modelEspressoMachine->delete();
        //return response
        return response()->json([],204);
    }
}
