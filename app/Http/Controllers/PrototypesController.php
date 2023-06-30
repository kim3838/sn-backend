<?php

namespace App\Http\Controllers;

use App\Blueprint\Repositories\PrototypeRepository;
use App\Criteria\PrototypeCriteria;
use App\Http\Requests;
use App\Http\Requests\PrototypeCreateRequest;
use App\Http\Requests\PrototypeUpdateRequest;
use App\Validators\PrototypeValidator;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class PrototypesController.
 *
 * @package namespace App\Http\Controllers;
 */
class PrototypesController extends Controller
{
    /**
     * @var PrototypeRepository
     */
    protected $repository;

    /**
     * @var PrototypeValidator
     */
    protected $validator;

    /**
     * PrototypesController constructor.
     *
     * @param PrototypeRepository $repository
     * @param PrototypeValidator $validator
     */
    public function __construct(PrototypeRepository $repository, PrototypeValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->repository->pushCriteria(app(RequestCriteria::class));

        $this->repository->pushCriteria(PrototypeCriteria::class);
        
        if (request()->wantsJson()) {
            return $this->successfulResponse([]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PrototypeCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(PrototypeCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $prototype = $this->repository->create($request->all());

            if ($request->wantsJson()) {
                return $this->successfulResponse([]);
            }

        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return $this->validationErrorResponse($e->getMessageBag());
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $prototype = $this->repository->find($id);

        if (request()->wantsJson()) {
            return $this->successfulResponse([]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PrototypeUpdateRequest $request
     * @param string $id
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(PrototypeUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $prototype = $this->repository->update($request->all(), $id);

            if ($request->wantsJson()) {
                return $this->successfulResponse([]);
            }
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {
                return $this->validationErrorResponse($e->getMessageBag());
            }
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {
            return $this->successfulResponse([]);
        }
    }
}
