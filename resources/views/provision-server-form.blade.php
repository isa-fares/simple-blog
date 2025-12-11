<!DOCTYPE html>
<html>
<head>
    <title>Provision Server</title>
</head>
<body>
    <h1>Provision a New Server</h1>

    <form action="/provision-server" method="POST">
        @csrf
        <label for="server_name">Server Name:</label>
        <input type="text" id="server_name" name="server_name" required>
        <button type="submit">Provision</button>
    </form>

    @if(session('message'))
        <p style="color: green;">{{ session('message') }}</p>
    @endif
</body>
</html>
