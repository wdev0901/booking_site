<?php

namespace App\Http\Controllers\API;

use App\User;
use Aws\Api\Service;
use DateTime;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Holiday;
use App\Models\Setting;
use App\Models\Services;
use App\Models\BreakTime;
use Illuminate\Support\Str;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use League\Flysystem\Config;
use App\Models\ServicePolicy;
use App\Libraries\Permissions;
use App\Libraries\CommonHelper;
use App\Models\ServiceEmployee;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Libraries\AllSettingsFormat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use function PHPSTORM_META\map;


class ServiceController extends Controller
{


    private $timeFormat;
    private $dateFormat;
    private $timeZone;

    public function __construct()
    {
        $settingsTimeFormat = Setting::where('setting_name', 'time_format')->first()->setting_value;
        $this->dateFormat = Setting::where('setting_name', 'date_format')->first()->setting_value;
        $this->timeZone = Setting::where('setting_name', 'time_zone')->first()->setting_value;
        $settingsTimeFormat == '12h' ? $this->timeFormat = 'h:i A' : $this->timeFormat = 'H:i';
    }

    public function getServiceListData()
    {
        $business_type = Setting::getSettingValue('business_type');
        $services = Services::with('serviceImage')
            ->where('activation', 1)
            ->where('business_type', '=', $business_type->setting_value)
            ->where(function ($data) {
                $data->whereRaw('service_starting_date <= DATE_ADD(curdate(), INTERVAL allow_booking_max_day_ago DAY)')
                    ->orWhereNull('service_starting_date');
            })->get();

        $holidays = Holiday::all();
        $data = Setting::where('setting_name', '=', 'offday_setting')->first();
        $offDays = [];
        if ($data) {
            $offDays = explode(',', $data->setting_value);
        }
        $validServices = [];
        $today = date("Y-m-d");
        if (!empty($services)) {
            foreach ($services as $key => $item) {
                if (!empty($item->allow_booking_max_day_ago)) {
                    $allowMaxDaysPositive = ' + ' . $item->allow_booking_max_day_ago . ' days';
                    $allowMaxDaysNegative = ' - ' . $item->allow_booking_max_day_ago . ' days';

                    $allowStartDate = date('Y-m-d', strtotime($allowMaxDaysNegative, strtotime($today)));
                    $allowEndDate = date('Y-m-d', strtotime($today . $allowMaxDaysPositive));

                    if (!empty($item->service_starting_date)) {
                        $allowStartDate = date('Y-m-d', strtotime($allowMaxDaysNegative, strtotime($item->service_starting_date)));
                        $allowEndDate = date('Y-m-d', strtotime($item->service_starting_date . $allowMaxDaysPositive));
                    } else {
                        $item->service_starting_date = $today;
                        $item->service_ending_date = $allowEndDate;
                    }

                    if ($today >= $allowStartDate) {
                        array_push($validServices, $item);
                    }
                } else {
                    array_push($validServices, $item);
                }
            }
        }
        return [
            'services' => $validServices,
            'holidays' => $holidays,
            'offDays' => $offDays
        ];
    }

    public function activeData()
    {
        $data = Services::all()->where('activation', 1);
        return $data;
    }

    public function services()
    {
        return view('services.index');
    }

    public function permissionCheck()
    {
        $controller = new Permissions;
        return $controller;
    }

    public function getData(Request $request)
    {
        $getServiceList = Services::getAllService($request);
        $allGetServiceList = $getServiceList['data'];
        $allGetServiceList = $this->makeDataReadAble($allGetServiceList);

        $customData = new CustomDataController();
        $addCustomData = $customData->showCustomDataInDataTable('service', $allGetServiceList);

        $allGetServiceList = $addCustomData['data'];
        $columnName = $addCustomData['columnName'];
        $customDateId = $addCustomData['customDateId'];

        return ['datarows' => $allGetServiceList, 'count' => $getServiceList['count'], 'columnName' => $columnName, 'customDateId' => $customDateId];
    }


