<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Enums\AppointmentStatus;

class DashboardStatController extends Controller
{
    public function appointments(){
        $totalAppointmentsCount = Appointment::query()
                    ->when(request('status') === 'scheduled', function($query){
                        $query->where('status', AppointmentStatus::SCHEDULED);
                    })
                    ->when(request('status') === 'confirmed', function($query){
                        $query->where('status', AppointmentStatus::CONFIRMED);
                    })
                    ->when(request('status') === 'cancelled', function($query){
                        $query->where('status', AppointmentStatus::CANCELLED);
                    })
                    ->count();
        return response()->json([
            'totalAppointmentsCount' => $totalAppointmentsCount,
        ]);
    }
}
