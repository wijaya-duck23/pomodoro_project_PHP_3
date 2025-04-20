<nav class="bg-white shadow-md">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center">
                <i class="fas fa-clock text-red-500 text-2xl mr-2"></i>
                <span class="font-bold text-xl text-gray-800">Pomodoro Timer</span>
            </div>
            
            <div class="flex items-center space-x-4">
                <div id="auth-status" class="hidden">
                    <span class="text-sm text-gray-600">Welcome, <span id="username"></span></span>
                    <button id="btn-logout" class="ml-4 text-sm text-red-500 hover:text-red-700">Logout</button>
                </div>
                
                <div id="auth-buttons">
                    <button id="btn-login" class="text-sm text-gray-600 hover:text-gray-900">Login</button>
                    <button id="btn-register" class="ml-4 px-4 py-2 bg-red-500 text-white text-sm rounded-md hover:bg-red-600">Register</button>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Login Modal -->
<div id="login-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Login</h2>
            <button id="close-login-modal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="login-form" class="space-y-4">
            <div>
                <label for="login-email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="login-email" type="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" required>
            </div>
            
            <div>
                <label for="login-password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="login-password" type="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" required>
            </div>
            
            <div id="login-error" class="text-red-500 text-sm hidden"></div>
            
            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Login
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Register Modal -->
<div id="register-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Register</h2>
            <button id="close-register-modal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="register-form" class="space-y-4">
            <div>
                <label for="register-username" class="block text-sm font-medium text-gray-700">Username</label>
                <input id="register-username" type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" required>
            </div>
            
            <div>
                <label for="register-email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="register-email" type="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" required>
            </div>
            
            <div>
                <label for="register-password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="register-password" type="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" required>
            </div>
            
            <div>
                <label for="register-password-confirm" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input id="register-password-confirm" type="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" required>
            </div>
            
            <div id="register-error" class="text-red-500 text-sm hidden"></div>
            
            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Register
                </button>
            </div>
        </form>
    </div>
</div> 