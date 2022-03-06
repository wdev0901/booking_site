<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class HolidayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $start = date('Y-m-d', strtotime(str_replace(['.', '/'], '-', $this->start_date)));
        $end = date('Y-m-d', strtotime(str_replace(['.', '/'], '-', $this->end_date)));

        return [
            'id' => $this->id,
            'title' => $this->title,
            'start' => $start,
            'end' => Carbon::parse($end)->addDay(1)->format('Y-m-d'),
            'color' => '#26c6da',
            'textColor' => '#fff',
        ];
    }
}
