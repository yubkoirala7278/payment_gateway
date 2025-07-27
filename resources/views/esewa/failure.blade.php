<!DOCTYPE html>
<html>
<head>
    <title>Payment Failed</title>
</head>
<body>
    <h1>Payment Failed</h1>
    <p>{{ session('message') }}</p>
    <a href="{{ route('esewa.initiate') }}">Try Again</a>
</body>
</html>