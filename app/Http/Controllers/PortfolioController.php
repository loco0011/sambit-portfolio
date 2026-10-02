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
        $home = url('/');
        [$given, $family] = array_pad(explode(' ', $p['name'], 2), 2, '');

        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'WebSite',
                    '@id' => $home.'#website',
                    'url' => $home,
                    'name' => $p['name'],
                    'alternateName' => preg_replace('#^https?://#', '', $home),
                    'inLanguage' => 'en',
                    'publisher' => ['@id' => $home.'#person'],
                ],
                [
                    '@type' => 'ProfilePage',
                    '@id' => $home.'#profile',
                    'url' => $home,
                    'name' => $portfolio['seo']['title'] ?? $p['name'].' — '.$p['role'],
                    'description' => $portfolio['seo']['description'] ?? $p['headline'],
                    'isPartOf' => ['@id' => $home.'#website'],
                    'mainEntity' => ['@id' => $home.'#person'],
                ],
                [
                    '@type' => 'Person',
                    '@id' => $home.'#person',
                    'name' => $p['name'],
                    'givenName' => $given,
                    'familyName' => $family,
                    'url' => $home,
                    'image' => asset('og-image.png'),
                    'description' => $p['headline'],
                    'jobTitle' => $p['role'],
                    'email' => 'mailto:'.$p['email'],
                    'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'Kolkata', 'addressRegion' => 'West Bengal', 'addressCountry' => 'IN'],
                    'worksFor' => ['@type' => 'Organization', 'name' => $p['current']['company']],
                    'alumniOf' => ['@type' => 'CollegeOrUniversity', 'name' => $portfolio['education']['school']],
                    'knowsAbout' => $portfolio['stack'],
                    'sameAs' => array_column($p['links'], 'url'),
                ],
            ],
        ];
    }

    public function caseStudy(string $slug): View
    {
        $portfolio = PortfolioContent::get();
        $all = PortfolioContent::caseStudies($portfolio);
        abort_unless(isset($all[$slug]), 404);

        $project = $all[$slug];
        $url = route('work.show', $slug);
        $home = url('/');

        return view('case-study', [
            'portfolio' => $portfolio,
            'project' => $project,
            'others' => array_values(array_diff_key($all, [$slug => true])),
            'schema' => [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'BreadcrumbList',
                        'itemListElement' => [
                            ['@type' => 'ListItem', 'position' => 1, 'name' => $portfolio['profile']['name'], 'item' => $home],
                            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Work', 'item' => $home.'/#work'],
                            ['@type' => 'ListItem', 'position' => 3, 'name' => $project['name'], 'item' => $url],
                        ],
                    ],
                    [
                        '@type' => 'CreativeWork',
                        '@id' => $url.'#work',
                        'name' => $project['name'],
                        'headline' => $project['name'].' — '.$project['kind'],
                        'description' => $project['blurb'],
                        'url' => $url,
                        'keywords' => implode(', ', $project['stack'] ?? []),
                        'author' => ['@type' => 'Person', '@id' => $home.'#person', 'name' => $portfolio['profile']['name'], 'url' => $home],
                        'isPartOf' => ['@id' => $home.'#website'],
                    ],
                ],
            ],
        ]);
    }

    public function resume(): StreamedResponse
    {
        abort_unless(Storage::disk('local')->exists('resume.pdf'), 404);

        return Storage::disk('local')->download('resume.pdf', 'Sambit_Maity_Full_Stack_Software_Engineer_Resume.pdf');
    }
}
