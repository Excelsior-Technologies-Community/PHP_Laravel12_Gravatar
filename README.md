# PHP_Laravel12_Gravatar


## Project Description

PHP_Laravel12_Gravatar is a simple Laravel 12 application that demonstrates how to generate Gravatar profile images from email addresses and store them in a database.

The application allows users to enter an email address, automatically generate the corresponding Gravatar avatar using an MD5 hash, and display the avatar on the page. Each generated avatar is also saved in the database and displayed in a list for reference.

This project is useful for learning how to integrate Gravatar services in Laravel applications, work with controllers, models, migrations, and Blade views, and manage user data using Eloquent ORM.


## Features

- Generate Gravatar avatar from email address

- Convert email to MD5 hash for Gravatar API

- Display Gravatar profile image dynamically

- Store generated avatars in the database

- List previously generated avatars

- Clean and responsive user interface

- Simple Laravel MVC architecture

- Uses Eloquent ORM for database operations


## Technologies Used

- PHP 8+

- Laravel 12

- MySQL Database

- Blade Template Engine

- Gravatar API

- HTML5

- CSS3

- Composer


---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Gravatar "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Gravatar

```

#### Explanation:

This command installs a fresh Laravel 12 application and creates the project folder.

The cd command moves into the newly created project directory.




## STEP 2: Database Setup 

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_Gravatar
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_gravatar

```

### Then Run:

```
php artisan migrate

```


#### Explanation:

This step connects Laravel with the MySQL database.

The migration command creates the default Laravel tables in the database.




## STEP 3: Install Gravatar Package 

### Install the package:

```
composer require creativeorange/gravatar

```

#### Explanation

This package helps generate Gravatar profile images from email addresses easily inside Laravel.





## STEP 4: Publish Configuration (Optional)

### Run:

```
php artisan vendor:publish

```

### It will create

```
config/gravatar.php

```

### Example config:

```
return [

    'default' => 'mp',

    'size' => 80,

    'rating' => 'g',

    'secure' => true,

];

```

#### Explanation

This step publishes the Gravatar configuration file, allowing you to customize avatar size, default image, and rating settings.




## STEP 5: Create Model + Migration

### Run command:

```
php artisan make:model Gravatar -m

```

### This creates:

```
app/Models/Gravatar.php
database/migrations/xxxx_create_gravatars_table.php

```


### Open migration file: database/migrations/xxxx_create_gravatars_table.php

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gravatars', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('avatar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gravatars');
    }
};


```


### Then Run:

```
php artisan migrate

```


### Open File: app/Models/Gravatar.php

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gravatar extends Model
{
    protected $fillable = [
        'email',
        'avatar'
    ];
}

```

#### Explanation

This command creates a Model for database interaction and a Migration file to create the gravatars table.

The migration creates a database table to store email addresses and their corresponding Gravatar URLs.






## STEP 6: Create Controller

### Run:

```
php artisan make:controller GravatarController

```

### File: app/Http/Controllers/GravatarController.php

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gravatar;

class GravatarController extends Controller
{

    public function index()
    {
        $avatars = Gravatar::latest()->get();
        return view('gravatar', compact('avatars'));
    }

    public function generate(Request $request)
    {
        $email = strtolower(trim($request->email));

        $hash = md5($email);

        $avatar = "https://www.gravatar.com/avatar/".$hash."?s=200&d=identicon";

        Gravatar::create([
            'email'=>$email,
            'avatar'=>$avatar
        ]);

        return redirect('/');
    }
}

```

#### Explanation

The controller handles the application logic, such as receiving the email, generating the Gravatar URL, and saving it to the database.






## STEP 7: Create Routes

### File: routes/web.php

```
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GravatarController;

Route::get('/', [GravatarController::class,'index']);

Route::post('/generate',[GravatarController::class,'generate'])->name('generate.avatar');

```

#### Explanation

Routes define URLs that users can access and connect them to controller methods.





## STEP 8: Create View

### Create file: resources/views/gravatar.blade.php

```
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

```

#### Explanation

This Blade template provides the user interface, including the email input form and the list of generated avatars.





## STEP 9: Run Project

### Run:

```
php artisan serve

```

### Open

```
http://127.0.0.1:8000

```

#### Explanation

This command starts Laravel's local development server, allowing you to access the application in your browser.




## Expected Output:


### Main Page:


<img width="1919" height="936" alt="Screenshot 2026-03-05 165535" src="https://github.com/user-attachments/assets/52246c94-62c1-40eb-bb6b-80cd58ee8b7f" />


### Generated Avatar:


<img width="1919" height="947" alt="Screenshot 2026-03-05 173923" src="https://github.com/user-attachments/assets/e8e97d1f-7426-45b8-baa4-91e5068c5c1d" />


---

# Project Folder Structure:

```
PHP_Laravel12_Gravatar
│
├── app
│   ├── Http
│   │   └── Controllers
│   │       └── GravatarController.php
│   │
│   ├── Models
│   │   └── Gravatar.php
│
├── database
│   └── migrations
│       └── xxxx_create_gravatars_table.php
│
├── resources
│   └── views
│       └── gravatar.blade.php
│
├── routes
│   └── web.php
│
├── .env
├── artisan
├── composer.json

```
