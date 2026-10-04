Thanks, {!! $firstName !!}. It landed safely.

Your message is in my inbox and I read every one myself. I usually reply within a day, so expect to hear from me shortly. If it's urgent, just reply to this email.

What you sent:
{!! \Illuminate\Support\Str::limit($contact->message, 1200) !!}

See selected work: {!! url('/') !!}#work
Résumé: {!! route('resume') !!}

Talk soon,
{!! $profile['name'] !!}
@foreach ($profile['links'] as $link)
{!! $link['label'] !!}: {!! $link['url'] !!}
@endforeach