    public function storeImage(Request $request)
    {
        $fileNames = array();

        if ($request->has('uploads')) {
            foreach ($request->uploads as $file) {
                $oiginalName = $file->getClientOriginalName();
                $originalExtension = $file->getClientOriginalExtension();

                $fileName = time() . '.' . $oiginalName;

                $file->move(public_path('files'), $fileName);
                $fileNames[] = $fileName;

            }
        }


        $storeFile = [
            'name' => $request->name,
            'file_names' => serialize($fileNames),
            'description' => $request->description,
            'departments' => serialize($request->department),
            'created_by' => 1
        ];
        $success = FileManagement::store($storeFile);

        if ($success) {
            $response = [
                'message' => Lang::get('lang.file_management') . ' ' . Lang::get('lang.successfully_saved')
            ];
            return response()->json($response, 201);
        } else {
            $response = [
                'message' => Lang::get('lang.getting_problems')
            ];
            return response()->json($response, 404);
        }
    }


    public function getSortedData(Request $request)
    {
        if ($request->columnKey) $columnName = $request->columnKey;
        if ($request->rowLimit) $limit = $request->rowLimit;
        $offset = $request->rowOffset;

        $data = Services::orderBy($columnName, $request->columnSortedBy)->take($limit)->skip($offset)->get();
        $data = $this->makeDataReadAble($data);

        $customData = new CustomDataController();
        $addCustomData = $customData->showCustomDataInDataTable('service', $data);

        $data = $addCustomData['data'];
        $columnName = $addCustomData['columnName'];
        $customDateId = $addCustomData['customDateId'];

        $totalCount = Services::count();

        return ['dataRows' => $data, 'count' => $totalCount, 'columnName' => $columnName, 'customDateId' => $customDateId];
    }

    public function makeDataReadAble($data)
    {
        $settingApp = new AllSettingsFormat;

        foreach ($data as $dt) {

            $dt->service_starts = $settingApp->timeFormat($dt->service_starts);
            $dt->service_ends = $settingApp->timeFormat($dt->service_ends);

            if ($dt->multiple_bookings == 1) {
                $dt->multiple_bookings = Lang::get('lang.yes');

            } else {
                $dt->multiple_bookings = Lang::get('lang.no');
            }

            if (Booking::where('service_id', $dt->id)->count()) {
                $dt->used = 1;

            } else {
                $dt->used = 0;
            }

            $dt->price = $settingApp->getCurrency($settingApp->thousandSep($dt->price));
        }

        return $data;
    }

    public function getServiceAndOffDays()
    {
        $allSet = new AllSettingsFormat;
        $business_type = Setting::getSettingValue('business_type');

        $data = Services::where('activation', 1)
            ->where('business_type', '=', $business_type->setting_value)
            ->where(function ($data) {
                $data->whereRaw('service_starting_date <= DATE_ADD(curdate(), INTERVAL allow_booking_max_day_ago DAY)')
                    ->orWhereNull('service_starting_date');
            })->get();

        $offDays = Setting::Select('setting_value')->where('setting_name', 'offday_setting')->first();

        if (!$offDays) {
            $offDays = [0];

        } else {
            $offDays = array_map('intval', explode(',', $offDays->setting_value));
        }

        $holydays = Holiday::Select('start_date', 'end_date')->get();

        foreach ($data as $d) {

            $d->service_price = $d->price;
            $d->price = $allSet->getCurrency($d->price);
        }

        return ['serviceData' => $data, 'offdays' => $offDays, 'holydays' => $holydays];

    }

    public function getServiceList($id)
    {
        return Services::select('*')->where('id','!=', $id)->where('business_type', 'salon')->get();
    }

