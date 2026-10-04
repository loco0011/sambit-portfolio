New message from your portfolio · {!! $receivedAt !!}

{!! $contact->name !!} <{!! $contact->email !!}>
@if ($contact->company)
{!! $contact->company !!}
@endif

{!! $contact->message !!}

@foreach ($contact->details as $label => $value)
{!! str_pad($label.':', 12) !!} {!! $value !!}
@endforeach

Open in inbox: {!! $inboxUrl !!}
