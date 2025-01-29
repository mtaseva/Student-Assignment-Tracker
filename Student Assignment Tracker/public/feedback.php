<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collecting form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $feedback = $_POST['feedback'];

    // Developer email addresses
    $developerEmails = [
        "student1@example.com",
        "student2@example.com",
        "student3@example.com"
    ];

    // Subject and message for the email
    $subject = "New Feedback Submission from $name";
    $message = "You have received new feedback from $name ($email):\n\n$feedback";
    $headers = "From: $email";

    // Sending feedback to the developers
    foreach ($developerEmails as $developerEmail) {
        mail($developerEmail, $subject, $message, $headers);
    }

    header("Location: ../views/help.html");
    exit;
}
?>
