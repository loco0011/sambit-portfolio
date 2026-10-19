{{-- How a project's parts connect, drawn from its config `diagram` (node positions are percentages). --}}
@php
    $w = 400; $h = 250;
    $boxes = [];
    foreach ($diagram['nodes'] as $n) {
        $bw = (int) round(mb_strlen($n['label']) * 6.6 + 24);
        $boxes[$n['id']] = [
            'label' => $n['label'],
            'core' => ! empty($n['core']),
            'w' => $bw,
            'x' => max($bw / 2 + 6, min($w - $bw / 2 - 6, $n['x'] / 100 * $w)),
            'y' => max(22, min($h - 22, $n['y'] / 100 * $h)),
        ];
    }
    // Nudge apart boxes on the same row that would overlap once their labels are measured
    $ids = array_keys($boxes);
    for ($pass = 0; $pass < 3; $pass++) {
        foreach ($ids as $a) {
            foreach ($ids as $b) {
                [$l, $r] = $boxes[$a]['x'] <= $boxes[$b]['x'] ? [$a, $b] : [$b, $a];
                if ($a >= $b || abs($boxes[$l]['y'] - $boxes[$r]['y']) >= 34) continue;
                $gap = ($boxes[$l]['w'] + $boxes[$r]['w']) / 2 + 10 - ($boxes[$r]['x'] - $boxes[$l]['x']);
                if ($gap <= 0) continue;
                $boxes[$l]['x'] = max($boxes[$l]['w'] / 2 + 6, $boxes[$l]['x'] - $gap / 2);
                $boxes[$r]['x'] = min($w - $boxes[$r]['w'] / 2 - 6, $boxes[$l]['x'] + ($boxes[$l]['w'] + $boxes[$r]['w']) / 2 + 10);
            }
        }
    }
@endphp
<svg class="arch" viewBox="0 0 {{ $w }} {{ $h }}" role="img" aria-label="How {{ $name }} is put together: {{ implode(', ', array_column($boxes, 'label')) }}">
    @foreach ($diagram['edges'] as [$from, $to])
        @continue(! isset($boxes[$from], $boxes[$to]))
        <line x1="{{ $boxes[$from]['x'] }}" y1="{{ $boxes[$from]['y'] }}" x2="{{ $boxes[$to]['x'] }}" y2="{{ $boxes[$to]['y'] }}" />
    @endforeach
    @foreach ($boxes as $b)
        <g @class(['core' => $b['core']])>
            <rect x="{{ $b['x'] - $b['w'] / 2 }}" y="{{ $b['y'] - 15 }}" width="{{ $b['w'] }}" height="30" rx="7" />
            <text x="{{ $b['x'] }}" y="{{ $b['y'] + 4 }}">{{ $b['label'] }}</text>
        </g>
    @endforeach
</svg>
