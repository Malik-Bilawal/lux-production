<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication</title>
</head>
<body>
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg w-full max-w-md p-8">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4 text-center">Two-Factor Authentication</h2>
        <p class="text-gray-500 mb-6 text-center">
            Enter the 6-digit code from your authenticator app to continue
        </p>

        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4" style="display:none;">
            The code you entered is invalid. Please try again.
        </div>
        <form action="/admin/2fa/verify" method="POST" class="space-y-4">
            <div>
                <label for="otp" class="block text-gray-600 font-medium mb-2">Authentication Code</label>
                <input 
                    type="text" 
                    name="otp" 
                    id="otp" 
                    maxlength="6"
                    placeholder="123456"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
                <span class="text-red-600 text-sm" style="display:none;">The authentication code is required.</span>
                </div>

            <button 
                type="submit" 
                class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                Verify
            </button>
        </form>

        <p class="text-gray-500 text-sm mt-4 text-center">
            Didn’t receive a code? <a href="/admin/2fa/resend" class="text-blue-600 hover:underline">Resend</a>
        </p>
    </div>
</div>
</body>
</html>