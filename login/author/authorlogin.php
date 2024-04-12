<!DOCTYPE html>
<html>
<head>
    <title>Author Login</title>
</head>
<body>
    <h2>Author Login</h2>
    <form action="authorauthenticate.php" method="post">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username">
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password">
        </div>
        <div>
            <button type="submit">Login</button>
        </div>
    </form>
</body>
</html>