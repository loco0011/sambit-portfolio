<?php

namespace App\Http\Controllers;

use App\Support\PortfolioContent;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PortfolioController extends Controller
{
    public function index(): View
    {
        $portfolio = PortfolioContent::get();

        return view('app', ['portfolio' => $portfolio, 'schema' => $this->schema($portfolio)]);
    }

    // Structured data for search engines. Built here, not in Blade: '@context' would be compiled as a Blade directive.
    private function schema(array $portfolio): array
    {
        $p = $portfolio['profile'];

        return [
            '@context' => 'https://schema.org',
            '@type' => 'ProfilePage',
            'url' => url('/'),
            'name' => $p['name'].' — '.$p['role'],
            'description' => $p['headline'],
            'mainEntity' => [
                '@type' => 'Person',
                '@id' => url('/').'#person',
                'name' => $p['name'],
                'url' => url('/'),
                'image' => asset('og-image.png'),
                'description' => $p['headline'],
                'jobTitle' => $p['role'],
                'email' => 'mailto:'.$p['email'],
                'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'Kolkata', 'addressCountry' => 'IN'],
                'worksFor' => ['@type' => 'Organization', 'name' => $p['current']['company']],
                'alumniOf' => ['@type' => 'CollegeOrUniversity', 'name' => $portfolio['education']['school']],
                'knowsAbout' => $portfolio['stack'],
                'sameAs' => array_column($p['links'], 'url'),
            ],
        ];
    }

    public function resume(): StreamedResponse
    {
        abort_unless(Storage::disk('local')->exists('resume.pdf'), 404);

        return Storage::disk('local')->download('resume.pdf', 'Sambit_Maity_Full_Stack_Software_Engineer_Resume.pdf');
    }
}
