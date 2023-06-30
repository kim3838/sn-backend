<?php

namespace App\Presenters;

use App\Transformers\PrototypeTransformer;
use League\Fractal\TransformerAbstract;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class PrototypePresenter.
 *
 * @package namespace App\Presenters;
 */
class PrototypePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return TransformerAbstract
     */
    public function getTransformer(): TransformerAbstract
    {
        return new PrototypeTransformer();
    }
}
