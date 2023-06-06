<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Enums\AppointmentStatus;

class AppointmentController extends Controller
{
    public function index() {
        $statusMap = [
            AppointmentStatus::SCHEDULED => 'Scheduled',
            AppointmentStatus::CONFIRMED => 'Confirmed',
            AppointmentStatus::CANCELLED => 'Cancelled',
        ];
        
        return Appointment::query()
                ->with('client:id,first_name,last_name')
                // ->when(request('status'), function ($query){
                //     return $query->where('status', AppointmentStatus::getStatusName(request('status')));
                // })
                ->when(request('status'), function ($query) use ($statusMap) {
                    $selectedStatus = request('status');
                    if (isset($statusMap[$selectedStatus])) {
                        return $query->where('status', $selectedStatus);
                    }
                    return $query;
                })
                ->latest()
                ->paginate()
                ->through(fn($appointment)=>[
                    'id' => $appointment->id,
                    'start_time' => $appointment->start_time->format('Y-m-d h:i A'),
                    'end_time' => $appointment->end_time->format('Y-m-d h:i A'),
                    'status' => [
                        'name' => AppointmentStatus::getStatusName($appointment->status),
                        'color' => $appointment->status_color,
                    ],
                    'client' => $appointment->client,
                ]);
    }

    public function store(){
        $validated = request()->validate([
            'client_id' => 'required',
            'title' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'required'
        ],[
            'client_id.required' =>  'The client name field is required'
        ]);
        Appointment::create([
            'title' => $validated['title'],
            'client_id' => $validated['client_id'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'description' => $validated['description'],
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        return response()->json(['message' => 'success']);
    }

    public function edit(Appointment $appointment) {
        return $appointment;
    }

    public function update(Appointment $appointment){
        $validated = request()->validate([
            'client_id' => 'required',
            'title' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'required'
        ],[
            'client_id.required' =>  'The client name field is required'
        ]);
        $appointment->update($validated);
        return response()->json(['success' => true]);
    }

    public function destroy(Appointment $appointment) {
        $appointment->delete();
        return response()->json(['success' => true], 200);
    }
}
