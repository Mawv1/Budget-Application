<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twoja Aplikacja Budżetowa</title>
    <link rel="stylesheet" href="landing.css">
    <link rel="icon" type="image/x-icon" href="pictures/logo.webp">
    <script src="scripts/landing.js"></script>
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <img src="pictures/logo.webp" alt="Logo" class="logo">
            <span class="app-name">Budget Application</span>
        </div>
        <nav class="nav">
            <ul>
                <li><a href="#features">Funkcje</a></li>
                <li><a href="#benefits">Korzyści</a></li>
                <li><a href="#testimonials">Opinie</a></li>
                <li><a href="login_module/register.php" class="cta-btn">Zaloguj</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>Zarządzaj swoim budżetem z łatwością</h1>
            <p>Odkryj prosty sposób na kontrolę wydatków i planowanie oszczędności. Twoje finanse — w Twoich rękach.</p>
            <a href="#signup" class="cta-button">Rozpocznij za darmo</a>
        </div>
        <div class="hero-image">
            <img src="pictures/hero.png" alt="Hero Image">
        </div>
    </section>

    <section id="features" class="features">
        <h2>Dlaczego warto?</h2>
        <div class="features-grid">
            <div class="feature-item">
                <img src="pictures/feature1.png" alt="Feature 1">
                <h3>Łatwość użycia</h3>
                <p>Intuicyjny interfejs pozwala szybko zarządzać finansami.</p>
            </div>
            <div class="feature-item">
                <img src="pictures/feature2.png" alt="Feature 2">
                <h3>Personalizacja</h3>
                <p>Dostosuj aplikację do swoich potrzeb.</p>
            </div>
            <div class="feature-item">
                <img src="pictures/feature3.png" alt="Feature 3">
                <h3>Analizy i raporty</h3>
                <p>Uzyskaj szczegółowe informacje o swoich wydatkach.</p>
            </div>
        </div>
    </section>

    <section id="benefits" class="benefits">
        <h2>Korzyści z aplikacji</h2>
        <ul>
            <li>Lepsza kontrola nad budżetem</li>
            <li>Oszczędność czasu i pieniędzy</li>
            <li>Bezpieczeństwo Twoich danych</li>
        </ul>
    </section>

    <section id="testimonials" class="testimonials">
        <h2>Opinie użytkowników</h2>
        <div class="testimonial">
            <p>"Dzięki tej aplikacji w końcu wiem, gdzie uciekają moje pieniądze!"</p>
            <span>- Anna K.</span>
        </div>
        <div class="testimonial">
            <p>"Polecam każdemu! Intuicyjna, przyjazna i skuteczna."</p>
            <span>- Piotr W.</span>
        </div>
    </section>

    <footer class="footer">
        <p>&copy; 2024 Budget Application. Wszelkie prawa zastrzeżone.</p>
        <a href="#signup" class="signup-footer-link">Załóż konto teraz</a>
    </footer>
</body>
</html>
