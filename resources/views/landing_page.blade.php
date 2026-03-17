<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DealsDay - Join Us</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
</head>

<body
    class="bg-gradient-to-br from-blue-700 to-blue-500 min-h-screen flex items-center justify-center px-4 relative overflow-hidden">

    <!-- Confetti canvas -->
    <canvas id="confetti-canvas" class="fixed inset-0 pointer-events-none z-0"></canvas>

    <!-- Card -->
    <div class="relative z-10 bg-white rounded-2xl p-8 max-w-md w-full text-center shadow-2xl">
        <div class="mb-6">
            <img src="/logo.png" alt="DealsDay Logo"
                class="w-20 h-20 mx-auto rounded-full bg-white p-2 shadow-lg animate-pulse" />
        </div>

        <h1 class="text-3xl font-extrabold text-blue-700 mb-2">Welcome to DealsDay 🚀</h1>
        <p class="text-gray-600 text-base mb-6">
            Unlock massive savings every day! Join our exclusive community on your favorite platform and never miss a
            deal again.
        </p>

        <!-- Telegram -->
        <a href="https://t.me/YourTelegramChannel" target="_blank"
            class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-xl text-lg font-semibold inline-flex items-center justify-center w-full mb-4 transition-transform transform hover:scale-105">
            <img src="{{ asset('telegram-svgrepo-com.svg') }}" class="w-5 h-5 mr-2 fill-white" />
            Join our Telegram Channel
        </a>

        <!-- WhatsApp -->
        <a href="https://chat.whatsapp.com/YourWhatsAppGroupLink" target="_blank"
            class="bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-xl text-lg font-semibold inline-flex items-center justify-center w-full transition-transform transform hover:scale-105">
            <img src="{{ asset('whatsapp-svgrepo-com.svg') }}" class="w-5 h-5 mr-2 fill-white" />
            Join our WhatsApp Group
        </a>

        <p class="mt-6 text-sm text-gray-400">🔥 Thousands have already joined. Don’t miss out!</p>
    </div>

    <script>
        // Launch confetti on load
        const duration = 4 * 1000;
        const animationEnd = Date.now() + duration;
        const defaults = {
            startVelocity: 30,
            spread: 360,
            ticks: 60,
            zIndex: 0
        };

        function randomInRange(min, max) {
            return Math.random() * (max - min) + min;
        }

        const interval = setInterval(function() {
            const timeLeft = animationEnd - Date.now();

            if (timeLeft <= 0) {
                return clearInterval(interval);
            }

            const particleCount = 50 * (timeLeft / duration);
            confetti({
                ...defaults,
                particleCount,
                origin: {
                    x: randomInRange(0.1, 0.3),
                    y: Math.random() - 0.2
                }
            });
            confetti({
                ...defaults,
                particleCount,
                origin: {
                    x: randomInRange(0.7, 0.9),
                    y: Math.random() - 0.2
                }
            });
        }, 250);
    </script>
</body>

</html>
