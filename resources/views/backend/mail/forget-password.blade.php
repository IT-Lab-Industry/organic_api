<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
</head>
<body>
    <p>Token Is: <a href="{{ env('FRONT_APP_URL') }}/user/reset-password/{{ $data['token'] }}">{{ $data['token'] }}</a></p>
</body>
</html>