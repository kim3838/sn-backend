<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Blueprint\Repositories\PrototypeRepository;
use App\Models\Prototype;
use App\Validators\PrototypeValidator;

/**
 * Class PrototypeRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PrototypeRepositoryEloquent extends BaseRepository implements PrototypeRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Prototype::class;
    }

    /**
    * Specify Validator class name
    *
    * @return string
     */
    public function validator(): string
    {
        return PrototypeValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
