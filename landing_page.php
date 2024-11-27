<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudApp</title>
    <link rel="stylesheet" href="landing.css">
    <link rel="icon" type="image/x-icon" href="pictures/logo.webp">
    <script src="landing.js"></script>
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <img src="pictures/logo.webp" alt="Logo" class="logo">
            <span class="app-name">BudApp</span>
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

    <!-- <section class="hero"> -->
    <div class="slider">
        <div class="slide active">
            <img src="pictures/hero.png" alt="Budżet domowy" class="slide-image">
            <div class="slide-content">
                <h1>Zarządzaj swoim budżetem z łatwością</h1>
                <p>Kontrola wydatków nigdy nie była prostsza.</p>
                <a href="#signup" class="cta-button">Rozpocznij za darmo</a>
            </div>
        </div>
        <div class="slide">
            <img src="pictures/savings.png" alt="Planowanie oszczędności" class="slide-image">
            <div class="slide-content">
                <h1>Planuj oszczędności z nami</h1>
                <p>Twój plan finansowy na wyciągnięcie ręki.</p>
                <a href="#signup" class="cta-button">Rozpocznij za darmo</a>
            </div>
        </div>
        <div class="slide">
            <img src="pictures/dream_chasing.png" alt="Oszczędzanie na przyszłość" class="slide-image">
            <div class="slide-content">
                <h1>Bezpieczna przyszłość finansowa</h1>
                <p>Oszczędzaj na swoje marzenia.</p>
                <a href="#signup" class="cta-button">Rozpocznij za darmo</a>
            </div>
        </div>
    </div>
    <!-- </section> -->

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
        <p>&copy; 2024 BudApp. Wszelkie prawa zastrzeżone.</p>
        <a href="#signup" class="signup-footer-link">Załóż konto teraz</a>
    </footer>
</body>
</html>
