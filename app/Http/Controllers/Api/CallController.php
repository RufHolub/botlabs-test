<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\CallModel;
use App\Models\Lead;

class CallController extends Controller
{
    public function store(Request $request, Lead $lead)
	{
		$validated = $request->validate([
            'duration' => ['required', 'integer', 'between:0,65535'],
            'result' => ['required', Rule::in([CallModel::RESULT_NO_ANSWER, CallModel::RESULT_CALLBACK_LATER, CallModel::RESULT_SUCCESS])],
        ]);
		
		$leadStatus = $lead->status;
		$managerId = $lead->manager_id ?? Auth::id();
		
		//Change lead status
		if ($validated['result'] === CallModel::RESULT_SUCCESS) {
			$leadStatus = Lead::STATUS_WON;
		} else if (!$lead->calls()->exists() && $lead->status === Lead::STATUS_NEW) {
			$leadStatus = Lead::STATUS_IN_PROGRESS;
		}
		
		//Create new call
		$call = CallModel::create([
			'lead_id' => $lead->id,
			'duration' => $validated['duration'],
			'result' => $validated['result'],
		]);
		
		//Change lead status when no answer x3
		if ($leadStatus !== Lead::STATUS_WON) {
			$isNoAnswer = $lead->calls()
				->latest()
				->limit(3)
				->where('result', CallModel::RESULT_NO_ANSWER)
				->count() === 3;
			
			if ($isNoAnswer) {
				$leadStatus = Lead::STATUS_LOST;
			}
		}
		
		//Update lead info
		$lead->update([
			'status' => $leadStatus,
			'manager_id' => $managerId,
		]);

        return response()->json($call, 201);
	}
}
