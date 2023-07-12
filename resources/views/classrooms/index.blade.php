
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Classrooms</title>
</head>
<body>

    <h1>My Classroom</h1>
    <p>Welcome {{$name}}, <?= $title ?></p>
    <a href="{{ route('classrooms.show',1 )}}">Create</a>
</body>
</html>
