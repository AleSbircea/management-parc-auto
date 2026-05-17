<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Înregistrare - AUTO221</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="home-page">
    <div class="bg-animation">
        <div id="stars"></div>
        <div id="stars2"></div>
    </div>

    <main class="hero-container">
        <div class="logo-area fade-in">
            <h1>AUTO<span>221</span></h1>
            <div class="divider"></div>
            <p>Creează un cont nou</p>
        </div>
        
        <form action="auth_logic.php" method="POST" class="button-group slide-up" style="flex-direction: column; width: 300px; gap: 15px;">
            <input type="text" name="username" placeholder="Utilizator" required style="padding: 12px; border-radius: 4px; border: 1px solid #d63031; background: #000; color: #fff;">
            <input type="email" name="email" placeholder="Email" required style="padding: 12px; border-radius: 4px; border: 1px solid #d63031; background: #000; color: #fff;">
            <input type="password" name="password" placeholder="Parolă" required style="padding: 12px; border-radius: 4px; border: 1px solid #d63031; background: #000; color: #fff;">
            
            <button type="submit" name="register" class="btn btn-login" style="width: 100%; cursor: pointer;">Creează Cont</button>
            <a href="index.php" style="color: #666; text-decoration: none; font-size: 0.8rem;">Înapoi la pornire</a>
        </form>
    </main>
</body>
</html>