/**
 * Pomodoro Timer JavaScript
 */

// DOM Elements
const timerDisplay = document.getElementById('timer-display');
const timerTitle = document.getElementById('timer-title');
const btnPomodoro = document.getElementById('btn-pomodoro');
const btnShortBreak = document.getElementById('btn-short-break');
const btnLongBreak = document.getElementById('btn-long-break');
const btnStart = document.getElementById('btn-start');
const btnPause = document.getElementById('btn-pause');
const btnReset = document.getElementById('btn-reset');
const btnSkip = document.getElementById('btn-skip');
const completedSessionsEl = document.getElementById('completed-sessions');
const focusTimeEl = document.getElementById('focus-time');

// Auth Elements
const authStatus = document.getElementById('auth-status');
const authButtons = document.getElementById('auth-buttons');
const usernameEl = document.getElementById('username');
const btnLogin = document.getElementById('btn-login');
const btnLogout = document.getElementById('btn-logout');
const btnRegister = document.getElementById('btn-register');
const loginModal = document.getElementById('login-modal');
const registerModal = document.getElementById('register-modal');
const closeLoginModal = document.getElementById('close-login-modal');
const closeRegisterModal = document.getElementById('close-register-modal');
const loginForm = document.getElementById('login-form');
const registerForm = document.getElementById('register-form');
const loginError = document.getElementById('login-error');
const registerError = document.getElementById('register-error');

// Timer state
let timerState = {
    mode: 'pomodoro',
    timeLeft: timerSettings.pomodoro,
    isRunning: false,
    timerId: null,
    sessionId: null,
    completedPomodoros: 0
};

// Format time as MM:SS
function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}

// Update timer display
function updateDisplay() {
    timerDisplay.textContent = formatTime(timerState.timeLeft);
    
    // Update page title
    document.title = `${formatTime(timerState.timeLeft)} - Pomodoro Timer`;
    
    // Update timer background color based on mode
    const timerContainer = timerDisplay.closest('.bg-red-500, .bg-green-500, .bg-blue-500');
    if (timerContainer) {
        timerContainer.classList.remove('bg-red-500', 'bg-green-500', 'bg-blue-500');
        
        if (timerState.mode === 'pomodoro') {
            timerContainer.classList.add('bg-red-500');
            timerTitle.textContent = 'Focus Time';
        } else if (timerState.mode === 'shortBreak') {
            timerContainer.classList.add('bg-green-500');
            timerTitle.textContent = 'Short Break';
        } else if (timerState.mode === 'longBreak') {
            timerContainer.classList.add('bg-blue-500');
            timerTitle.textContent = 'Long Break';
        }
    }
}

// Start timer
function startTimer() {
    if (timerState.isRunning) return;
    
    // Start a new session in the database
    if (!timerState.sessionId) {
        fetch('/api/timer/start', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                session_type: timerState.mode
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                timerState.sessionId = data.session_id;
            }
        })
        .catch(error => console.error('Error starting session:', error));
    }
    
    timerState.isRunning = true;
    btnStart.classList.add('hidden');
    btnPause.classList.remove('hidden');
    
    timerState.timerId = setInterval(() => {
        timerState.timeLeft--;
        updateDisplay();
        
        if (timerState.timeLeft <= 0) {
            completeTimer();
        }
    }, 1000);
}

// Pause timer
function pauseTimer() {
    if (!timerState.isRunning) return;
    
    timerState.isRunning = false;
    clearInterval(timerState.timerId);
    btnPause.classList.add('hidden');
    btnStart.classList.remove('hidden');
}

// Reset timer
function resetTimer() {
    pauseTimer();
    
    // End the current session if it exists
    endCurrentSession(false);
    
    timerState.timeLeft = timerSettings[timerState.mode];
    updateDisplay();
}

// Skip to next timer
function skipTimer() {
    // End current session
    endCurrentSession(false);
    
    // Move to next timer type
    if (timerState.mode === 'pomodoro') {
        if (timerState.completedPomodoros % timerSettings.longBreakInterval === 0 && timerState.completedPomodoros > 0) {
            setTimerMode('longBreak');
        } else {
            setTimerMode('shortBreak');
        }
    } else {
        setTimerMode('pomodoro');
    }
}

// Complete timer
function completeTimer() {
    pauseTimer();
    
    // Play notification sound
    const audio = new Audio('/public/notification.mp3');
    audio.play().catch(e => console.log('Audio play failed:', e));
    
    // Show browser notification if allowed
    if (Notification.permission === 'granted') {
        let title, body;
        
        if (timerState.mode === 'pomodoro') {
            title = 'Break Time!';
            body = 'Good job! Take a break.';
            timerState.completedPomodoros++;
        } else {
            title = 'Focus Time!';
            body = 'Break is over. Back to work!';
        }
        
        new Notification(title, { body });
    }
    
    // End the current session
    endCurrentSession(true);
    
    // Move to next timer type
    if (timerState.mode === 'pomodoro') {
        if (timerState.completedPomodoros % timerSettings.longBreakInterval === 0) {
            setTimerMode('longBreak');
        } else {
            setTimerMode('shortBreak');
        }
    } else {
        setTimerMode('pomodoro');
    }
    
    // Fetch updated stats
    fetchStats();
}

