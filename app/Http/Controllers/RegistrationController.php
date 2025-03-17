<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'children' => 'required|array|max:4',
            'children.*.age' => 'integer|min:0|max:12',
            'children.*.month' => 'integer|min:0|max:12',
            'reservation_datetime' => 'required|date|after:6 hours|before:30 days',
        ]);

        $validated['booking_no'] = Str::upper(Str::random(8));

        $registration = Registration::create($validated);
        return response()->json(['booking_no' => $registration->booking_no], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $registration = Registration::find($id);
        if (!$registration) {
            return response()->json(['message' => 'Registration not found'], 404);
        }
        return response()->json($registration);
    }

    /**
     * Display the specified resource by phone number.
     *
     * @param  string  $phone_number
     * @return \Illuminate\Http\Response
     */
    public function showBySearchTerm($searchTerm)
    {
        $registration = Registration::where(function ($query) use ($searchTerm) {
            $query->where('phone_number', $searchTerm)
                  ->orWhere('booking_no', $searchTerm);
        })->get();

        if ($registration->isEmpty()) {
            return response()->json(['message' => 'Registration not found'], 404);
        }

        return response()->json($registration);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $registrations = Registration::all();
        return response()->json($registrations);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $registration = Registration::find($id);
        if (!$registration) {
            return response()->json(['message' => 'Registration not found'], 404);
        }

        $validated = $request->validate([
            'customer_name' => 'sometimes|required|string',
            'phone_number' => 'sometimes|required|string',
            'address' => 'sometimes|required|string',
            'children.*.age' => 'integer|min:0|max:12',
            'children.*.month' => 'integer|min:0|max:12',
            'reservation_datetime' => 'sometimes|required|date|after:6 hours|before:30 days',
        ]);

        $registration->update($validated);
        return response()->json($registration);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $registration = Registration::find($id);
        if (!$registration) {
            return response()->json(['message' => 'Registration not found'], 404);
        }
        $registration->delete();
        return response()->json(['message' => 'Registration deleted successfully']);
    }
}
