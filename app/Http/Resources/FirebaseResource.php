<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FirebaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    protected $database;
    public function __construct()
    {
        $this->database = app('firebase.firestore');
    }

    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return[
            'id'=> $this->id,
            'name'=> $this->name,
            'nim'=> $this->nim,
            'angkatan'=> $this->angkatan,
        ];
    }
}                                                                                                                                