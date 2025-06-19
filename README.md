# lambdatt-php/log

A SplitPHP Framework plugin to capture, organize and view the framework's logs.

---

## Installation

Install via Composer:

```bash
composer require lambdatt-php/log
```

Run the Migrations:
```bash
php console migrations:apply --module=log
```
**PS: this can only be installed on a SplitPHP Framework project. For more information refer to: https://github.com/splitphp/core**

## Usage

Once installed, simply navigate to your application's log route:

```
https://your-app.test/log
```

All framework logs are automatically captured and available at `/log`.

