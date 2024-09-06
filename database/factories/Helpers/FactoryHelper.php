<?php

namespace Database\Factories\Helpers;

use \Illuminate\Database\Eloquent\Factories\HasFactory;

class FactoryHelper
{

    /**
     * This function will get a random model id from the db
     * @param string | HasFactory $model
     * @return void
     */
    public static function getRandomModelId(string $model): int {
        $count = $model::count();

        if( $count === 0) {
            return $model::factory()->create()->id;
        } else {
            return rand(1, $count);
        }

    }
}
