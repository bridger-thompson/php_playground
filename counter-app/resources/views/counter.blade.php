<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Counter App</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/apiClient.js') }}"></script>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f7fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .counter-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            text-align: center;
            width: 300px;
            position: relative;
        }
        .counter-value {
            font-size: 4rem;
            font-weight: bold;
            margin: 1rem 0;
            color: #2d3748;
            transition: all 0.2s ease;
        }
        .counter-value.updating {
            transform: scale(1.1);
            color: #4299e1;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 1.5rem;
        }
        .button {
            background-color: #4299e1;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            padding: 0.75rem 1.5rem;
            transition: background-color 0.3s;
            width: 100%;
        }
        .button:hover {
            background-color: #3182ce;
        }
        .button:disabled {
            background-color: #a0aec0;
            cursor: not-allowed;
        }
        .button.reset {
            background-color: #e53e3e;
        }
        .button.reset:hover {
            background-color: #c53030;
        }
        .button.reset:disabled {
            background-color: #fc8181;
        }
        .btn-container {
            width: 48%;
        }
        .error-message {
            color: #e53e3e;
            margin-top: 1rem;
            font-size: 0.875rem;
            display: none;
        }
        .error-message.visible {
            display: block;
        }
        .spinner {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top-color: #4299e1;
            animation: spin 1s ease-in-out infinite;
            display: none;
        }
        .spinner.visible {
            display: block;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .spin-animation {
            animation: spin 1s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <div class="counter-container">
        <div class="spinner" id="spinner"></div>
        <h1>Counter App</h1>
        <div class="counter-value" id="counter">{{ $count }}</div>
        <div class="button-container">
            <div class="btn-container">
                <button type="button" id="incrementBtn" class="button increment">Increment</button>
            </div>
            <div class="btn-container">
                <button type="button" id="resetBtn" class="button reset">Reset</button>
            </div>
        </div>
        <div class="error-message" id="errorMessage">An error occurred. Please try again.</div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const counterElement = document.getElementById('counter');
            const incrementBtn = document.getElementById('incrementBtn');
            const resetBtn = document.getElementById('resetBtn');
            const errorMessage = document.getElementById('errorMessage');
            const spinner = document.getElementById('spinner');

            const api = new ApiClient()
                .setLoadingStartCallback(() => {
                    incrementBtn.disabled = resetBtn.disabled = true;
                    spinner.classList.toggle('visible', true);
                    counterElement.classList.add('scale-110', 'text-blue-500');
                    errorMessage.classList.add('hidden');
                })
                .setLoadingEndCallback(() => {
                    incrementBtn.disabled = resetBtn.disabled = false;
                    spinner.classList.toggle('visible', false);
                    setTimeout(() => counterElement.classList.remove('scale-110', 'text-blue-500'), 300);
                })
                .setErrorCallback(error => {
                    console.error('Error:', error);
                    errorMessage.classList.remove('hidden');
                    setTimeout(() => errorMessage.classList.add('hidden'), 3000);
                });

            incrementBtn.addEventListener('click', async () => {
                try {
                    const data = await api.post('{{ route("counter.increment") }}');
                    counterElement.textContent = data.count;
                } catch (error) {
                    // Error is handled by the API client
                }
            });

            resetBtn.addEventListener('click', async () => {
                try {
                    const data = await api.post('{{ route("counter.reset") }}');
                    counterElement.textContent = data.count;
                } catch (error) {
                    // Error is handled by the API client
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === '+' || event.key === '=') incrementBtn.click();
                else if (event.key === 'r') resetBtn.click();
            });
        });
    </script>
</body>
</html>
