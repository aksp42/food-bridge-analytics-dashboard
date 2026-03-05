<style>
    .fb-footer {
        background-color: #1a3a17; /* Same Dark Green as Navbar */
        color: #e2e8f0;
        padding: 50px 0 20px 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin-top: 50px;
        border-top: 4px solid #4ade80;
    }
    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 40px;
        padding: 0 20px;
    }
    .footer-section h3 {
        color: #4ade80;
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .footer-section p {
        font-size: 14px;
        line-height: 1.6;
        color: #cbd5e1;
    }
    .footer-links {
        list-style: none;
        padding: 0;
    }
    .footer-links li {
        margin-bottom: 12px;
    }
    .footer-links a {
        color: #cbd5e1;
        text-decoration: none;
        font-size: 14px;
        transition: 0.3s;
    }
    .footer-links a:hover {
        color: white;
        padding-left: 5px;
    }
    .footer-bottom {
        text-align: center;
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid rgba(255,255,255,0.1);
        font-size: 13px;
        color: #94a3b8;
    }
    .social-icons {
        display: flex;
        gap: 15px;
        margin-top: 15px;
    }
    .social-icons a {
        color: white;
        background: rgba(255,255,255,0.1);
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: 0.3s;
    }
    .social-icons a:hover {
        background: #4ade80;
        color: #1a3a17;
    }
</style>

<footer class="fb-footer">
    <div class="footer-container">
        <div class="footer-section">
            <h3>Food Bridge</h3>
            <p>Connecting surplus food from vendors to those in need through dedicated NGOs. Join us in our mission to eliminate food wastage.</p>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>

        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul class="footer-links">
                <li><a href="/food-wastage-system/index.php">Home</a></li>
                <li><a href="/food-wastage-system/about.php">About Us</a></li>
                <li><a href="/food-wastage-system/feedback.php">User Feedback</a></li>
                <li><a href="/food-wastage-system/register.php">Join as Partner</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h3>Contact Us</h3>
            <ul class="footer-links text-sm">
                <li><i class="fas fa-envelope mr-2"></i> support@foodbridge.com</li>
                <li><i class="fas fa-phone mr-2"></i> +91 98765 43210</li>
                <li><i class="fas fa-map-marker-alt mr-2"></i> Greater Noida, UP, India</li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        &copy; <?php echo date("Y"); ?> <b>Food Bridge</b>. All Rights Reserved. | Designed for a Greener Tomorrow.
    </div>
</footer>