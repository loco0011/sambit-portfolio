<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PortfolioController extends Controller
{
    public function index(): View
    {
        return view('app', ['portfolio' => config('portfolio')]);
    }

    public function resume(): StreamedResponse
    {
        abort_unless(Storage::disk('local')->exists('resume.pdf'), 404);

        return Storage::disk('local')->download('resume.pdf', 'Sambit_Maity_Resume.pdf');
    }
}
