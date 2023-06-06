<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\AppointmentStatus;
use App\Models\Appointment;

class AppointmentStatusController extends Controller
{
    
    public function getStatusWithCount() {
        // $cases = AppointmentStatus::cases();
        // return collect($cases)->map(function($status){
        //     return [
        //         'name' => $status->name,
        //         'value' => $status->value,
        //         'count' => 4,
        //         'color' => AppointmentStatus::from($status->value)->color(),
        //     ];
        // });

        // $cases = AppointmentStatus::cases();

        // return collect($cases)->map(function ($status) {
        //     return [
        //         'name' => AppointmentStatus::getStatusName($status->value),
        //         'value' => $status->value,
        //         'count' => 4,
        //         'color' => AppointmentStatus::color($status->value),
        //     ];
        // });

        $statusValues = AppointmentStatus::getStatusValues();

        return collect($statusValues)->map(function ($statusValue) {
            return [
                'name' => AppointmentStatus::getStatusName($statusValue),
                'value' => $statusValue,
                'count' => Appointment::where('status', $statusValue)->count(),
                'color' => AppointmentStatus::color($statusValue),
            ];
        });
    }
}
