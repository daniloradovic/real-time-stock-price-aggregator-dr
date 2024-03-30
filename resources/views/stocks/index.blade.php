<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 min-h-screen flex flex-col items-center justify-center text-white">
    <h1 class="text-4xl font-bold mb-5">💹 Real Time Stock Prices 📈</h1>
    
    <div id="stocksContainer" class="overflow-hidden w-full h-55 flex bg-white shadow p-5 rounded-lg"></div>
    <script>
        function fetchStockData() {
            fetch('api/latest-prices')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('stocksContainer');
                    container.innerHTML = ''; // Clear the container
                    const stocks = document.createElement('div');
                    stocks.className = 'inline-flex space-x-2';
                    data.forEach(element => {
                        const stockDiv = document.createElement('div');
                        stockDiv.className = 'stock inline-flex flex-col items-center bg-gray-200 rounded-lg px-4 py-2 text-gray-800 w-max hover-effect shadow-lg m-4';

                        stockDiv.innerHTML = `
                            <h2 class="text-xl font-bold mb-2">${element.symbol}</h2>
                            <p class="text-gray-500">Price: <span class="font-bold text-xl">${element.price}</span></p>
                            <p class="text-gray-500 mt-2">Change: <span class="font-bold text-xl ${element.change > 0 ? 'text-green-500' : 'text-red-500'}">${element.change > 0 ? '↑' : '↓'} ${element.change_percent}%</span></p>
                        `;
                        stocks.appendChild(stockDiv);
                    });
                    // Duplicate the content
                    const stocksClone = stocks.cloneNode(true);
                    container.appendChild(stocks);
                    container.appendChild(stocksClone);
                    // Add animation to the child elements
                    stocks.style.animation = `marquee 60s linear infinite`;
                    stocksClone.style.animation = `marquee 60s linear infinite`;
                });
        }

        // Fetch the stock data immediately, then every 60 seconds
        fetchStockData();
        setInterval(fetchStockData, 60000);
    </script>

    <style>
        /* Create a marquee animation */
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }

        /* Add a hover effect */
        .hover-effect:hover {
            transform: scale(1.05);
            transition: transform .3s ease-in-out;
        }
    </style>
</body>
</html>