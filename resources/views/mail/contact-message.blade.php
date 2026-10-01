New message from your portfolio

Name:    {{ $contact->name }}
Email:   {{ $contact->email }}
Company: {{ $contact->company ?: '-' }}
IP:      {{ $contact->ip }}

{{ $contact->message }}
