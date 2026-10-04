{{-- Shared shell for portfolio emails: the site's dark card, lime top bar and brand row.
     Child views get the palette as $t from App\Support\MailTheme. --}}
@php($t = \App\Support\MailTheme::T)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark">
    <meta name="supported-color-schemes" content="dark">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500&family=Geist+Mono&family=Instrument+Serif:ital@1&display=swap" rel="stylesheet">
    <style>
        :root { color-scheme: dark; supported-color-schemes: dark; }
        body { margin: 0; padding: 0; background: {{ $t['ink'] }}; }
        a { color: {{ $t['acid'] }}; }
        @media (max-width: 600px) {
            .px { padding-left: 22px !important; padding-right: 22px !important; }
            .h1 { font-size: 34px !important; }
            .stack { display: block !important; width: 100% !important; }
            .stack-gap { height: 10px !important; display: block !important; }
        }
    </style>
</head>
<body style="margin:0;padding:0;background:{{ $t['ink'] }};">
    {{-- Inbox preview line --}}
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;">@yield('preheader')</div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:{{ $t['ink'] }};">
        <tr>
            <td align="center" style="padding:40px 12px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:560px;background:{{ $t['card'] }};border:1px solid {{ $t['line'] }};border-radius:20px;overflow:hidden;">
                    {{-- Lime progress bar, like the site nav --}}
                    <tr><td style="height:3px;line-height:3px;font-size:0;background:{{ $t['acid'] }};">&nbsp;</td></tr>

                    {{-- Brand --}}
                    <tr>
                        <td class="px" style="padding:30px 40px 0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="width:52px;">
                                        <img src="{{ asset('icon-192.png') }}" width="40" height="40" alt="SM" style="display:block;border:0;border-radius:10px;">
                                    </td>
                                    <td>
                                        <div style="font-family:{{ $t['sans'] }};font-size:14px;font-weight:500;color:{{ $t['fg'] }};">{{ $profile['name'] }}</div>
                                        <div style="font-family:{{ $t['sans'] }};font-size:12px;color:{{ $t['mute'] }};">@yield('brandline', $profile['role'])</div>
                                    </td>
                                    <td align="right" style="font-family:{{ $t['mono'] }};font-size:10.5px;letter-spacing:0.08em;color:{{ $t['dim'] }};">@yield('stamp')</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    @yield('content')

                    {{-- Footer --}}
                    <tr>
                        <td class="px" style="padding:20px 40px 26px;border-top:1px solid {{ $t['line'] }};">
                            @yield('footer')
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
