@php
    // Site palette (dark theme), inlined because email clients ignore stylesheets.
    $ink = '#07070a'; $card = '#0c0c10'; $fg = '#ededef'; $mute = '#8b8b95'; $dim = '#55555e';
    $line = '#1c1c22'; $acid = '#d4ff4f';
    $sans = "'Geist', -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif";
    $mono = "'Geist Mono', ui-monospace, SFMono-Regular, Menlo, Consolas, monospace";
    $serif = "'Instrument Serif', Georgia, 'Times New Roman', serif";
    $home = url('/');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark">
    <meta name="supported-color-schemes" content="dark">
    <title>Got your message</title>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500&family=Geist+Mono&family=Instrument+Serif:ital@1&display=swap" rel="stylesheet">
    <style>
        :root { color-scheme: dark; supported-color-schemes: dark; }
        body { margin: 0; padding: 0; background: {{ $ink }}; }
        a { color: {{ $acid }}; }
        @media (max-width: 600px) {
            .px { padding-left: 22px !important; padding-right: 22px !important; }
            .h1 { font-size: 34px !important; }
        }
    </style>
</head>
<body style="margin:0;padding:0;background:{{ $ink }};">
    {{-- Inbox preview line --}}
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;">Thanks for reaching out. I've got your message and will get back to you shortly.</div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:{{ $ink }};">
        <tr>
            <td align="center" style="padding:40px 12px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:560px;background:{{ $card }};border:1px solid {{ $line }};border-radius:20px;overflow:hidden;">
                    {{-- Lime progress bar, like the site nav --}}
                    <tr><td style="height:3px;line-height:3px;font-size:0;background:{{ $acid }};">&nbsp;</td></tr>

                    {{-- Brand --}}
                    <tr>
                        <td class="px" style="padding:30px 40px 0;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="padding-right:12px;">
                                        <img src="{{ asset('icon-192.png') }}" width="40" height="40" alt="SM" style="display:block;border:0;border-radius:10px;">
                                    </td>
                                    <td>
                                        <div style="font-family:{{ $sans }};font-size:14px;font-weight:500;color:{{ $fg }};">{{ $profile['name'] }}</div>
                                        <div style="font-family:{{ $sans }};font-size:12px;color:{{ $mute }};">{{ $profile['role'] }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Headline --}}
                    <tr>
                        <td class="px" style="padding:40px 40px 0;">
                            <div style="font-family:{{ $mono }};font-size:11px;letter-spacing:0.16em;text-transform:uppercase;color:{{ $acid }};">
                                &#9679;&nbsp; Message received
                            </div>
                            <h1 class="h1" style="margin:16px 0 0;font-family:{{ $sans }};font-size:42px;line-height:1.02;font-weight:500;letter-spacing:-0.035em;color:{{ $fg }};">
                                Thanks, {{ $firstName }}.<br>
                                <span style="font-family:{{ $serif }};font-style:italic;font-weight:400;letter-spacing:-0.01em;color:{{ $acid }};">It landed safely.</span>
                            </h1>
                            <p style="margin:22px 0 0;font-family:{{ $sans }};font-size:15px;line-height:1.65;color:{{ $mute }};">
                                Your message is in my inbox and I read every one myself. I usually reply
                                <span style="color:{{ $fg }};">within a day</span>, so expect to hear from me shortly.
                                If it's urgent, just reply to this email.
                            </p>
                        </td>
                    </tr>

                    {{-- Their message, echoed back --}}
                    <tr>
                        <td class="px" style="padding:30px 40px 0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:{{ $ink }};border:1px solid {{ $line }};border-radius:14px;">
                                <tr>
                                    <td style="padding:20px 22px;">
                                        <div style="font-family:{{ $mono }};font-size:10px;letter-spacing:0.16em;text-transform:uppercase;color:{{ $dim }};">
                                            What you sent{{ $contact->company ? ' · '.$contact->company : '' }}
                                        </div>
                                        <div style="margin-top:10px;font-family:{{ $sans }};font-size:14px;line-height:1.65;color:{{ $fg }};">{!! nl2br(e(\Illuminate\Support\Str::limit($contact->message, 1200))) !!}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Actions --}}
                    <tr>
                        <td class="px" style="padding:30px 40px 0;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="border-radius:999px;background:{{ $acid }};">
                                        <a href="{{ $home }}#work" style="display:inline-block;padding:12px 22px;font-family:{{ $sans }};font-size:14px;font-weight:500;color:{{ $ink }};text-decoration:none;border-radius:999px;">See selected work &rarr;</a>
                                    </td>
                                    <td style="width:10px;">&nbsp;</td>
                                    <td style="border-radius:999px;border:1px solid #2a2a31;">
                                        <a href="{{ route('resume') }}" style="display:inline-block;padding:11px 20px;font-family:{{ $sans }};font-size:14px;color:{{ $fg }};text-decoration:none;border-radius:999px;">R&eacute;sum&eacute; &darr;</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Sign-off --}}
                    <tr>
                        <td class="px" style="padding:34px 40px 34px;">
                            <p style="margin:0;font-family:{{ $sans }};font-size:15px;line-height:1.6;color:{{ $fg }};">Talk soon,</p>
                            <p style="margin:2px 0 0;font-family:{{ $serif }};font-style:italic;font-size:24px;color:{{ $fg }};">{{ $profile['name'] }}</p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td class="px" style="padding:20px 40px 26px;border-top:1px solid {{ $line }};">
                            <div style="font-family:{{ $mono }};font-size:11px;line-height:1.9;color:{{ $mute }};">
                                @foreach ($profile['links'] as $link)
                                    <a href="{{ $link['url'] }}" style="color:{{ $fg }};text-decoration:none;">{{ $link['label'] }}</a>&nbsp;&nbsp;<span style="color:{{ $dim }};">&middot;</span>&nbsp;&nbsp;
                                @endforeach
                                <a href="{{ $home }}" style="color:{{ $acid }};text-decoration:none;">{{ parse_url($home, PHP_URL_HOST) }}</a>
                            </div>
                            <div style="margin-top:10px;font-family:{{ $sans }};font-size:11.5px;line-height:1.6;color:{{ $dim }};">
                                You're getting this because you used the contact form on my portfolio. No mailing list, just this one reply.
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