    public function getTiming($id, $bDate)
    {
        $allSet = new AllSettingsFormat;

        $data = Services::where('id', $id)->first();
        $stackSlot = array();
        $bookedSlot = array();
        $bookedSlotDuration = array();
        $timeSlotBooked = array();
        $bookedSeat = array();
        $availableSeat = array();
        $combineSlotSeat = array_combine($bookedSlot, $bookedSeat);

        $serviceIds = $this->referredService($data->referred_service_id);
        array_push($serviceIds, $id);

        $breakTime = $data->break_time ? $this->getBreakTime($data->break_time) : [];

        $bufferTime = Services::find($id)->buffer_time ?? 0;

        $allBook = array();
        foreach ($serviceIds as $id) {
            $seat = Booking::select('bookings.booking_time', 'services.service_duration', 'bookings.quantity')
                            ->join('services', 'services.id', '=', 'bookings.service_id')
                            ->where(['bookings.service_id' => $id, 'bookings.booking_date' => $bDate])
                            ->where('bookings.status', '!=', 'canceled')->get();
            array_push($allBook, $seat);
        }

        if ($data->service_duration_type == 'hourly') {
            foreach ($allBook as $seat) {
                foreach ($seat as $s) {
                    array_push($bookedSlot, unserialize($s->booking_time));
                    array_push($bookedSeat, $s->quantity);
                    array_push($bookedSlotDuration, $s->service_duration);
                }
            }


            for ($time = $data->service_starts; $time < $data->service_ends;) {
                $finalSeat = 0;
                if ($time != $data->service_starts) {
                    $time = date("H:i:s", strtotime($time) + strtotime($bufferTime));
                }
                
                array_push($stackSlot, $time);

                foreach ($bookedSlot as $index => $bs) {

                    $duration = $bookedSlotDuration[$index];

                   foreach($bs as $key => $item){
                        $slotEndTime = date("H:i:s", (strtotime($duration) + strtotime($item)));
                        $slotStartTime = date("H:i:s", strtotime($item));
                        $timeAndDuration = date("H:i:s", (strtotime($data->service_duration) + strtotime($time)));

                        if ((($time >= $slotStartTime && $time < $slotEndTime) || ($timeAndDuration >= $slotStartTime && $timeAndDuration < $slotEndTime)) && in_array($time, $bs)){
                            $finalSeat += $bookedSeat[$index];
                        } else {
                            $finalSeat += 0;
                        }
                    }
                }

                array_push($availableSeat, $data->available_seat - $finalSeat);

                $maxTime = "23.59.59";
                $calculatedTime = strtotime($time) - strtotime("00:00:00") + strtotime($data->service_duration);

                if ($calculatedTime > strtotime($maxTime)) {
                    break;

                } else {
                    $time = date("H:i:s", $calculatedTime);
                }
            }
            /* for break time */
            $testSlot = [];
            if(count($breakTime) > 0){
                foreach($breakTime as $b_key => $b_val){
                    
                    $start_time =  date("H:i:s", strtotime($b_val->start_time));
                    $end_time = date("H:i:s", strtotime($b_val->end_time));
                    $b_start_time_and_duration =  $slotEndTime = date("H:i:s", (strtotime($data->service_duration) + strtotime($start_time)));
                    
                    foreach ($stackSlot as $s_key => $s_val) {
                        $slotEndTime = date("H:i:s", (strtotime($data->service_duration) + strtotime($s_val)));
                        $terget_time = date("H:i:s", strtotime($s_val)); 

                        if (($terget_time >= $start_time && $terget_time <= $end_time) || ($slotEndTime >= $start_time && ($slotEndTime <= $end_time || $slotEndTime <= $b_start_time_and_duration)))
                        {
                            unset($stackSlot[$s_key]);
                        }  
                    }
                }
            }
            
            $durationMin = CommonHelper::convertToMinutes($data->service_duration);
            $lastSlotMin = CommonHelper::convertToMinutes(end($stackSlot));
            $serviceEndMin = CommonHelper::convertToMinutes($data->service_ends);
            $sumOfDurationAndLastSlot = $durationMin + $lastSlotMin;

            if ($sumOfDurationAndLastSlot > $serviceEndMin) {
                array_pop($stackSlot);
            }
            $finalSlot = [];
            $currentDate = date($this->dateFormat);
            $currentTime = $this->getCurrentTime();
           
            foreach ($stackSlot as $key => $value) {
                 
                $endTime = date("H:i:s", strtotime($value) - strtotime("00:00:00") + strtotime($data->service_duration));
                
                if($currentDate == $bDate){
                    if($value > $currentTime){
                        $finalSlot[] = $allSet->timeFormat($value, true) . " - " . $allSet->timeFormat($endTime, true);
                    }
                }
                else{
                    $finalSlot[] = $allSet->timeFormat($value, true) . " - " . $allSet->timeFormat($endTime, true);
                }
            }

            return ['stack' => $finalSlot, 'seat' => $availableSeat, "currentTime" => $currentTime, "timezone" => date_default_timezone_get()];
        }

        if ($data->service_duration_type == 'daily') {

            $countedSeat = 0;

            foreach ($seat as $s) {

                $countedSeat = $countedSeat + (int)$s->quantity;
            }

            return ['stack' => [$allSet->getDate($bDate)], 'seat' => [$data->available_seat - $countedSeat]];
        }
    }

