<?php
session_start();
require('./app/Http/Conrtoller/Controller.php');

if (isset($_COOKIE["id"]) && isset($_COOKIE["key"])) {
    $id = $_COOKIE["id"];
    $key = $_COOKIE["key"];
    $user = getDatas("SELECT * FROM users WHERE id = '$id'")[0];

    if ($user && $key === hash("sha224", $user["email"])) {
        $_SESSION["login"] = true;
        $_SESSION["auth"] = $user;
    }
}

if (isset($_SESSION["login"])) {
    if (isset($isAdmin)) {
        header("Location: http://localhost/web-rpl/resources/views/dashboard/");
        exit;
    } elseif (isset($isCustomer)) {
        header("Location: http://localhost/web-rpl/resources/views/beranda/");
        exit;
    } else {
        header("Location: http://localhost/web-rpl/");
        exit;
    }
}

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $user = getDatas("SELECT * FROM users WHERE email = '$email'")[0];

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["login"] = true;
        $_SESSION['user'] = $user;

        if (isset($_POST["remember"])) {
            setcookie("id", $user["id"], time() + 30000);
            setcookie("key", hash("sha224", $user["email"]), time() + 30000);
        }

        $isAdmin = getDatas("SELECT
            admins.id AS admin_id,
            users.id AS user_id,
            users.name,
            users.email,
            users.email_verified_at,
            admins.no_telp AS admin_no_telp,
            admins.created_at AS admin_created_at,
            admins.updated_at AS admin_updated_at
            FROM
            admins
            JOIN
            users ON admins.user_id = {$user['id']}
        ")[0];

        $isCustomer = getDatas("SELECT
            customers.id AS customer_id,
            users.id AS user_id,
            users.name,
            users.email,
            users.email_verified_at,
            customers.alamat,
            customers.no_telp AS customer_no_telp,
            customers.created_at AS customer_created_at,
            customers.updated_at AS customer_updated_at
            FROM
            customers
            JOIN
            users ON customers.user_id = {$user['id']}
        ")[0];

        if (isset($isAdmin)) {
            header("Location: http://localhost/web-rpl/resources/views/dashboard/");
            exit;
        } elseif (isset($isCustomer)) {
            header("Location: http://localhost/web-rpl/resources/views/beranda/");
            exit;
        } else {
            header("Location: http://localhost/web-rpl/");
            exit;
        }
    } else {
        header("Location: http://localhost/web-rpl/");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./src/css/login.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
  <title>Login</title>
</head>

<body>
  <section>
    <form action="" method="post">
      <div class="riyot">
        <div class="logo">
          <img src="./public/assets/image/logoPT.png" alt="" />
        </div>
        <div class="logcre">
          <h2>Login</h2>
        </div>
        <div class="form">
          <div class="user">
            <input type="email" name="email" id="email"  placeholder="Email" value="" autofocus/>
          </div>
          <div class="pas">
            <input type="password" name="password" id="password" placeholder="Password" value="password"/>
          </div>
        </div>
        <div class="remfor">
          <div class="rimem">
            <input id="chek" type="checkbox" name="remember" id="remember"/>
            <label for="chek">Remember me</label>
          </div>
          <div class="forpas">
            <a href="#">Forgot my password</a>
          </div>
        </div>
        <div class="conti">
          <button type="submit" name="login">Login in now</button>
        </div>
      </div>
    </form>
  </section>
</body>

</html>