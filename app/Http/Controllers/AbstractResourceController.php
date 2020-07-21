<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class AbstractResourceController extends AbstractApiController
{
    /** @var Model */
    protected $model;

    /**
     * Set the model used in this resource
     *
     * @param Model $model
     *
     * @return void
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * Return model items filtered and with pagination
     *
     * @param Request $request
     *
     * @param array $options
     *
     * @return array
     */
    public function _index(Request $request, array $options): array
    {
        $offset = $request->input('offset') ?: 1;
        $limit = min(10, $request->input('limit') ?: 10);

        $baseQuery = $this->model->select($this->model->getTable() . '.*');

        if ($request->input('orderBy') && $request->input('orderDirection')) {
            $directionToSql = [
                '+' => 'ASC',
                '-' => 'DESC',
            ];
            $baseQuery->orderBy($request->input('orderBy'), $directionToSql[$request->input('orderDirection')]);
        }

        if ($request->input('where')) {
            $baseQuery->where($request->input('where'));
        }

        if ($request->input('search') && isset($options['searchIn'])) {
            $baseQuery->where(function ($query) use ($request, $options) {
                foreach ($options['searchIn'] as $key => $columns) {
                    $query->orWhere($columns, 'like', '%' . $request->input('search') . '%');
                }
            });
        }

        $itemsQuery = (clone $baseQuery);
        $itemsQuery
            ->offset(($offset - 1) * $limit)
            ->limit($limit);

        $items = $itemsQuery->get();
        $rowsCount = (clone $baseQuery)->count();

        return compact('items', 'rowsCount');
    }
}