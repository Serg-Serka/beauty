<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Salon;
use App\Models\Service;
use Illuminate\Http\Request;

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
//                'salonId' => $request->input('salonId'),
                'services' => Service::where('salon_id', $request->input('salonId'))->get()
            ]
        );
    }
}
