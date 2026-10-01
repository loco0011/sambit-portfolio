<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\PortfolioContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function show(): JsonResponse
    {
        return $this->state();
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'content' => ['required', 'array'],
            'content.profile' => ['required', 'array'],
            'content.profile.name' => ['required', 'string', 'max:120'],
            'content.profile.email' => ['required', 'email'],
            'content.experience' => ['array'],
            'content.projects' => ['array'],
        ]);

        // Not validate()'s return value: that keeps only the keys that have rules,
        // which would strip every other profile field.
        PortfolioContent::save($request->input('content'));

        return $this->state();
    }

    public function availability(Request $request): JsonResponse
    {
        $request->validate(['available' => ['required', 'boolean']]);

        $content = PortfolioContent::get();
        $content['profile']['available'] = $request->boolean('available');
        PortfolioContent::save($content);

        return response()->json(['available' => $content['profile']['available']]);
    }

    public function destroy(): JsonResponse
    {
        PortfolioContent::reset();

        return $this->state();
    }

    private function state(): JsonResponse
    {
        $override = PortfolioContent::override();

        return response()->json([
            'content' => PortfolioContent::get(),
            'customized' => (bool) $override,
            'updated_at' => $override?->updated_at,
        ]);
    }
}
