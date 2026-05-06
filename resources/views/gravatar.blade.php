<!DOCTYPE html>
<html>

<head>
    <title>Laravel 12 Gravatar</title>

    <style>
        body {
            font-family: Arial;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
            transition: 0.3s;
        }

        .container {
            width: 500px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            transition: 0.3s;
            margin: 80px auto;
            position: relative;
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
            cursor: pointer;
        }

        .delete-btn {
            background: red;
            padding: 6px 10px;
            width: auto;
        }

        .avatar {
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }

        .avatar img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
        }

        .left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dark-toggle {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #6366f1;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            transition: 0.3s;
        }

        .dark-toggle:hover {
            transform: scale(1.1);
        }

        .dark {
            background: #111;
            color: white;
        }

        .dark .container {
            background: #1f2937;
        }

        .dark input {
            background: #333;
            color: white;
            border: 1px solid #555;
        }

        .dark .avatar {
            border-bottom: 1px solid #444;
        }

        .message-success {
            color: green;
        }

        .message-error {
            color: red;
        }

        .search-box {
            margin-bottom: 15px;
        }
    </style>

</head>

<body>

<div class="container">

    <!-- Dark Mode -->
    <button class="dark-toggle" onclick="toggleDark()">🌙</button>

    <h2>Laravel 12 Gravatar Generator</h2>

    <!-- Messages -->
    @if(session('success'))
        <p class="message-success">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p class="message-error">{{ session('error') }}</p>
    @endif

    <!--  SEARCH BAR -->
    <form method="GET" action="/" class="search-box">
        <input 
            type="text" 
            name="search" 
            placeholder="Search by email..." 
            value="{{ request('search') }}"
        >
    </form>

    <!-- Search Info -->
    @if(request('search'))
        <p>Showing results for: <b>{{ request('search') }}</b></p>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('generate.avatar') }}">
        @csrf
        <input type="email" name="email" placeholder="Enter Email" required>
        <button>Generate Avatar</button>
    </form>

    <hr style="margin:20px 0;">

    <h3>Saved Avatars</h3>

    <!-- No Results -->
    @if($avatars->isEmpty())
        <p>No avatars found.</p>
    @endif

    @foreach($avatars as $avatar)

        <div class="avatar">

            <div class="left">
                <img src="{{ $avatar->avatar }}">
                <b>{{ $avatar->email }}</b>
            </div>

            <form method="POST" action="{{ route('delete.avatar', $avatar->id) }}">
                @csrf
                @method('DELETE')
                <button class="delete-btn">Delete</button>
            </form>

        </div>

    @endforeach

</div>

<script>
function toggleDark() {
    document.body.classList.toggle('dark');

    let btn = document.querySelector('.dark-toggle');

    if(document.body.classList.contains('dark')) {
        btn.innerHTML = '☀️';
    } else {
        btn.innerHTML = '🌙';
    }
}
</script>

</body>
</html>