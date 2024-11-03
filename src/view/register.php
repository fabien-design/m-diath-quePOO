<?php
use App\Router\Router;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script type="module" src="scripts.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</head>
<body>
    <?php
        include 'includes/_header.php';
    ?>
    <main>
        <h2>Register</h2>
        <?php echo (isset($_GET['error']) ? ($_GET['error'] === 'password_mismatch' ? 'Les mots de passe de correspondent pas' : ($_GET['error'] === 'email_exists' ? 'L"email existe déjà' : ( $_GET['error'] === 'username_exists' ? 'Le username existe déjà' : ''))) : '')  ?>
        <form action="<?= Router::use('createAccount') ?>" method="post" id="form">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <p id="passwordMessage" style="color:red;padding:0;"></p>
            </div>
            <div>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Register</button>
            </div>
        </form>
    </main>
</body>
<script>
const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

const passwordInput = document.getElementById('password');
const passwordMessage = document.getElementById('passwordMessage');

// Fonction de validation du mot de passe
function validatePassword() {
    const password = passwordInput.value;
    if (passwordRegex.test(password)) {
        passwordMessage.textContent = "Mot de passe valide !";
        passwordMessage.style.color = "green";
    } else {
        passwordMessage.textContent = "Le mot de passe doit contenir au moins 8 caractères, avec une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial (@$!%*?&).";
        passwordMessage.style.color = "red";
    }
}

// Validation en temps réel lors de la saisie
passwordInput.addEventListener('input', validatePassword);

// Validation lors de la soumission du formulaire
document.getElementById('form').addEventListener('submit', function(event) {
    if (!passwordRegex.test(passwordInput.value)) {
        event.preventDefault(); // Empêche la soumission du formulaire si le mot de passe est invalide
        passwordMessage.textContent = "Veuillez corriger le mot de passe avant de soumettre.";
    }
});
</script>
</html>