    public function referredService($data)
    {
        return explode(",",$data);
    }

    public function getBreakTime($data){
        $arr = explode(",",$data);
        $newArr = [];

        foreach($arr as $item){
            $v = BreakTime::where('id', $item)->where('is_enable',1)->first();
            
            if($v) array_push($newArr, $v);
        }
        return $newArr;
    }

    public function getCurrentTime(){
        date_default_timezone_set($this->timeZone);
        return date("H:i:s");
    }

    public function getTimingZero($id)
    {
        return ['stack' => 0, 'seat' => 0];
    }

    public function serviceUserlist()
    {
        $users = User::getServiceUserlist();
        return $users;
    }


    public function store(Request $request)
    {
        $allSet = new AllSettingsFormat;
        $this->validate($request, [
            'title' => 'required',
        ]);

        $title = $request->input('title');
        $price = $request->input('price');
        $service_duration_type = $request->service_duration_type;
        $service_starting_date = $request->service_starting_date;
        $service_ending_date = $request->service_ending_date;

        $request->service_starts == '' ? $service_starts = null : $service_starts = Carbon::createFromFormat($this->timeFormat, $request->service_starts)->format('H:i:s');
        $request->service_ends == '' ? $service_ends = null : $service_ends = Carbon::createFromFormat($this->timeFormat, $request->service_ends)->format('H:i:s');
        $request->service_duration == '' ? $service_duration = null : $service_duration = Carbon::createFromFormat('H:i', $request->service_duration)->format('H:i:s');


        $request->buffer_time == '' ? $buffer_time = null : $buffer_time = Carbon::createFromFormat('H:i:s', $request->buffer_time)->format('H:i:s');


        $multiple_bookings = $request->input('multiple_bookings');
        $available_seat = $request->available_seat;
        $description = $request->input('description');
        $max_booking = $request->input('max_booking');
        $created_by = Auth::user()->id;
        $business_type = Setting::getSettingValue('business_type');

        $serviceDetails = ['title' => $title,
            'business_type' => $business_type->setting_value,
            'price' => $price,
            'multiple_bookings' => $multiple_bookings,
            'description' => $description,
            'service_duration_type' => $service_duration_type,
            'service_starting_date' => $service_starting_date,
            'service_ending_date' => $service_ending_date,
            'service_starts' => $service_starts,
            'service_ends' => $service_ends,
            'service_duration' => $service_duration,
            'buffer_time' => $buffer_time,
            'max_booking' => $max_booking,
            'activation' => 1,
            'created_by' => $created_by,
            'available_seat' => $available_seat,
            'break_time' => $request->break_time,
            'referred_service_id' => $request->referred_service_id,
        ];

        $alias = str_replace(' ', '-', strtolower($title));
        $countAlias = Services::where('alias', $alias)->count();

        if ($countAlias > 0) {
            $maxId = Services::max('id') + 1;
            $serviceDetails['alias'] = $alias . '-' . $maxId;

        } else {
            $serviceDetails['alias'] = $alias;
        }

        if ($service = Services::create($serviceDetails)) {

            if ($request->referred_service_id) {
                $this->updateServiceReferredIds($request->referred_service_id, $service->id);
            }

            //Service Employee Start
            if ($service->id && $request->user_id) {
                foreach ($request->user_id as $key => $val) {
                    ServiceEmployee::create([
                        'service_id' => $service->id,
                        'user_id' => $val,
                    ]);
                }
            }
            //Service Employee End

            //Service images start
            if ($service->id && $request->has('uploads')) {

                $file = $request->uploads;
                $oiginalName = str_replace(' ', '', $file->getClientOriginalName());
                $file->move(public_path('files'), $oiginalName);
                $arr = array(
                    "service_id" => $service->id,
                    "image" => $oiginalName,
                    "is_default" => 1,
                );
                ServiceImage::insert($arr);

            } else if ($service->id) {
                $oiginalName = 'service.png';
                $arr = array(
                    "service_id" => $service->id,
                    "image" => $oiginalName,
                    "is_default" => 1,
                );
                ServiceImage::insert($arr);
            }
            //Service images end
            $customData = new CustomDataController();

            if ($request->has('uploads')) {
                $customData->insertUpdateCustomFieldData('service', $service->id, json_decode($request->customFields), false);
            } else {
                $customData->insertUpdateCustomFieldData('service', $service->id, json_decode($request->customFields), false);
            }

            $response = [
                'message' => Lang::get('lang.created_successfully'),
            ];

            return response()->json($response, 201);


            $response = [
                'message' => Lang::get('lang.error_during_creating')
            ];

            return response()->json($response, 404);
        }
    }

