<?php

namespace Tests\Feature;

use App\Models\Employee;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    protected Employee $employee;

    public function setUp(): void
    {
        parent::setUp();

        $this->employee = factory(Employee::class)->create([
            'login' => 'don.john',
            'name' => 'Don John',
        ]);
    }

    public function test_get_employee_end_point()
    {
        $this->json('GET', 'v1/employees')
            ->assertStatus(200)
            ->assertJsonStructure(['items', 'rowsCount'])
            ->assertJsonFragment([
                'uuid' => $this->employee->uuid,
            ]);
    }

    public function test_get_employee_with_filters_end_point()
    {
        $this->json('GET', 'v1/employees', [
            'search' => 'John',
        ])
            ->assertStatus(200)
            ->assertJsonStructure(['items', 'rowsCount'])
            ->assertJsonFragment([
                'uuid' => $this->employee->uuid,
            ]);

        $employee2 = factory(Employee::class)->create([
            'login' => 'jose.rose',
            'name' => 'Jose Rose',
        ]);

        $this->json('GET', 'v1/employees', [
            'limit' => 1,
            'offset' => 1,
            'sort' => '-name',
        ])
            ->assertStatus(200)
            ->assertJsonStructure(['items', 'rowsCount'])
            ->assertJsonFragment([
                'uuid' => $employee2->uuid,
            ])
            ->assertJsonMissing([
                'uuid' => $this->employee->uuid,
            ]);
    }

    public function test_get_employee_with_invalid_filters_end_point()
    {
        $this->json('GET', 'v1/employees', [
            'sort' => '*name',
        ])->assertStatus(422)->assertJsonValidationErrors(['sort']);

        $this->json('GET', 'v1/employees', [
            'sort' => '+foo',
        ])->assertStatus(422)->assertJsonValidationErrors(['sort']);
    }

    public function test_show_employee_end_point()
    {
        $this->json('GET', 'v1/employees/' . $this->employee->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'uuid',
                'name',
                'login',
                'created_at',
            ]);

        $this->json('GET', 'v1/employees/' . 'foo-bar')
            ->assertStatus(404);
    }

    public function test_update_employee_end_point()
    {
        $employee = factory(Employee::class)->create([
            'login' => 'torey.white.login',
        ]);

        $this->json('PUT', 'v1/employees/' . $employee->uuid, [
            'login' => 'torey.white.login',
            'name' => 'Torrey White',
        ])->assertStatus(200);

        $this->assertDatabaseHas('employees', [
            'uuid' => $employee->uuid,
            'login' => $employee->login,
            'name' => 'Torrey White',
        ]);
    }

    public function test_update_existing_employee_end_point()
    {
        $employee2 = factory(Employee::class)->create([
            'login' => 'torey.brown.login',
        ]);
        $this->json('PUT', 'v1/employees/' . $employee2->uuid, [
            'login' => $this->employee->login,
            'name' => 'Torrey Brown',
        ])->assertStatus(422)->assertJsonValidationErrors(['login']);
    }

    public function test_create_employees_end_point()
    {
        $this->json('POST', 'v1/employees', [
            'employees' => [
                [
                    'name' => 'employee 1',
                    'login' => 'employee 1',
                ],
            ],
        ])->assertStatus(201);
        $this->assertDatabaseHas('employees', [
            'name' => 'employee 1',
            'login' => 'employee 1',
        ]);
    }

    public function test_create_employees_with_invalid_payload_end_point()
    {
        $this->json('POST', 'v1/employees', [
            'employees' => [
                [
                    'name' => 'employee 1',
                ],
            ],
        ])->assertStatus(422)->assertJsonValidationErrors(['employees']);

        $this->json('POST', 'v1/employees', [
            'employees' => [
                [
                    'login' => 'employee.1',
                    'name' => 'employee 1',
                ],
                [
                    'login' => 'employee.1',
                    'name' => 'employee 2',
                ],
            ],
        ])->assertStatus(422)->assertJsonValidationErrors(['employees']);
    }
}
