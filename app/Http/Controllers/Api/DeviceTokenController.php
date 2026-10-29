<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string|max:255',
            'platform' => 'nullable|string|max:20',
        ]);

        $deviceToken = DeviceToken::updateOrCreate(
            ['token' => $validated['token']],
            [
                'user_id' => $request->user()->id,
                'platform' => $validated['platform'] ?? 'android',
            ],
        );

        return response()->json([
            'message' => 'Token perangkat disimpan.',
            'id' => $deviceToken->id,
        ]);
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string|max:255',
        ]);

        DeviceToken::where('token', $validated['token'])
            ->where('user_id', $request->user()->id)
            ->delete();

        return response()->json(['message' => 'Token perangkat dihapus.']);
    }
}
