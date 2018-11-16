<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FoodsController extends Controller
{
    // Define the food classes namespace
    protected $foodClassesNamespace = "\App\Classes\Foods";

    /**
     * Main controller methods
     * @param $type
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function types($type, Request $request)
    {
        if (!class_exists($this->getClassName($type))) {
            return response()->json([
                'message' => __('foods.not_exists', ['attribute' => $type])
            ], 404);
        }

        try {
            $result = $this->createInstance($this->getClassName($type), $request);
            return $result->getResponse();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get the class name with namespace
     * @param $type
     * @return string
     */
    public function getClassName($type)
    {
        return $this->foodClassesNamespace . '\\' . ucwords($type);
    }


    /**
     * Create an instance
     * @param $class
     * @param $params
     * @return mixed
     */
    public function createInstance($class, $params)
    {
        return new $class($params);
    }
}
