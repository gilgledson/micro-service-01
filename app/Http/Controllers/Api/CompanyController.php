<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateCompany;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    protected $repository;

    public function __construct(Company $model)
    {
        $this->repository = $model;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies =  $this->repository->with('category')->paginate();

        return CompanyResource::collection($companies);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateCompany $request)
    {
        $company = $this->repository->create($request->validated());
        return new CompanyResource($company);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $uid
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $company = $this->repository->where('uuid', $uuid)->firstOrFail();
        return response()->json(new CompanyResource($company));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateCompany $request, $uuid)
    {
        $company = $this->repository->where('uuid', $uuid)->firstOrFail();
        $company->update($request->validated());
        return response()->json(['message' => 'updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $uuid
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $company = $this->repository->where('uuid', $uuid)->firstOrFail();
        $company->delete();

        return response()->json([], 204);
    }
}
