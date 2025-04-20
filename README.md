# Pomodoro Timer Web Application

A full-stack Pomodoro Timer web application built using PHP with MVC architecture, MySQL, and Tailwind CSS.

## Features

- 🍅 Pomodoro technique implementation (25-minute work sessions, 5-minute short breaks, 15-minute long breaks)
- 🔄 Full cycle support with automatic progression
- 📊 Session tracking and statistics
- 👤 User account system (optional)
- 🎨 Responsive design with Tailwind CSS
- 🔔 Browser notifications
- 🎵 Audio alerts when timer completes

## Tech Stack

- **Frontend:** HTML, Tailwind CSS (via CDN), JavaScript
- **Backend:** PHP 8+ with MVC architecture
- **Database:** MySQL
- **Tools:** phpMyAdmin for database management

## Project Structure

```
pomodoro_project_PHP/
├── app/
│   ├── controllers/
│   │   ├── SessionController.php
│   │   └── TimerController.php
│   ├── models/
│   │   ├── SessionModel.php
│   │   └── UserModel.php
│   └── views/
│       ├── partials/
│       │   ├── footer.php
│       │   └── navbar.php
│       └── index.php
├── config/
│   ├── config.php
│   └── database.php
├── core/
│   ├── Controller.php
│   ├── Model.php
│   └── Router.php
├── database/
│   └── schema.sql
├── public/
│   └── js/
│       └── timer.js
├── routes/
│   └── web.php
├── index.php
└── README.md
```

## Installation

1. Clone this repository:
   ```
   git clone https://github.com/yourusername/pomodoro_project_PHP.git
   cd pomodoro_project_PHP
   ```

2. Configure your web server (Apache or Nginx) to point to the project directory.

3. Create the database and tables using phpMyAdmin by importing the `database/schema.sql` file or running the SQL commands directly.

4. Update database connection details in `config/database.php`:
   ```php
   return [
       'host' => 'localhost',
       'database' => 'pomodoro_app',
       'username' => 'your_username',
       'password' => 'your_password',
       // ...
   ];
   ```

5. Modify the base URL in `config/config.php` to match your server setup:
   ```php
   'base_url' => 'http://your-server-url/pomodoro_project_PHP',
   ```

6. Ensure your web server has proper permissions to read and write to the project directory.

## Usage

1. Navigate to the application in your web browser.
2. Start a Pomodoro session by clicking the "Start" button.
3. Work until the timer completes.
4. Take a break when prompted.
5. The application will automatically cycle between work and break sessions.
6. You can manually switch between Pomodoro, Short Break, and Long Break modes using the buttons.
7. Track your productivity with the session statistics.

## Optional Features

### User Authentication
- Register an account to track your Pomodoro sessions across devices.
- View your personal statistics and history.

### Session Analytics
- The application tracks and displays your completed sessions and total focus time.

## Customization

You can customize the timer settings in `config/config.php`:

```php
'pomodoro_duration' => 25 * 60,        // 25 minutes
'short_break_duration' => 5 * 60,      // 5 minutes
'long_break_duration' => 15 * 60,      // 15 minutes
'long_break_interval' => 4,            // After every 4 pomodoros
```

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgements

- The [Pomodoro Technique](https://francescocirillo.com/pages/pomodoro-technique) by Francesco Cirillo
- [Tailwind CSS](https://tailwindcss.com/) for the UI styling 