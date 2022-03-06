<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Setting;
use App\Models\BreakTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\AllSettingsFormat;
use Illuminate\Support\Facades\Lang;

class BreakTimeController extends Controller
{
    private $timeFormat;

    public function __construct()
    {
        $settingsTimeFormat = Setting::where('setting_name', 'time_format')->first()->setting_value;
        $this->timeFormat = $settingsTimeFormat == '12h' ? 'h:i A' : 'H:i';
    }
    
    public function index()
    {
        $data = BreakTime::all();

        $formatedData = $this->makeDataReadAble($data);

        return [
            'datarows' => $formatedData,
            'count' => count($data)
        ];
    }

    public function getAllData()
    {
        return BreakTime::all();
    }
    
    public function store(Request $request){
        $data = $request->all();
        $data['start_time'] = Carbon::createFromFormat($this->timeFormat, $data['start_time'])->format('H:i:s');
        $data['end_time'] = Carbon::createFromFormat($this->timeFormat, $data['end_time'])->format('H:i:s');
        BreakTime::create($data);

        $response = [
            'message' => Lang::get('lang.break_time_created_successfully')
        ];
        return response()->json($response, 201);
    }

    public function show($id)
    {
        $data = BreakTime::find($id);
        $data->start_time = date($this->timeFormat, strtotime($data->start_time));
        $data->end_time = date($this->timeFormat, strtotime($data->end_time));

        return $data;
    }


    public function edit(Request $request, $id)
    {
        // dd($request->all(), "sdfds", $id);
    }

    public function update($id, Request $request)
    {
        $fillable = $request->all();
        $fillable['start_time'] = Carbon::createFromFormat($this->timeFormat, $fillable['start_time'])->format('H:i:s');
        $fillable['end_time'] = Carbon::createFromFormat($this->timeFormat, $fillable['end_time'])->format('H:i:s');
        $data = BreakTime::find($id);
        $data->update($fillable);

        $response = [
            'message' => Lang::get('lang.break_time_updated_successfully')
        ];

        return response()->json($response, 201);
    }

    public function destroy($id, Request $request)
    {
        $data = BreakTime::find($id);

        if ($data) {
            $data->delete();
            $response = [
                'message' => Lang::get('lang.break_time_deleted_successfully')
            ];
            return response()->json($response, 201);
        }
    }

    public function makeDataReadAble($data)
    {
        $settingApp = new AllSettingsFormat;

        foreach ($data as $dt) {

            $dt->start_time = $settingApp->timeFormat($dt->start_time);
            $dt->end_time = $settingApp->timeFormat($dt->end_time);

            if ($dt->is_enable == 1) $dt->is_enable = Lang::get('lang.yes');
            else $dt->is_enable = Lang::get('lang.no');
        }

        return $data;
    }
}
