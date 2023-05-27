<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Http\Resources\PatientResource;
use App\Http\Resources\PatientResourceCollection;
use App\Models\Patient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;

class PatientController extends Controller
{
    const CACHE_TTL_SECONDS = 5 * 60;
    const CACHE_ID = 'Patients';

    /**
     * Display a listing of the resource.
     */
    public function index(): PatientResourceCollection
    {
        return PatientResourceCollection::make(
            Cache::remember(
                self::CACHE_ID,
                self::CACHE_TTL_SECONDS,
                fn() => Patient::all()
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientRequest $request): PatientResource
    {
        $patient = new Patient();
        $patient->first_name = $request->first_name;
        $patient->last_name = $request->last_name;
        $patient->birthday = $request->birthday;
        $patient->save();

        Queue::fake();
        Queue::push('processNewPatient', $patient, 'newPatientQueue');

        return PatientResource::make(
            Cache::remember(
                self::CACHE_ID . $patient->id,
                self::CACHE_TTL_SECONDS,
                fn() => $patient
            )
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient): PatientResource
    {
        return PatientResource::make(
            Cache::remember(
                self::CACHE_ID . $patient->id,
                self::CACHE_TTL_SECONDS,
                fn() => $patient
            )
        );
    }

}
