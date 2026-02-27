<?php

namespace App\Http\Controllers;

use App\Ai\Agents\SalesAssistan;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function callAgent(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required']
        ]);

        $response = (new SalesAssistan(auth()->user()))->prompt($validated['message']);

        return (string) $response;
    }
}