// Set timer mode (pomodoro, shortBreak, longBreak)
function setTimerMode(mode) {
    // End current session if running
    if (timerState.isRunning) {
        endCurrentSession(false);
    }
    
    pauseTimer();
    timerState.mode = mode;
    timerState.timeLeft = timerSettings[mode];
    timerState.sessionId = null;
    
    // Update active button
    btnPomodoro.classList.remove('active', 'bg-red-600');
    btnShortBreak.classList.remove('active', 'bg-green-600');
    btnLongBreak.classList.remove('active', 'bg-blue-600');
    
    if (mode === 'pomodoro') {
        btnPomodoro.classList.add('active', 'bg-red-600');
    } else if (mode === 'shortBreak') {
        btnShortBreak.classList.add('active', 'bg-green-600');
    } else if (mode === 'longBreak') {
        btnLongBreak.classList.add('active', 'bg-blue-600');
    }
    
    updateDisplay();
}

// End current session
function endCurrentSession(completed) {
    if (timerState.sessionId) {
        fetch('/api/timer/end', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                session_id: timerState.sessionId,
                completed: completed
            })
        })
        .then(response => response.json())
        .then(data => {
            timerState.sessionId = null;
        })
        .catch(error => console.error('Error ending session:', error));
    }
}

// Fetch and update stats
function fetchStats() {
    fetch('/api/timer/stats')
        .then(response => response.json())
        .then(data => {
            completedSessionsEl.textContent = data.completed_sessions || 0;
            focusTimeEl.textContent = `${data.total_focus_minutes || 0} min`;
        })
        .catch(error => console.error('Error fetching stats:', error));
}

// Check authentication status
function checkAuth() {
    fetch('/api/auth/check')
        .then(response => response.json())
        .then(data => {
            if (data.authenticated) {
                authStatus.classList.remove('hidden');
                authButtons.classList.add('hidden');
                usernameEl.textContent = data.user.username;
            } else {
                authStatus.classList.add('hidden');
                authButtons.classList.remove('hidden');
            }
        })
        .catch(error => console.error('Error checking auth:', error));
}

// Initialize timer on page load
function initializeTimer() {
    // Request notification permission
    if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
        Notification.requestPermission();
    }
    
    // Set default timer mode
    setTimerMode('pomodoro');
    
    // Fetch initial stats
    fetchStats();
    
    // Check auth status
    checkAuth();
}

// Event listeners
btnStart.addEventListener('click', startTimer);
btnPause.addEventListener('click', pauseTimer);
btnReset.addEventListener('click', resetTimer);
btnSkip.addEventListener('click', skipTimer);
btnPomodoro.addEventListener('click', () => setTimerMode('pomodoro'));
btnShortBreak.addEventListener('click', () => setTimerMode('shortBreak'));
btnLongBreak.addEventListener('click', () => setTimerMode('longBreak'));

// Auth event listeners
btnLogin.addEventListener('click', () => {
    loginModal.classList.remove('hidden');
});

btnRegister.addEventListener('click', () => {
    registerModal.classList.remove('hidden');
});

btnLogout.addEventListener('click', () => {
    fetch('/api/auth/logout', { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                checkAuth();
            }
        })
        .catch(error => console.error('Error logging out:', error));
});

closeLoginModal.addEventListener('click', () => {
    loginModal.classList.add('hidden');
});

closeRegisterModal.addEventListener('click', () => {
    registerModal.classList.add('hidden');
});

// Login form submission
loginForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const email = document.getElementById('login-email').value;
    const password = document.getElementById('login-password').value;
    
    fetch('/api/auth/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email, password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loginModal.classList.add('hidden');
            checkAuth();
            fetchStats(); // Refresh stats to show user-specific data
        } else {
            loginError.textContent = data.error || 'Login failed';
            loginError.classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Error logging in:', error);
        loginError.textContent = 'An error occurred during login';
        loginError.classList.remove('hidden');
    });
});

// Register form submission
registerForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const username = document.getElementById('register-username').value;
    const email = document.getElementById('register-email').value;
    const password = document.getElementById('register-password').value;
    const passwordConfirm = document.getElementById('register-password-confirm').value;
    
    if (password !== passwordConfirm) {
        registerError.textContent = 'Passwords do not match';
        registerError.classList.remove('hidden');
        return;
    }
    
    fetch('/api/auth/register', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
            username, 
            email, 
            password, 
            password_confirm: passwordConfirm 
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            registerModal.classList.add('hidden');
            checkAuth();
        } else {
            registerError.textContent = data.error || 'Registration failed';
            registerError.classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Error registering:', error);
        registerError.textContent = 'An error occurred during registration';
        registerError.classList.remove('hidden');
    });
});

// Initialize the timer when the page loads
document.addEventListener('DOMContentLoaded', initializeTimer); 