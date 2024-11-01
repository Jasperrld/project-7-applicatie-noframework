<?php
require_once('../vendor/autoload.php');  // Autolo
include('./inc/hoornhek_header.php');
?>
<main class="main-content">
    <div id="login">
        <div>
            <form action="authorisatie.php" method="POST" class="frmlogin" id="loginForm">
                <label for="fInlog">Inlognaam:</label>
                <input type="text" name="inlognaam" id="fInlog" size="25" placeholder="Inlognaam..." required><br>
                <label for="fWachtwoord">Wachtwoord:</label>
                <input type="password" id="fWachtwoord" name="wachtwoord" size="25" placeholder="Wachtwoord..." required><br>
                <script src="https://www.google.com/recaptcha/api.js?render=6LfKzZspAAAAAL0aaGuW4fsj3kmmhcbTUlTWMa3i"></script>
                <script>
                    grecaptcha.ready(function() {
                        grecaptcha.execute('6LfKzZspAAAAAL0aaGuW4fsj3kmmhcbTUlTWMa3i', { action: 'login' }).then(function(tokenn) {
                            document.getElementById('g-recaptcha-response').value = tokenn;
                        });
                    });
                </script>
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                <input type="submit" name="submit" value="Login"><br>
            </form>
        </div>
    </div>
</main>

<?php
include("inc/hoornhek_footer.php");
?>