# 📘 README (in English) 🇬🇧
# 🎓 Student Assignment Tracker 

📘 This README is also available in Macedonian: [README.mk.md](./README.mk.md)

A web-based system to help students manage their assignments, track deadlines, and engage in academic discussions.

## 📌 Project Overview

This application allows students to:
- Register and log in securely
- Create and view assignments and tasks
- Manage course-related data
- Participate in a discussion forum
- View a personalized dashboard with all tasks and deadlines

## 🛠️ Technologies Used

- **Backend:** PHP (vanilla)
- **Frontend:** HTML, CSS
- **Database:** MySQL
- **Design Assets:** Custom icon and styles

## 📂 Project Structure

/assets → Icons and custom stylesheets

/includes → PHP includes for DB and logic

/public → Public-facing PHP pages (dashboard, login, forum, etc.)

/views → Static content (About Us page)

studentassignmenttracker1.sql → MySQL database schema

## 🚀 Getting Started

### Prerequisites

- PHP 7.x or above
- MySQL or compatible DBMS
- Web server (e.g., Apache)

### Installation

1. Clone or download this repository.
2. Import the database using studentassignmenttracker1.sql.
3. Configure your /includes/db.php with your database credentials.
4. Run the app on a local or remote server with PHP support.

### Example Configuration (/includes/db.php)


php

$host = "localhost";

$db = "student_assignment_tracker";

$user = "root";

$pass = ""; // your DB password

👥 Team Members

Marija Taseva – GitHub: [github.com/mtaseva](https://github.com/mtaseva)

Martina Divanisova – GitHub: [github.com/MDivanisova](https://github.com/MDivanisova)

Bojana Lazarova – GitHub: [github.com/Bojana22](https://github.com/Bojana22)