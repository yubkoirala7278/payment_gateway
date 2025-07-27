<!DOCTYPE html>
<html>

<head>
    <title>eSewa Payment</title>
</head>

<body>
    <form action="{{ env('ESEWA_API_ENDPOINT') }}" method="POST" id="esewa-form">
        @foreach ($data as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        <button type="submit">Pay with eSewa</button>
    </form>
</body>

</html>
