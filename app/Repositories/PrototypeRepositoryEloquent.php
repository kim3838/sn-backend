<?php

namespace App\Repositories;

use App\Blueprint\Repositories\PrototypeRepository;
use App\Models\Prototype;
use App\Services\BaseRepositoryEloquent;
use App\Validators\PrototypeValidator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class PrototypeRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PrototypeRepositoryEloquent extends BaseRepositoryEloquent implements PrototypeRepository
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

    public function selection()
    {
        $filters = json_decode(Request::get('filters'));

        $queryBuilder = $this->model->getQuery()
            ->orderBy('name', 'ASC')
            ->when($filters->id ?? false, function ($builder, $value) {
                $builder->whereIn('id', $value);
            })
            ->when($filters->search ?? false, function ($builder, $value) {
                $builder->where(function ($query) use ($value) {
                    $query->where('name', 'like', '%' . $value . '%')->orWhere('code', 'like', '%' . $value . '%');
                });
            });

        $paginator = $this->createPaginationFromBuilder($queryBuilder);

        return $this->hydratePaginationItems($paginator, $this->model);
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
