<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Status</title>
</head>
<body>
    @if (! $user)
        <p>No user is currently logged in.</p>
        <p><a href="{{ route('login') }}">Go to the login page</a></p>
    @else
        <p>This user is: {{ $user->name }} (ID: {{ $user->id }})</p>

        <form action="{{ route('logout') }}" method="POST" style="margin-top:10px;">
            @csrf
            <button type="submit">Logout</button>
        </form>
    @endif
</body>
</html>
