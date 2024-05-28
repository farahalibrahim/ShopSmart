<section class="footer" id="footer">
    <div class="footer-container">
        <div class="footer-box">
            <a href="#" class="logo"><span class="material-symbols-outlined">
                    shopping_cart
                </span> Shop Smart</a>
            <p>Our Social Media Platforms <br>Follow Us !! </p>
            <div class="social">
                <a href="#"><i class="bx bxl-facebook"></i></a>
                <a href="#"><i class="bx bxl-instagram"></i></a>
                <a href="#"><i class="bx bxl-youtube"></i></a>
                <a href="#"><i class="bx bxl-whatsapp"></i></a>
            </div>
        </div>
        <div class="footer-box">
            <h2>Categories</h2>
            <a href="http://localhost:3000/PHP/main/index.php#categories">Fruits & Vegetables</a>
            <a href="http://localhost:3000/PHP/main/index.php#categories">Bakery</a>
            <a href="http://localhost:3000/PHP/main/index.php#categories">Personal Care</a>
            <a href="http://localhost:3000/PHP/main/index.php#categories">Cleaning Supplies</a>
        </div>
        <div class="footer-box">
            <h2>To partner with us</h2>
            <p>Submit your email for partnerships <br>Here</p>
            <form id="emailForm" action="#">
                <i class="bx bx-envelope"></i>
                <input id="footerEmailInput" type="email" placeholder="Enter your email">
                <i id="footerSubmitEmail" class="bx bx-right-arrow-alt"></i>
            </form>
        </div>

        <script>
            document.getElementById('footerSubmitEmail').addEventListener('click', function() {
                var email = document.getElementById('footerEmailInput').value;
                window.location.href = `mailto:${email}`;
            });
        </script>
    </div>

    </div>
</section>