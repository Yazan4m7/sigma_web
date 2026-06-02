<?php

namespace App\Http\Controllers;

use App\Services\TableWidthPreferences;
use Illuminate\Http\Request;

class UserPreferencesController extends Controller
{
    public function tableWidthsIndex(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['data' => [], 'defaults' => []]);
        }

        return response()->json([
            'data' => TableWidthPreferences::getForUser(auth()->id()),
            'defaults' => TableWidthPreferences::getDefaults(),
        ]);
    }

    public function tableWidthsStore(Request $request)
    {
        $request->validate([
            'scope' => 'required|string|max:190',
            'widths' => 'nullable|array',
        ]);

        $widths = $request->input('widths', []);
        if (!is_array($widths)) {
            $widths = [];
        }

        $saved = TableWidthPreferences::save(auth()->id(), $request->input('scope'), $widths);

        return response()->json(['ok' => $saved]);
    }

    public function tableWidthsReset(Request $request)
    {
        $scope = $request->input('scope');
        $deleted = TableWidthPreferences::delete(auth()->id(), $scope);

        return response()->json(['deleted' => $deleted]);
    }
}
