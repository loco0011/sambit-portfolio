@extends('mail.layout')
@php($t = \App\Support\MailTheme::T)
@php($first = strtok(trim($contact->name), ' ') ?: $contact->name)
@php($reply = 'mailto:'.$contact->email.'?subject='.rawurlencode('Re: your message on my portfolio'))

@section('title', 'New message from '.$contact->name)
@section('preheader', \Illuminate\Support\Str::limit($contact->message, 120))
@section('brandline', 'Portfolio inbox')
@section('stamp', $contact->id ? '#'.str_pad($contact->id, 4, '0', STR_PAD_LEFT) : '')

@section('content')
    {{-- Who --}}
    <tr>
        <td class="px" style="padding:40px 40px 0;">
            <div style="font-family:{{ $t['mono'] }};font-size:11px;letter-spacing:0.16em;text-transform:uppercase;color:{{ $t['acid'] }};">
                &#9679;&nbsp; New message <span style="color:{{ $t['dim'] }};">&nbsp;&middot;&nbsp; {{ $receivedAt }}</span>
            </div>
            <h1 class="h1" style="margin:16px 0 0;font-family:{{ $t['sans'] }};font-size:42px;line-height:1.02;font-weight:500;letter-spacing:-0.035em;color:{{ $t['fg'] }};">
                {{ $contact->name }}
            </h1>
            <div style="margin-top:12px;font-family:{{ $t['sans'] }};font-size:15px;line-height:1.5;">
                <a href="mailto:{{ $contact->email }}" style="color:{{ $t['acid'] }};text-decoration:none;">{{ $contact->email }}</a>
                @if ($contact->company)
                    <span style="color:{{ $t['dim'] }};">&nbsp;&middot;&nbsp;</span><span style="font-family:{{ $t['serif'] }};font-style:italic;font-size:18px;color:{{ $t['mute'] }};">{{ $contact->company }}</span>
                @endif
            </div>
        </td>
    </tr>

    {{-- The message --}}
    <tr>
        <td class="px" style="padding:28px 40px 0;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:{{ $t['ink'] }};border:1px solid {{ $t['line'] }};border-left:2px solid {{ $t['acid'] }};border-radius:14px;">
                <tr>
                    <td style="padding:22px 24px;">
                        <div style="font-family:{{ $t['mono'] }};font-size:10px;letter-spacing:0.16em;text-transform:uppercase;color:{{ $t['dim'] }};">Message</div>
                        <div style="margin-top:10px;font-family:{{ $t['sans'] }};font-size:15px;line-height:1.7;color:{{ $t['fg'] }};">{!! nl2br(e($contact->message)) !!}</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Actions --}}
    <tr>
        <td class="px" style="padding:26px 40px 0;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="stack" style="border-radius:999px;background:{{ $t['acid'] }};">
                        <a href="{{ $reply }}" style="display:inline-block;padding:12px 22px;font-family:{{ $t['sans'] }};font-size:14px;font-weight:500;color:{{ $t['ink'] }};text-decoration:none;border-radius:999px;">Reply to {{ $first }} &rarr;</a>
                    </td>
                    <td class="stack-gap" style="width:10px;">&nbsp;</td>
                    <td class="stack" style="border-radius:999px;border:1px solid {{ $t['line2'] }};">
                        <a href="{{ $inboxUrl }}" style="display:inline-block;padding:11px 20px;font-family:{{ $t['sans'] }};font-size:14px;color:{{ $t['fg'] }};text-decoration:none;border-radius:999px;">Open in inbox</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- About the sender --}}
    @if ($contact->details)
        <tr>
            <td class="px" style="padding:34px 40px 34px;">
                <div style="font-family:{{ $t['mono'] }};font-size:10px;letter-spacing:0.16em;text-transform:uppercase;color:{{ $t['dim'] }};">About the sender</div>
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:12px;">
                    @foreach ($contact->details as $label => $value)
                        <tr>
                            <td style="width:120px;padding:9px 12px 9px 0;border-top:1px solid {{ $t['line'] }};font-family:{{ $t['mono'] }};font-size:11px;letter-spacing:0.04em;color:{{ $t['mute'] }};vertical-align:top;">{{ $label }}</td>
                            <td style="padding:9px 0;border-top:1px solid {{ $t['line'] }};font-family:{{ $t['sans'] }};font-size:13.5px;line-height:1.5;color:{{ $t['fg'] }};">{{ $value }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    @else
        <tr><td style="height:34px;line-height:34px;font-size:0;">&nbsp;</td></tr>
    @endif
@endsection

@section('footer')
    <div style="font-family:{{ $t['sans'] }};font-size:11.5px;line-height:1.6;color:{{ $t['dim'] }};">
        Sent from the contact form on <a href="{{ url('/') }}" style="color:{{ $t['mute'] }};text-decoration:none;">{{ parse_url(url('/'), PHP_URL_HOST) }}</a>.
        Hitting reply answers {{ $first }} directly.
    </div>
@endsection
