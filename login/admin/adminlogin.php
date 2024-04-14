<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        *,
        body {
            background-color: #202124;
            color: #bdc1c6ba;
        }

        .form-control {
            background-color: #3131318e;
            color: #bdc1c6ba;
            border: none;
        }

        input[type="text"]:focus {
            border-color: #343434;
            box-shadow: 0 0 5px #25252580;
            background-color: #3131318e;
            color: #bdc1c6ba;
        }
    </style>

</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Admin Login</h2>
        <form action="adminauthenticate.php" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>
</html>