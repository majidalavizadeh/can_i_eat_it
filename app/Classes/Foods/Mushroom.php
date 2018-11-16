<?php
/**
 * Created by PhpStorm.
 * User: majid
 * Date: 11/16/18
 * Time: 11:40 AM
 */

namespace App\Classes\Foods;


use App\Interfaces\FoodResponse;
use Illuminate\Http\Request;

class Mushroom implements FoodResponse
{

    protected $length;
    protected $color;
    protected $weight;

    // Define poisonous mushrooms (It's better to store in database in a real project)
    protected $poisonous_mushrooms = [
        'Mushroom 1' => [
            'length' => [
                'min' => 1,
                'max' => 5
            ],
            'weight' => 2,
            'color' => 'red'
        ],
        'Mushroom 2' => [
            'length' => [
                'min' => 4,
                'max' => 6
            ],
            'weight' => 3,
            'color' => 'grey'
        ]
    ];

    /**
     * Mushroom constructor.
     * Set needed properties
     */
    public function __construct(Request $param)
    {
        if (!isset($param->length) || !isset($param->color) || !isset($param->weight)) {
            throw new \Exception(__('foods.not_parameters'));
        }
        $this->length = $param->length;
        $this->color = $param->color;
        $this->weight = $param->weight;
    }

    /**
     * Return the final response
     * @return \Illuminate\Http\JsonResponse
     */
    public function getResponse()
    {
        return response()->json([
            'is_poisonous' => $this->is_poisonous(),
            'message' => $this->is_poisonous() ? __('foods.dont_eat_it') : __('foods.eat_it')
        ]);
    }


    /**
     * Is poisonous mushroom ?
     * @return bool
     */
    public function is_poisonous()
    {
        return in_array(true, $this->getPoisonousMushrooms());
    }


    /**
     * Get the name of poisonous mushroom
     * @return false|int|string
     */
    public function getMushroomName()
    {
        //TODO: Maybe more than one mushroom is poisonous so we should return an array of mushrooms name
        return array_search(true, $this->getPoisonousMushrooms());
    }


    /**
     * Get the poisonous mushrooms in $poisonous_mushrooms array according to given parameters
     * @return array|bool
     */
    public function getPoisonousMushrooms()
    {
        return array_map(function ($value) {
                if ($this->color == $value['color']
                    && $this->length >= $value['length']['min']
                    && $this->length <= $value['length']['max']
                    && $this->weight == $value['weight']) {
                    return true;
                } else {
                    return false;
                }
            }, $this->poisonous_mushrooms) ?? false;
    }
}