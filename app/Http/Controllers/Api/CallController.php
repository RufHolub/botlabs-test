<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\CallModel;
use App\Models\Lead;
use App\Services\LeadService;

class CallController extends Controller
{
	public function store(Request $request, Lead $lead)
	{
		$validated = $request->validate([
			'duration' => ['required', 'integer', 'between:0,65535'],
			'result' => ['required', Rule::in([CallModel::RESULT_NO_ANSWER, CallModel::RESULT_CALLBACK_LATER, CallModel::RESULT_SUCCESS])],
		]);

		$call = $leadService->addCall($lead, $validated);

		return response()->json($call, 201);
	}
}