    public function updateServiceReferredIds($referredIds, $serviceId)
    {        
        $referredServiceIds = explode(',', $referredIds);

        foreach ($referredServiceIds as $id) {
            $getReferredId = Services::select('referred_service_id')->where('id', $id)->first();
            if ($getReferredId->referred_service_id) {
                $getReferredIdArray = explode(',', $getReferredId->referred_service_id);
                array_push($getReferredIdArray, $serviceId);
                $data = [
                    'referred_service_id' => implode(',', array_values($getReferredIdArray))
                ];
                Services::where('id', $id)->update($data);
            } else {
                $data = [
                    'referred_service_id' => $serviceId
                ];
                Services::where('id', $id)->update($data);
            }
        }
    }

    public function show($id)
    {
        $data = Services::with(array('serviceEmployee.user' => function ($query) {
            $query->select('id', 'first_name', 'last_name');
        }))->find($id);

        $customData = new CustomDataController();
        $customFields = $customData->getCustomFieldData('service', $id);
        $data->customFields = $customFields['options'];
        $data->dateId = $customFields['dateId'];

        $data->service_starts = date($this->timeFormat, strtotime($data->service_starts));
        $data->service_ends = date($this->timeFormat, strtotime($data->service_ends));
        $data->business_type = Setting::where('setting_name', 'business_type')->first()->setting_value;
        $data->service_image = ServiceImage::select('image')->where('service_id', $id)->first();

        return $data;
    }

    public function getServiceById($id)
    {
        $data = $this->show($id);

        $today = date("Y-m-d");
        if (!empty($data->allow_booking_max_day_ago)) {
            $maxAgoDay = $data->allow_booking_max_day_ago - 1;
            $allowMaxDaysPositive = ' + ' . $maxAgoDay . ' days';
            $allowEndDate = date('Y-m-d', strtotime($today . $allowMaxDaysPositive));
            if (empty($data->service_starting_date)) {
                $data->service_starting_date = $today;
                $data->service_ending_date = $allowEndDate;
            } else {
                if (date('Y-m-d', strtotime($data->service_ending_date)) >= $allowEndDate) {
                    $data->service_ending_date = $allowEndDate;
                }
            }
        }
        return $data;
    }

    public function deactivate(Request $request, $id)
    {
        $service = Services::find($id);
        $service->update($request->all());

        if ($service) {
            $response = [
                'message' => Lang::get('lang.successfully_updated'),
                'Service' => $service
            ];

            return response()->json($response, 201);
        }

        $response = [
            'message' => Lang::get('lang.error_during_update')
        ];

        return response()->json($response, 404);
    }

