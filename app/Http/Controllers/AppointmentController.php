<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Salon;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function renderForm(): Response
    {
        return Inertia::render('Beauty');
    }

    public function getSalons(): JsonResponse
    {
        return \response()->json(['success' => true, 'salons' => Salon::all()]);
    }

    public function getServices(Request $request): JsonResponse
    {
        return \response()->json(
            [
                'success' => true,
                'services' => Service::where('salon_id', $request->input('salonId'))
                                    ->where('is_enabled', 1)
                                    ->get()
            ]
        );
    }

    public function getAppointments(Request $request)
    {
        $appointments = DB::table('appointments')
                ->whereYear('date', '=', $request->input('year'))
                ->whereMonth('date', '=', $request->input('month'))
                ->whereDay('date', '=', $request->input('day'))
                ->get();

        return \response()->json(
            [
                'success' => true,
                'appointments' => $appointments
            ]
        );
    }

    public function makeRecord(Request $request)
    {
        $hour = substr($request->input('hour'), 0, 2);
        $minutes = substr($request->input('hour'), 3, 2);
        $date = Carbon::parse(mktime(
            intval($hour),
            intval($minutes),
            0,
            intval($request->input('month')),
            intval($request->input('day')),
            intval($request->input('year'))
        ));

        $appointment = Appointment::create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'date' => $date,
            'service_id' => intval($request->input('service'))
        ]);
        $appointment->save();

        return \response()->json([
            'success' => true,
            'mins' => $minutes
        ]);
    }
}
