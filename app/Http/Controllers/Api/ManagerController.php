<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager;
use App\Models\Lead;

class ManagerController extends Controller
{
	public function store(Request $request)
	{
		
		$validated = $request->validate([
            'name' => ['required', 'string'],
        ]);
		
		
		$manager = Manager::create($validated);

        return response()->json($manager, 201);
	}
	
    public function leads(Manager $manager)
	{
		$manager->load([
			'leads' => function ($query) {
				$query->withCount('calls')->withSum('calls', 'duration');
			}
		]);

		$leads = $manager->leads->makeHidden([
			'phone',
		]);

		return response()->json($leads);
	}
}
