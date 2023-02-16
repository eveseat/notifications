<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace Seat\Notifications\Http\DataTables;

use Seat\Notifications\Models\NotificationGroup;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\JsonResponse;

/**
 * Class NotificationGroupDataTable.
 *
 * @package Seat\Notifications\Http\DataTables
 */
class NotificationGroupDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function ajax() : JsonResponse
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->addColumn('alerts', function ($row) {
                return $row->alerts->count();
            })
            ->addColumn('integrations', function ($row) {
                return $row->integrations->count();
            })
            ->addColumn('affiliations', function ($row) {
                return $row->affiliations->count();
            })
            ->editColumn('action', function ($row) {
                return view('notifications::groups.partials.actions', compact('row'));
            })
            ->filterColumn('alerts', function ($query, $keyword) {
                $query->whereHas('alerts', function ($sub_query) use ($keyword) {
                    $sub_query->whereRaw('alert LIKE ?', ["%$keyword%"]);
                });
            })
            ->filterColumn('integrations', function ($query, $keyword) {
                $query->whereHas('integrations', function ($sub_query) use ($keyword) {
                    $sub_query->whereRaw('name LIKE ?', ["%$keyword%"])
                        ->orWhereRaw('type LIKE ?', ["$keyword"]);
                });
            })
            ->filterColumn('affiliations', function ($query, $keyword) {
                $query->whereHas('affiliations.entity', function ($sub_query) use ($keyword) {
                    $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
            })
            ->toJson();
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->postAjax()
            ->columns($this->getColumns())
            ->addAction();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return NotificationGroup::with('alerts', 'integrations', 'affiliations', 'affiliations.entity');
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'name', 'title' => trans('notifications::notifications.name')],
            ['data' => 'alerts', 'title' => trans_choice('notifications::notifications.alert', 2), 'orderable' => false],
            ['data' => 'integrations', 'title' => trans_choice('notifications::notifications.integration', 2), 'orderable' => false],
            ['data' => 'affiliations', 'title' => trans_choice('notifications::notifications.affiliation', 2), 'orderable' => false],
        ];
    }
}
