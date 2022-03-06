<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $uploads = '/uploads/profiles';

    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'phone_object', 'password', 'gender', 'cellphone', 'avatar', 'date_of_birth', 'verified', 'role_id','disabled', 'token', 'is_admin', 'notification_check', 'verification_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['full_name'];


    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function AauthAcessToken()
    {
        return $this->hasMany('\App\OauthAccessToken');
    }

    public function getFileAttribute($avatar)
    {
        return $this->uploads . $avatar;
    }

    public static function getServiceUserlist()
    {
        return User::select('id', 'first_name', 'last_name', 'role_id', 'is_admin')
              ->Where('users.role_id', '!=', 0)
              ->orWhere('users.is_admin', 1)->get();
    }

    public static function getUserLists($request)
    {
        $searchValue = $request->searchValue;


        if (!empty($searchValue)) {
            $query = User::select('users.id as id', 'users.first_name', 'users.last_name', 'users.email', 'roles.title as role_title', 'users.is_admin', 'users.disabled')
                ->where('roles.title', 'LIKE', '%' . $searchValue . '%')
                ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                ->where('title', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('first_name', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('last_name', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('email', 'LIKE', '%' . $searchValue . '%');
        } else {
            $query = User::select('users.id as id', 'users.first_name', 'users.last_name', 'users.email', 'roles.title as role_title', 'users.is_admin', 'users.disabled')
                ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                ->orWhere('users.role_id', '!=', 0)
                ->orWhere('users.is_admin', 1);
        }



        if (empty($requestType)) {
            $count = $query->get()->count();
            $allData = $query->get();
            return ['datarows' => $allData, 'count' => $count];
        } else {
            return $query->orderBy($request->columnKey, $request->columnSortedBy)->get();
        }
    }

    public static function userWithRole($id)
    {
        $user = User::leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.id', $id)
            ->select('users.id as id', 'users.first_name', 'users.last_name', 'users.email', 'roles.title as role_title', 'users.is_admin', 'users.role_id', 'disabled')
            ->first();

        return $user;

    }


    public static function getAllClients($request)
    {

        $searchValue = $request->searchValue;

        $query =  User::leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->where('role_id', '=', 0)
            ->where('is_admin', '=', 0)
            ->select('users.id as id', 'users.first_name', 'users.last_name', 'users.email', 'users.phone');

         if (!empty($searchValue)) {
             $query->where(function ($query) use ($searchValue) {
                 $query->where('users.first_name', 'LIKE', '%' . $searchValue . '%')
                     ->orWhere('users.last_name', 'LIKE', '%' . $searchValue . '%')
                     ->orWhere('users.phone', 'LIKE', '%' . $searchValue . '%');
             });
         }

        if (empty($requestType)) {

            $count = $query->get()->count();
            $allData = $query->get();
            //$data = $query->orderBy($request->columnKey, $request->columnSortedBy)->take($request->rowLimit)->skip($request->rowOffset)->get();

            return ['datarows' => $allData, 'count' => $count];
        } else {
            return $query->orderBy($request->columnKey, $request->columnSortedBy)->get();
        }
    }

    public static function getClientBookingList($request, $userId){

        $filtersData = $request->filtersData;

        $query =  User::where('users.id', '=', $userId)
            ->join('bookings', 'bookings.user_id', '=', 'users.id')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->join('payments', 'payments.booking_id', '=', 'bookings.id')
            ->select('users.first_name', 'users.last_name', 'users.email', 'users.phone', 'services.title', 'bookings.id as id', 'bookings.status', 'bookings.booking_date', 'bookings.booking_time', 'bookings.booking_bill', 'bookings.quantity', 'payments.paid_amount');


        //DropDown Filter For Department
        if (!empty($filtersData)) {
            foreach ($filtersData as $singleFilter) {
                if (array_key_exists('key', $singleFilter) && $singleFilter['key'] == "status" && $singleFilter['value'] == 1) {
                    $query->where('bookings.status', 'confirmed');

                }
                else if (array_key_exists('key', $singleFilter) && $singleFilter['key'] == "status" && $singleFilter['value'] == 2) {
                    $query->where('bookings.status', 'pending');

                }
                else if (array_key_exists('key', $singleFilter) && $singleFilter['key'] == "status" && $singleFilter['value'] == 3) {
                    $query->where('bookings.status', 'canceled');

                }
                else if (array_key_exists('filterKey', $singleFilter) && $singleFilter['filterKey'] == "date_range") {
                    $query->whereBetween('bookings.booking_date', [$singleFilter['value'][0]['start'], $singleFilter['value'][0]['end']]);
                }
            }
        }

        if (empty($requestType)) {

            $count = $query->get()->count();
            $allData = $query->get();
            //$data = $query->orderBy($request->columnKey, $request->columnSortedBy)->take($request->rowLimit)->skip($request->rowOffset)->get();

            //return ['datarows' => $allData, 'count' => $count];
            return ['data' => $allData, 'count' => $count];
        } else {
            return $query->orderBy($request->columnKey, $request->columnSortedBy)->get();
        }

    }


    public static function getuserBookingList($request, $userId)
    {

        $filtersData = $request->filtersData;

        $query =  User::where('users.id', '=', $userId)
            ->join('bookings', 'bookings.user_id', '=', 'users.id')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->join('payments', 'payments.booking_id', '=', 'bookings.id')
            ->select('users.first_name', 'users.last_name', 'users.email', 'users.phone', 'services.title', 'bookings.id as id', 'bookings.status', 'bookings.booking_date', 'bookings.booking_time', 'bookings.booking_bill', 'bookings.quantity', 'payments.paid_amount');

        //DropDown Filter For Department
        if (!empty($filtersData)) {
            foreach ($filtersData as $singleFilter) {
                if (array_key_exists('key', $singleFilter) && $singleFilter['key'] == "status" && $singleFilter['value'] == 1) {
                    $query->where('bookings.status', 'confirmed');

                }
                else if (array_key_exists('key', $singleFilter) && $singleFilter['key'] == "status" && $singleFilter['value'] == 2) {
                    $query->where('bookings.status', 'pending');

                }
                else if (array_key_exists('key', $singleFilter) && $singleFilter['key'] == "status" && $singleFilter['value'] == 3) {
                    $query->where('bookings.status', 'canceled');

                }
                else if (array_key_exists('filterKey', $singleFilter) && $singleFilter['filterKey'] == "date_range") {
                    $query->whereBetween('bookings.booking_date', [$singleFilter['value'][0]['start'], $singleFilter['value'][0]['end']]);
                }
            }
        }

        if (empty($requestType)) {

            $count = $query->get()->count();
            $allData = $query->get();
            //$data = $query->orderBy($request->columnKey, $request->columnSortedBy)->take($request->rowLimit)->skip($request->rowOffset)->get();

            //return ['datarows' => $allData, 'count' => $count];
            return ['data' => $allData, 'count' => $count];
        } else {
            return $query->orderBy($request->columnKey, $request->columnSortedBy)->get();
        }

    }
}
