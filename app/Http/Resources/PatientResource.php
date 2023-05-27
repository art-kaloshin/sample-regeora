<?php

namespace App\Http\Resources;

use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $first_name
 * @property string $last_name
 * @property Carbon $birthday
 * @property int $age
 * @property int $age_type
 * @property int $id
 */
class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'birthday' => $this->birthday->format('d.m.Y'),
            'age' => $this->age . ' ' . Patient::AGE_STRING[$this->age_type]
        ];
    }
}
