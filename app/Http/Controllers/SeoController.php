<?php

namespace App\Http\Controllers;

use App\Support\PortfolioContent;
use Illuminate\Http\Response;

/**
 * sitemap.xml, generated so every URL follows APP_URL.
 */
class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $updated = (PortfolioContent::override()?->updated_at ?? now())->toAtomString();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n"
            .'  <url><loc>'.e(url('/')).'</loc><lastmod>'.$updated.'</lastmod><changefreq>monthly</changefreq><priority>1.0</priority></url>'."\n"
            .'</urlset>'."\n";

        return response($xml)->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
