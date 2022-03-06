<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\HolidayResource;
use App\Libraries\Permissions;
use App\Models\Holiday;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::all();
        return HolidayResource::collection($holidays);
    }

    public function sortedList(Request $request)
    {
        if ($request->columnKey) $columnName = $request->columnKey;
        if ($request->rowLimit) $limit = $request->rowLimit;
        $offset = $request->rowOffset;

        $data = Holiday::orderBy($columnName, $request->columnSortedBy)->take($limit)->skip($offset)->get();
        $totalCount = Holiday::count();

        return ['dataRows' => $data, 'count' => $totalCount];
    }

    public function permissionCheck()
    {
        $controller = new Permissions;

        return $controller;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $start = date('Y-m-d', strtotime(str_replace(['.', '/'], '-', $request->start_date)));
        $end = date('Y-m-d', strtotime(str_replace(['.', '/'], '-', $request->end_date)));

        $s = date('d', strtotime(str_replace(['.', '/'], '-', $request->start_date)));
        $e = date('d', strtotime(str_replace(['.', '/'], '-', $request->end_date)));

        if ($e < $s) {
            return response()->json(['message' => Lang::get('lang.end_date_cannot_be_less_than_start_date')], 400);
        }

        $holiday = $holiday = Holiday::create([
            'title' => $request->title,
            'start_date' => $start,
            'end_date' => $end,
            'created_by' => Auth::user()->id,
        ]);

        if ($holiday) {
            $response = [
                'message' => Lang::get('lang.holiday_successfully_saved'),
                'Holiday' => $holiday
            ];
            return response()->json($response, 201);

        } else {
            $response = [
                'message' => Lang::get('lang.error_during_creating')
            ];
        }


        return response()->json($response, 404);
    }

    public function show($id)
    {
        $data = Holiday::find($id);

        return $data;
    }

    public function updateHolidays(Request $request, $id)
    {
        $holiday = Holiday::findOrFail($id);

        $s = date('d', strtotime(str_replace(['.', '/'], '-', $request->start_date)));
        $e = date('d', strtotime(str_replace(['.', '/'], '-', $request->end_date)));

        if ($e < $s) {
            return response()->json(['message' => Lang::get('lang.cannot_be_less_than_start_date')], 401);
        }

        if ($holiday->update($request->all())) {
            $response = [
                'message' => Lang::get('lang.holiday_successfully_update'),
                'Holiday' => $holiday
            ];
            return response()->json($response, 201);

        } else {
            $response = [
                'message' => Lang::get('lang.error_during_creating')
            ];
        }


        return response()->json($response, 404);

    }

    public function destroy($id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();

        return response()->json(['message' => Lang::get('lang.holiday_deleted_successfully')], 201);
    }

}
