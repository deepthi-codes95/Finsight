DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finsight - Smart Expense Tracker</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/finsight/landing_styles.css">
</head>
<body>

    <!-- Navigation -->
    <nav>
        <a href="landing.php" class="logo">💰 Finsight</a>
        <ul class="nav-links">
            <li><a href="#features">Features</a></li>
            <li><a href="#how-it-works">How It Works</a></li>
            <li><a href="#pricing">Pricing</a></li>
        </ul>
        <div class="nav-buttons">
            <a href="login.php" class="btn btn-outline">Login</a>
            <a href="register.php" class="btn btn-primary">Get Started</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-badge">🚀 Track Your Expenses Smartly</div>
            <h1>Master Your <span class="highlight">Finances</span> With Confidence</h1>
            <p>Finsight helps you track expenses, set budgets, and visualize your spending patterns. Take control of your financial future today.</p>
            <div class="hero-buttons">
                <a href="register.php" class="btn btn-primary">Start Free Trial</a>
                <a href="#features" class="btn btn-outline">Learn More</a>
            </div>
            <div class="hero-stats">
                <div class="stat">
                    <div class="stat-number">10K+</div>
                    <div class="stat-label">Active Users</div>
                </div>
                <div class="stat">
                    <div class="stat-number">₹50L+</div>
                    <div class="stat-label">Expenses Tracked</div>
                </div>
                <div class="stat">
                    <div class="stat-number">99.9%</div>
                    <div class="stat-label">Uptime</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">

        <div style="max-width:1200px;margin:0 auto;">
            <div class="section-header" style="margin-bottom:30px;">
                <h2 style="margin-bottom:10px;">Welcome</h2>
                <p style="color:#666;">Login/Register to start using Finsight and manage your expenses with charts & PDF reports.</p>
            </div>

            <div class="features-grid" style="margin-top:30px;">
                <div class="feature-card">
                    <div class="feature-icon">🔐</div>
                    <h3>Login</h3>
                    <p>Access your dashboard and view expenses, budgets, and reports.</p>
                    <div style="margin-top:15px;">
                        <a class="btn btn-outline" href="login.php">Go to Login</a>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">📝</div>
                    <h3>Register</h3>
                    <p>Create your account to add expenses and track spending.</p>
                    <div style="margin-top:15px;">
                        <a class="btn btn-primary" href="register.php">Create Account</a>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">➕</div>
                    <h3>Add Expense</h3>
                    <p>Add new expenses with category, date, and description.</p>
                    <div style="margin-top:15px;">
                        <a class="btn btn-outline" href="add_expense.php">Add Expense</a>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">✏️</div>
                    <h3>Edit Expense</h3>
                    <p>Edit your saved expenses whenever you need.</p>
                    <div style="margin-top:15px;">
                        <a class="btn btn-outline" href="dashboard.php">View Dashboard</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-header" style="margin-top:70px;">
            <h2>Powerful Features</h2>
            <p>Everything you need to manage your finances effectively</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">💳</div>
                <h3>Expense Tracking</h3>
                <p>Track all your expenses in one place. Add expenses with categories, descriptions, and dates for complete visibility.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Visual Analytics</h3>
                <p>Beautiful pie charts and graphs help you understand your spending patterns at a glance.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💰</div>
                <h3>Budget Management</h3>
                <p>Set monthly budgets and track your spending against them. Get alerts when you approach your limits.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📄</div>
                <h3>PDF Reports</h3>
                <p>Download detailed expense reports in PDF format for your records or tax purposes.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">✏️</div>
                <h3>Easy Editing</h3>
                <p>Edit or delete expenses anytime. Keep your records accurate and up to date.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔐</div>
                <h3>Secure & Private</h3>
                <p>Your financial data is encrypted and stored securely. Only you can access your information.</p>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works" id="how-it-works">
        <div class="section-header">
            <h2>How It Works</h2>
            <p>Get started in three simple steps</p>
        </div>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Create Account</h3>
                <p>Sign up for free with your email and password to get started instantly.</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h3>Set Your Budget</h3>
                <p>Define your monthly budget and start adding your expenses.</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h3>Track & Analyze</h3>
                <p>Watch your spending patterns and take control of your finances.</p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <h2>Ready to Take Control?</h2>
        <p>Join thousands of users who are already managing their finances smarter with Finsight.</p>
        <a href="register.php" class="btn">Get Started Free</a>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-logo">💰 Finsight</div>
            <ul class="footer-links">
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
            <div class="footer-copy">
                &copy; 2024 Finsight. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>
