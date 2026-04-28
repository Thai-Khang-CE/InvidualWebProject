<?php
$errors = [];
$successMessage = '';
$name = '';
$email = '';
$message = '';

$storeLocations = [
    [
        'name' => 'District 1 Store',
        'address' => '45 Nguyen Hue Boulevard',
        'city' => 'Ho Chi Minh City',
        'map_url' => 'https://www.google.com/maps/search/?api=1&query=District+1+Ho+Chi+Minh+City',
    ],
    [
        'name' => 'Thu Duc Store',
        'address' => '120 Vo Van Ngan Street',
        'city' => 'Ho Chi Minh City',
        'map_url' => 'https://www.google.com/maps/search/?api=1&query=Thu+Duc+Ho+Chi+Minh+City',
    ],
    [
        'name' => 'Tan Binh Store',
        'address' => '88 Truong Son Street',
        'city' => 'Ho Chi Minh City',
        'map_url' => 'https://www.google.com/maps/search/?api=1&query=Tan+Binh+Ho+Chi+Minh+City',
    ],
];

$getLength = static function (string $value): int {
    return function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) && is_string($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) && is_string($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) && is_string($_POST['message']) ? trim($_POST['message']) : '';

    if ($name === '') {
        $errors[] = 'Full name is required.';
    }

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($message === '') {
        $errors[] = 'Message is required.';
    } elseif ($getLength($message) < 10) {
        $errors[] = 'Message must be at least 10 characters.';
    }

    if (empty($errors)) {
        $successMessage = 'Thank you for contacting us. We will get back to you soon.';
        $name = '';
        $email = '';
        $message = '';
    }
}
?>
<main class="page-main">
    <section class="contact-page">
        <div class="container">
            <section class="contact-hero">
                <h1>Contact Seasonal Wardrobe</h1>
                <p>Have questions about our seasonal collections or store availability? Contact us anytime.</p>
            </section>

            <section class="contact-grid" aria-label="Contact information and contact form">
                <article class="contact-info-card">
                    <h2 class="contact-page__section-title">Get in Touch</h2>
                    <ul class="contact-info-list">
                        <li><strong>Email:</strong> contact@seasonalwardrobe.local</li>
                        <li><strong>Phone:</strong> +84 900 123 456</li>
                        <li><strong>Business hours:</strong> Monday - Saturday, 9:00 AM - 8:00 PM</li>
                        <li><strong>Main address:</strong> Ho Chi Minh City, Vietnam</li>
                    </ul>
                </article>

                <article class="contact-form-card">
                    <h2 class="contact-page__section-title">Send Us a Message</h2>

                    <?php if (!empty($errors)) : ?>
                        <ul class="form-error-list">
                            <?php foreach ($errors as $error) : ?>
                                <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php if ($successMessage !== '') : ?>
                        <div class="form-success">
                            <p><?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    <?php endif; ?>

                    <form class="contact-form" action="index.php?page=contact" method="post" novalidate>
                        <div class="form-group">
                            <label for="contactName">Full Name</label>
                            <input
                                class="form-input"
                                type="text"
                                id="contactName"
                                name="name"
                                required
                                value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>"
                            >
                        </div>

                        <div class="form-group">
                            <label for="contactEmail">Email</label>
                            <input
                                class="form-input"
                                type="email"
                                id="contactEmail"
                                name="email"
                                required
                                value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"
                            >
                        </div>

                        <div class="form-group">
                            <label for="contactMessage">Message</label>
                            <textarea
                                class="form-input form-textarea"
                                id="contactMessage"
                                name="message"
                                rows="6"
                                required
                            ><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>

                        <button class="contact-submit" type="submit">Send Message</button>
                    </form>
                </article>
            </section>

            <section class="contact-locations" aria-labelledby="storeLocationsTitle">
                <div class="contact-section-heading">
                    <h2 id="storeLocationsTitle" class="contact-page__section-title">Store Locations</h2>
                    <p>Visit one of our physical stores to explore seasonal favorites in person.</p>
                </div>

                <div class="store-location-grid">
                    <?php foreach ($storeLocations as $store) : ?>
                        <article class="store-location-card">
                            <h3><?php echo htmlspecialchars($store['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p><?php echo htmlspecialchars($store['address'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p><?php echo htmlspecialchars($store['city'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <a
                                class="map-link"
                                href="<?php echo htmlspecialchars($store['map_url'], ENT_QUOTES, 'UTF-8'); ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                View on Google Maps
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </section>
</main>
