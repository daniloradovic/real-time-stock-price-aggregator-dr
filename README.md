# 🎉 Real Time Stock Price Collector 📈

API and provides comprehensive reporting functionalities for analyzing stock
trends.

## 📚 Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [Code Style and Functionalities](#💻-code-style-and-functionalities)
- [Contributing](#contributing)
- [License](#license)

## 🛠️ Installation 

1. Clone the repository:

    ```bash
    git clone https://github.com/daniloradovic/real-time-stock-price-aggregator-dr.git
    ```

2. Navigate to the project directory:

    ```bash
    cd real-time-stock-price-aggregator-dr
    ```

3. Run laravel sail set of commands

    ```bash
    ./vendor/bin/sail up -d
    ./vendor/bin/sail composer install
    ./vendor/bin/sail npm install
    ./vendor/bin/sail npm run build
    ./vendor/bin/sail artisan migrate
    ./vendor/bin/sail artisan db:seed --class=CompaniesTableSeeder
    ```

4. Make sure to update crontab to be able to run scheduled command for price collector

    ```
    * * * * * cd path/to/project/real-time-stock-price-aggregator-dr && ./vendor/bin/sail artisan schedule:run >> /dev/null 2>&0
    ```

5. Add ```ALPHA_VANTAGE_API_KEY=``` to the .env file (Get it from the [Alpha Vantage](https://www.alphavantage.co/support/#api-key))

## 🔌 Usage

1. Open your browser and visit `http://real-time-stock-price-aggregator-dr.test/stocks` (or `localhost:80/stocks`) to access the real time price collector page.

2. Check [Github Actions](https://github.com/daniloradovic/real-time-stock-price-aggregator-dr/actions) for the PHPUnit test coverage 🕵️

If you're running it locally with sail, you can use:

```bash
./vendor/bin/sail artisan test
```

Or with phpunit with test coverage
```bash
 ./vendor/bin/phpunit --coverage-text --colors=never
```


## 💻 Code Style and Functionalities

### 🗓️ Task Scheduling

This snippet schedules the `app:fetch-stock-data` command to run every minute. 

#### Key Methods

1. **everyMinute**: This method schedules the command to run every minute.

2. **withoutOverlapping**: This method prevents the task from running if the previous instance of the task is still running. This is useful to prevent long-running tasks from stacking up.

3. **appendOutputTo**: This method appends the output of the command to a log file. In this case, the log file is `stock_data.log` in the storage logs directory.

This scheduling is done in the `console.php` file, which is where you define all of your scheduled tasks in a Laravel application.

### 📈 FetchStockData Command

`FetchStockData` is a console command that fetches stock data from the API and stores it in the database.

#### Key Properties and Methods

1. **$signature**: This property holds the name and signature of the console command. It's used to call the command from the command line.

2. **$description**: This property holds the description of the console command. It's displayed when you run the `php artisan list` command or when you call the command with the `-h` or `--help` option.

3. **__construct**: The constructor method. It receives an instance of `StockServiceInterface` as a parameter. This instance is injected by Laravel's service container.

4. **handle**: This method is called when the command is executed. It calls the `fetchStockData` method on the `StockServiceInterface` instance.

#### Dependency Injection

`FetchStockData` uses dependency injection to receive an instance of `StockServiceInterface`. This design allows the command to be loosely coupled, making it easier to test and maintain.


### 🧰 AlphaVantageStockService

`AlphaVantageStockService` is a service class implementing `StockServiceInterface`. It fetches stock data from an API and stores it in the database.

#### Key Methods

1. **fetchStockData**: Fetches stock data for all companies. It uses a cache to store the companies and avoid unnecessary database queries.

2. **fetchStockDataForCompany**: Fetches stock data for a specific company. It checks the cache first and if the data is not available, it fetches from the API.

### 💸 API route `/api/latest-prices`

The `latest` method fetches the latest stock prices for each symbol from the database and caches the result for 1 minute.

#### Description

The `latest` method fetches the latest stock prices for each symbol from the database, caches the result for 1 minute, and returns it as a JSON response. If the data is already in the cache, it retrieves from there instead of querying the database.

## 🤝 Contributing 

Contributions are welcome! If you have any ideas, suggestions, or bug reports, please open an issue or submit a pull request.

To contribute to this project, follow these steps:

1. Fork this repository.
2. Create a new branch: `git checkout -b feature/your-feature-name`.
3. Make your changes and commit them: `git commit -m 'Add some feature'`.
4. Push to the original branch: `git push origin feature/your-feature-name`.
5. Open a pull request.

Please ensure that your contributions adhere to the [code of conduct](CODE_OF_CONDUCT.md).

## 📝 License 

This project is licensed under the [MIT License](LICENSE).


Thanks 😊🎉👨‍💻
