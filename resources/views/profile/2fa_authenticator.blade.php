<p>Scan QR Code with Google Authenticator:</p>
{!! $qr !!}

<form method="POST" action="/2fa/authenticator/verify">
    @csrf
    <input type="text" name="token" placeholder="Enter 6-digit code" required>
    <button type="submit">Enable Authenticator</button>
</form>
