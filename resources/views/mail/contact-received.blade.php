@extends('mail.layout')
@php($t = \App\Support\MailTheme::T)
@php($home = url('/'))

@section('title', 'Got your message')
@section('preheader', "Thanks for reaching out. I've got your message and will get back to you shortly.")

@section('content')
    {{-- Headline --}}
    <tr>
        <td class="px" style="padding:40px 40px 0;">
            <div style="font-family:{{ $t['mono'] }};font-size:11px;letter-spacing:0.16em;text-transform:uppercase;color:{{ $t['acid'] }};">
                &#9679;&nbsp; Message received
            </div>
            <h1 class="h1" style="margin:16px 0 0;font-family:{{ $t['sans'] }};font-size:42px;line-height:1.02;font-weight:500;letter-spacing:-0.035em;color:{{ $t['fg'] }};">
                Thanks, {{ $firstName }}.<br>
                <span style="font-family:{{ $t['serif'] }};font-style:italic;font-weight:400;letter-spacing:-0.01em;color:{{ $t['acid'] }};">It landed safely.</span>
            </h1>
            <p style="margin:22px 0 0;font-family:{{ $t['sans'] }};font-size:15px;line-height:1.65;color:{{ $t['mute'] }};">
                Your message is in my inbox and I read every one myself. I usually reply
                <span style="color:{{ $t['fg'] }};">within a day</span>, so expect to hear from me shortly.
                If it's urgent, just reply to this email.
            </p>
        </td>
    </tr>

    {{-- Their message, echoed back --}}
    <tr>
        <td class="px" style="padding:30px 40px 0;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:{{ $t['ink'] }};border:1px solid {{ $t['line'] }};border-radius:14px;">
                <tr>
                    <td style="padding:20px 22px;">
                        <div style="font-family:{{ $t['mono'] }};font-size:10px;letter-spacing:0.16em;text-transform:uppercase;color:{{ $t['dim'] }};">
                            What you sent{{ $contact->company ? ' · '.$contact->company : '' }}
                        </div>
                        <div style="margin-top:10px;font-family:{{ $t['sans'] }};font-size:14px;line-height:1.65;color:{{ $t['fg'] }};">{!! nl2br(e(\Illuminate\Support\Str::limit($contact->message, 1200))) !!}</div>
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
                    <td style="border-radius:999px;background:{{ $t['acid'] }};">
                        <a href="{{ $home }}#work" style="display:inline-block;padding:12px 22px;font-family:{{ $t['sans'] }};font-size:14px;font-weight:500;color:{{ $t['ink'] }};text-decoration:none;border-radius:999px;">See selected work &rarr;</a>
                    </td>
                    <td style="width:10px;">&nbsp;</td>
                    <td style="border-radius:999px;border:1px solid {{ $t['line2'] }};">
                        <a href="{{ route('resume') }}" style="display:inline-block;padding:11px 20px;font-family:{{ $t['sans'] }};font-size:14px;color:{{ $t['fg'] }};text-decoration:none;border-radius:999px;">R&eacute;sum&eacute; &darr;</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Sign-off --}}
    <tr>
        <td class="px" style="padding:34px 40px 34px;">
            <p style="margin:0;font-family:{{ $t['sans'] }};font-size:15px;line-height:1.6;color:{{ $t['fg'] }};">Talk soon,</p>
            <p style="margin:2px 0 0;font-family:{{ $t['serif'] }};font-style:italic;font-size:24px;color:{{ $t['fg'] }};">{{ $profile['name'] }}</p>
        </td>
    </tr>
@endsection

@section('footer')
    <div style="font-family:{{ $t['mono'] }};font-size:11px;line-height:1.9;color:{{ $t['mute'] }};">
        @foreach ($profile['links'] as $link)
            <a href="{{ $link['url'] }}" style="color:{{ $t['fg'] }};text-decoration:none;">{{ $link['label'] }}</a>&nbsp;&nbsp;<span style="color:{{ $t['dim'] }};">&middot;</span>&nbsp;&nbsp;
        @endforeach
        <a href="{{ $home }}" style="color:{{ $t['acid'] }};text-decoration:none;">{{ parse_url($home, PHP_URL_HOST) }}</a>
    </div>
    <div style="margin-top:10px;font-family:{{ $t['sans'] }};font-size:11.5px;line-height:1.6;color:{{ $t['dim'] }};">
        You're getting this because you used the contact form on my portfolio. No mailing list, just this one reply.
    </div>
@endsection
