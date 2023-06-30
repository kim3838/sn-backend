<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Prototype;

/**
 * Class PrototypeTransformer.
 *
 * @package namespace App\Transformers;
 */
class PrototypeTransformer extends TransformerAbstract
{
    /**
     * Transform the Prototype entity.
     *
     * @param \App\Models\Prototype $model
     *
     * @return array
     */
    public function transform(Prototype $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
