<?php
require(__DIR__ . "/env.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['signup'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $errorMessage = "";
        // Valider que la chaîne est une adresse e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = "L'adresse e-mail n'est pas une adresse mail valide.";
        }
        // Valider le mot de passe (au moins 4 caractères)
        elseif (strlen($password) < 4) {
            $errorMessage = "Le mot de passe doit contenir au moins 4 caractères";
        } else {
            // Hachage du mot de passe
            $password_hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);

            // Requête SQL pour insérer un nouvel utilisateur
            $query = "INSERT INTO users (email, password) VALUES (:email, :password)";

            try {
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password_hash);
                $stmt->execute();

                // Authentification réussie, enregistrez l'ID et le nom de l'utilisateur en session
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['user_email'] = $email;

                // Redirigez l'utilisateur vers "index.php"
                header("Location: index.php");
                exit;
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }
        }
    }
}

// Fermer la connexion
$pdo = null;
?>

<!DOCTYPE html>
<html>

<head>
    <?php require(__DIR__ . "/head.html"); ?>
</head>

<body>
<?php 
    if ($errorMessage) {
        echo "
            <div class=\"alert alert-warning text-center\" role=\"alert\">
                ⚠️".$errorMessage."
            </div>
        ";
    }
?>
<h1 class="title">🌭 Nourriture Terrestre 🍔</h1>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-2"></div>
            <div class="col-lg-6 col-md-8 login-box">
                <div class="col-lg-12 login-key">
                    <i class="fa fa-key" aria-hidden="true"></i>
                </div>
                <div class="col-lg-12 login-title">
                    🚨L'inscription est résérvée aux utilisateurs avec une adresse mail: "@mydsomanager"🚨
                </div>

                <div class="col-lg-12 login-form">
                    <div class="col-lg-12 login-form">
                        <form method="POST">
                            <input type="hidden" name="signup" value="1">
                            <div class="form-group w-50">
                                <label class="form-control-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="form-group w-50">
                                <label class="form-control-label">Mot de passe (4 caractères minimum)</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>

                            <div class="col-lg-12 loginbttm">
                                <div class="col-lg-6 login-btm login-text">
                                    <!-- Error Message -->
                                </div>
                                <div class="col-lg-12 login-btm login-button">
                                    <button type="submit" class="btn btn-outline-primary">S'inscrire</button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
                <div class="col-lg-3 col-md-2"></div>
            </div>
            <div class="row text-center">
                <div class="col-12">
                    <p class="my-3" style="color: white;">
                        Déjà inscrit sur Nourriture Terrestre ?
                    </p>
                    <a href="login.php" style="text-decoration: none; color: white;">👉 Connecte toi
                        ici 👈</a>
                </div>
            </div>
        </div>
</body>

</html>

<style>
    body {
        background: #222D32;
        font-family: 'Roboto', sans-serif;
    }

    .login-box {
        margin-top: 25px;
        height: auto;
        background: #1A2226;
        text-align: center;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
    }

    .login-key {
        height: 50px;
        font-size: 80px;
        line-height: 100px;
        background: -webkit-linear-gradient(#27EF9F, #0DB8DE);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .title {
        margin-top: 42px;
        text-align: center;
        font-size: 35px;
        letter-spacing: 3px;
        font-weight: bold;
        color: #ECF0F5;
    }

    .login-title {
        text-align: center;
        color: #ECF0F5;
    }

    .login-form {
        margin-top: 25px;
        text-align: left;
    }

    input[type=text] {
        background-color: #1A2226;
        border: none;
        border-bottom: 2px solid #0DB8DE;
        border-top: 0px;
        border-radius: 0px;
        font-weight: bold;
        outline: 0;
        margin-bottom: 20px;
        padding-left: 5px;
        color: #ECF0F5;
    }

    input[type=password] {
        background-color: #1A2226;
        border: none;
        border-bottom: 2px solid #0DB8DE;
        border-top: 0px;
        border-radius: 0px;
        font-weight: bold;
        outline: 0;
        padding-left: 5px;
        margin-bottom: 20px;
        color: #ECF0F5;
    }

    .form-group {
        margin-bottom: 40px;
        outline: 0px;
        margin-left: auto;
        margin-right: auto;
    }

    .form-control:focus {
        border-color: inherit;
        -webkit-box-shadow: none;
        box-shadow: none;
        border-bottom: 2px solid #0DB8DE;
        outline: 0;
        background-color: #1A2226;
        color: #ECF0F5;
    }

    input:focus {
        outline: none;
        box-shadow: 0 0 0;
    }

    label {
        margin-bottom: 0px;
    }

    .form-control-label {
        font-size: 10px;
        color: #6C6C6C;
        font-weight: bold;
        letter-spacing: 1px;
    }

    .btn-outline-primary {
        border-color: #0DB8DE;
        color: #0DB8DE;
        border-radius: 0px;
        font-weight: bold;
        letter-spacing: 1px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
    }

    .btn-outline-primary:hover {
        background-color: #0DB8DE;
        right: 0px;
    }

    .login-btm {
        float: left;
    }

    .login-button {
        padding-right: 0px;
        text-align: center;
        margin-bottom: 25px;
    }

    .login-text {
        text-align: center;
        padding-left: 0px;
        color: #A2A4A4;
    }

    .loginbttm {
        padding: 0px;
    }
</style>