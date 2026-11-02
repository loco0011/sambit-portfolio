<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use MaxMind\Db\Reader;
use RuntimeException;
use Throwable;

/**
 * Approximate location and network of an IP, from DB-IP Lite databases stored on this
 * server (CC BY 4.0), so visitor IPs are never sent to a third party.
 */
class GeoIp
{
    private const DATABASES = ['city' => 'dbip-city-lite', 'asn' => 'dbip-asn-lite'];

    /** @return array{country?: string, country_code?: string, region?: string, city?: string, org?: string} */
    public function lookup(string $ip): array
    {
        $place = $this->read('city', $ip);

        return array_filter([
            'country' => $place['country']['names']['en'] ?? null,
            'country_code' => $place['country']['iso_code'] ?? null,
            'region' => $place['subdivisions'][0]['names']['en'] ?? null,
            'city' => $place['city']['names']['en'] ?? null,
            'org' => $this->read('asn', $ip)['autonomous_system_organization'] ?? null,
        ]);
    }

    /** Fetches this month's databases; skipped when they are already current. Returns the number updated. */
    public function update(): int
    {
        $updated = 0;

        foreach (self::DATABASES as $kind => $name) {
            $path = $this->path($kind);

            if (is_file($path) && date('Y-m', filemtime($path)) === date('Y-m')) {
                continue;
            }

            $this->download($name, $path);
            $updated++;
        }

        return $updated;
    }

    private function read(string $kind, string $ip): ?array
    {
        $path = $this->path($kind);

        if (! is_file($path)) {
            return null;
        }

        try {
            $reader = new Reader($path);
            $record = $reader->get($ip);
            $reader->close();

            return is_array($record) ? $record : null;
        } catch (Throwable) {
            return null;
        }
    }

    private function path(string $kind): string
    {
        return Storage::disk('local')->path("geoip/$kind.mmdb");
    }

    private function download(string $name, string $path): void
    {
        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $gz = "$path.gz";
        $tmp = "$path.tmp";

        try {
            // This month's file isn't published on the very first days of a month.
            foreach ([date('Y-m'), date('Y-m', strtotime('first day of last month'))] as $month) {
                $response = Http::timeout(600)->sink($gz)->get("https://download.db-ip.com/free/$name-$month.mmdb.gz");

                if ($response->successful()) {
                    break;
                }
            }

            if (! $response->successful()) {
                throw new RuntimeException("Could not download $name (HTTP {$response->status()}).");
            }

            $in = gzopen($gz, 'rb');
            $out = fopen($tmp, 'wb');
            while (! gzeof($in)) {
                fwrite($out, gzread($in, 1 << 20));
            }
            gzclose($in);
            fclose($out);

            // Refuse a truncated or corrupt file instead of replacing a working one.
            (new Reader($tmp))->close();
            rename($tmp, $path);
        } finally {
            @unlink($gz);
            @unlink($tmp);
        }
    }
}
