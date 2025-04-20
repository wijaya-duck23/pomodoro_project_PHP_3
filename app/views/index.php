<?php
// View for the main Pomodoro Timer
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pomodoro Timer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Include navbar partial -->
    <?php require 'app/views/partials/navbar.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-red-500 text-white p-6">
                <h1 class="text-center text-3xl font-bold" id="timer-title">Pomodoro Timer</h1>
                <div class="text-center mt-4">
                    <div class="text-6xl font-bold tracking-widest" id="timer-display">25:00</div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="flex justify-center space-x-4 mb-8">
                    <button id="btn-pomodoro" class="px-4 py-2 bg-red-500 text-white rounded-md font-medium hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 active">
                        Pomodoro
                    </button>
                    <button id="btn-short-break" class="px-4 py-2 bg-green-500 text-white rounded-md font-medium hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400">
                        Short Break
                    </button>
                    <button id="btn-long-break" class="px-4 py-2 bg-blue-500 text-white rounded-md font-medium hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        Long Break
                    </button>
                </div>
                
                <div class="flex justify-center space-x-4">
                    <button id="btn-start" class="px-6 py-3 bg-red-500 text-white rounded-md font-medium hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400">
                        <i class="fas fa-play mr-2"></i> Start
                    </button>
                    <button id="btn-pause" class="px-6 py-3 bg-yellow-500 text-white rounded-md font-medium hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 hidden">
                        <i class="fas fa-pause mr-2"></i> Pause
                    </button>
                    <button id="btn-reset" class="px-6 py-3 bg-gray-500 text-white rounded-md font-medium hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400">
                        <i class="fas fa-redo-alt mr-2"></i> Reset
                    </button>
                    <button id="btn-skip" class="px-6 py-3 bg-blue-500 text-white rounded-md font-medium hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <i class="fas fa-forward mr-2"></i> Skip
                    </button>
                </div>
            </div>
            
            <div class="border-t border-gray-200 p-6">
                <h2 class="text-lg font-semibold mb-4">Session Stats</h2>
                <div id="stats-container" class="text-gray-600">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-100 p-4 rounded-md">
                            <p class="text-sm text-gray-500">Completed Sessions</p>
                            <p class="text-xl font-bold" id="completed-sessions">0</p>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-md">
                            <p class="text-sm text-gray-500">Focus Time</p>
                            <p class="text-xl font-bold" id="focus-time">0 min</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Include footer partial -->
    <?php require 'app/views/partials/footer.php'; ?>
    
    <!-- Timer settings from PHP config -->
    <script>
        // Timer settings from PHP
        const timerSettings = {
            pomodoro: <?= $timerSettings['pomodoro'] ?>,
            shortBreak: <?= $timerSettings['shortBreak'] ?>,
            longBreak: <?= $timerSettings['longBreak'] ?>,
            longBreakInterval: <?= $timerSettings['longBreakInterval'] ?>
        };
    </script>
    
    <!-- Timer JavaScript -->
    <script src="public/js/timer.js"></script>
</body>
</html> 