<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\CallModel;
use Illuminate\Support\Facades\Auth;

class LeadService
{
	public function addCall(Lead $lead, array $data): CallModel
	{
		$leadStatus = $this->resolveStatusBeforeCall($lead, $data['result']);

		$managerId = $lead->manager_id ?? Auth::id();

		$call = $this->createCall($lead, $data);

		$leadStatus = $this->applyNoAnswerRule($lead, $leadStatus);

		$lead->update([
			'status' => $leadStatus,
			'manager_id' => $managerId,
		]);

		return $call;
	}

	private function createCall(Lead $lead, array $data): CallModel
	{
		return CallModel::create([
			'lead_id' => $lead->id,
			'duration' => $data['duration'],
			'result' => $data['result'],
		]);
	}

	private function resolveStatusBeforeCall(Lead $lead, string $result): string
	{
		$status = $lead->status;

		if ($result === CallModel::RESULT_SUCCESS) {
			$status = Lead::STATUS_WON;
		} else if (!$lead->calls()->exists() && $lead->status === Lead::STATUS_NEW) {
			$status = Lead::STATUS_IN_PROGRESS;
		}

		return $status;
	}

	private function applyNoAnswerRule(Lead $lead, string $currentStatus): string
	{
		if ($currentStatus === Lead::STATUS_WON) {
			return $currentStatus;
		}

		$isNoAnswer = $lead->calls()
			->latest()
			->limit(3)
			->where('result', CallModel::RESULT_NO_ANSWER)
			->count() === 3;

		if ($isNoAnswer) {
			return Lead::STATUS_LOST;
		}

		return $currentStatus;
	}
}