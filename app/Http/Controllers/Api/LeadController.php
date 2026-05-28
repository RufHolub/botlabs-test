<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use Illuminate\Validation\Rule;

class LeadController extends Controller
{
	public function store(Request $request)
	{
		$validated = $request->validate([
            'name' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'manager_id' => ['nullable', 'exists:managers,id'],
        ]);
		
		$lead = Lead::create([
			'name' => $validated['name'],
			'phone' => $validated['phone'],
			'status' => Lead::STATUS_NEW,
			'manager_id' => $validated['manager_id'] ?? null,
		]);

        return response()->json($lead, 201);
	}
}
