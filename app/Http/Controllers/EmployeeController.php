<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetEmployeeRequest;
use App\Http\Requests\ShowEmployeeRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;

class EmployeeController extends AbstractResourceController
{
    /**
     * EmployeeController constructor.
     *
     * @param ResponseFactory $response
     * @param Employee $employee
     */
    public function __construct(ResponseFactory $response, Employee $employee)
    {
        parent::__construct($response);
        $this->setModel($employee);
    }

    /**
     * Get list of Employees
     *
     * @param GetEmployeeRequest $request
     *
     * @return JsonResponse
     */
    public function index(GetEmployeeRequest $request)
    {
        $options = [
            'searchIn' => [
                'name',
                'login',
            ],
        ];

        if ($sort = $request->input('sort')) {
            $request->merge([
              'orderDirection' => substr($sort, 0, 1),
                'orderBy' => substr($sort, 1),
            ]);
        }

        $response = parent::_index($request, $options);

        $response['items'] = EmployeeResource::collection($response['items']);

        return $this->respondOk($response);
    }

    /**
     * Show an Employee
     *
     * @param ShowEmployeeRequest $request
     * @param Employee $employee
     *
     * @return JsonResponse
     */
    public function show(ShowEmployeeRequest $request, Employee $employee)
    {
        return $this->respondOk(new EmployeeResource($employee));
    }

    /**
     * Update an Employee
     *
     * @param UpdateEmployeeRequest $request
     * @param Employee $employee
     *
     * @return JsonResponse
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->all());

        return $this->respondOk();
    }

    /**
     * Store Employees
     *
     * @param StoreEmployeeRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreEmployeeRequest $request)
    {
        foreach ($request->input('employees') as $employee) {
            Employee::create([
               'name' => $employee['name'],
               'login' => $employee['login'],
            ]);
        }

        return $this->respondCreated();
    }
}