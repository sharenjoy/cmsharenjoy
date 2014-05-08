<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h1>Reset your password</h1>
    <p>Hello, {{ $username }}</p>

    <p>This is your password: <b>{{$password}}</b></p>

    <p>We suggest you <a href="{{url($urlSegment.'/resetpassword/'.$id.'/'.$code)}}">reset your password</a></p>

    <p>Admin</p>
</body>
</html>