    public function update(Request $request, $id)
    {
        $allSet = new AllSettingsFormat;
        $service = Services::find($id);

        $request->service_duration == '' ? $request['service_duration'] = null : $request['service_duration'] = Carbon::createFromFormat('H:i', $request->service_duration)->format('H:i:s');

        if ($request->service_starts && $request->service_ends) {
            $request->service_starts == '' ? $request['service_starts'] = null : $request['service_starts'] = Carbon::createFromFormat($this->timeFormat, $request->service_starts)->format('H:i:s');
            $request->service_ends == '' ? $request['service_ends'] = null : $request['service_ends'] = Carbon::createFromFormat($this->timeFormat, $request->service_ends)->format('H:i:s');
        }

        if ($request->buffer_time != 'null' && $request->buffer_time != null) {
            $request->buffer_time = Carbon::createFromFormat('H:i:s', $request->buffer_time)->format('H:i:s');
        } else $request['buffer_time'] = null;

        if( !empty($request->referred_service_id) || !empty($service->referred_service_id)){
            if ($request->referred_service_id) {
                $this->editServiceReferredIds($request->referred_service_id, $service->referred_service_id, $id);
            } else {
                $this->updateAllReferredIds($service->referred_service_id,$id);
            }
        }

        $service->update($request->all());

        if ($request->has('uploads')) {
            $file = $request->uploads;
            $oiginalName = str_replace(' ', '', $file->getClientOriginalName());
            $file->move(public_path('files'), $oiginalName);
            $arr = array(
                "service_id" => $id,
                "image" => $oiginalName,
                "is_default" => 1,
            );
            ServiceImage::where('service_id', $id)->update($arr);
        }

        $customData = new CustomDataController();
        $customData->insertUpdateCustomFieldData('service', $id, json_decode($request->customFields));

        if ($service) {
            $response = [
                'message' => Lang::get('lang.service_updated_successfully'),
                'Service' => $service
            ];

            return response()->json($response, 201);
        }

        $response = [
            'message' => Lang::get('lang.error_during_update')
        ];

        return response()->json($response, 404);
    }

    public function updateAllReferredIds($referredIds, $id)
    {
        $arrayReferredIds = explode(',', $referredIds);
        foreach ($arrayReferredIds as $key => $referredId) {
            $this->updateReferredId($referredId, $id);
        }
    }

    public function editServiceReferredIds($requestReferredIds, $referredServiceIds, $id)
    {
        
        $requestReferredServiceIds = explode(',', $requestReferredIds);
        $serviceReferredIds = explode(',', $referredServiceIds);

        if(empty($referredServiceIds)){

            foreach ($requestReferredServiceIds as $key => $referredId){

                $this->updateServiceReferredIds($referredId, $id);
            }
        }else{
            $value = array_merge(array_diff($serviceReferredIds, $requestReferredServiceIds), array_diff($requestReferredServiceIds, $serviceReferredIds));
            foreach ($value as $key => $referredId)
            {
                if (in_array($referredId, $serviceReferredIds)) {
                    $this->updateReferredId($referredId, $id);
                } else {
                    $this->updateServiceReferredIds($referredId, $id);
                }
            }
        }

    }

    public function updateReferredId($referredId, $serviceId)
    {
        $getReferredId = Services::select('referred_service_id')->where('id', $referredId)->first();

        $getReferredIdArray = explode(',', $getReferredId->referred_service_id);
        $arrayServiceId = explode(',', $serviceId);
        $value = array_diff($getReferredIdArray, $arrayServiceId);

        $data = [
            'referred_service_id' => implode(',', array_values($value))
        ];
        Services::where('id', $referredId)->update($data);
    }

    public function delete($id)
    {
        Services::where('id', $id)->delete();

        $customData = new CustomDataController();
        $customData->deleteCustomFieldsRecords('service', $id);

        $response = [
            'message' => Lang::get('lang.service_deleted_successfully')
        ];
        return response()->json($response, 200);
    }

    public function serviceSetting(Request $request, $id)
    {
        $setting = ['allow_cancel' => $request->allow_cancel,
            'auto_confirm' => $request->auto_confirm,
            'activation' => $request->activation,
            'allow_booking_max_day_ago' => $request->allow_booking_max_day_ago,
            'consider_children_for_price' => $request->consider_children_for_price,
            'age_range' => $request->age_range,
            'percentage' => $request->percentage
        ];

        $alias = str_replace(' ', '-', strtolower($request->alias));
        $countAlias = Services::where('alias', $alias)->where('id', '!=', $id)->count();
        $service = Services::find($id);

        if ($countAlias == 0) {
            $setting['alias'] = $alias;

            $response = [
                'message' => Lang::get('lang.successfully_updated'),
                'Service' => $service
            ];

        } else {

            $response = [
                'message' => Lang::get('lang.successfully_updated') . ' ' . Lang::get('lang.and_external_link_already_exists'),
                'Service' => $service
            ];
        }

        $service->update($setting);

        return response()->json($response, 201);
    }
}
