<!DOCTYPE html>
<html>

<head>
    <title>Laravel 12 Gravatar</title>

    <style>
        body {
            font-family: Arial;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            padding-top: 50px;
        }

        .container {
            width: 500px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            margin-top: 10px;
            padding: 10px;
            background: #6366f1;
            color: white;
            border: none;
            border-radius: 5px;
            width: 100%;
        }

        .avatar {
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }

        .avatar img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
        }
    </style>

</head>

<body>

    <div class="container">

        <h2>Laravel 12 Gravatar Generator</h2>

        <form method="POST" action="{{ route('generate.avatar') }}">
            @csrf

            <input type="email" name="email" placeholder="Enter Email">

            <button>Generate Avatar</button>

        </form>

        <hr style="margin:20px 0;">

        <h3>Saved Avatars</h3>

        @foreach($avatars as $avatar)

            <div class="avatar">

                <img src="{{ $avatar->avatar }}">

                <div>

                    <b>{{ $avatar->email }}</b>

                </div>

            </div>

        @endforeach

    </div>

</body>

</html>