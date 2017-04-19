# Very simple math captcha for Laravel

## Installation

```
composer require elic-dev/laravel-math-captcha
```

## Laravel 5

### Setup

Add ServiceProvider to the providers array in `app/config/app.php`.

```
ElicDev\MathCaptcha\MathCaptchaServiceProvider::class,
```


### Usage

This package only returns the question and the input. You have to position it within your labels and form classes.

```php
{{ app('mathcaptcha')->label(); }}
{!! app('mathcaptcha')->input(); !!}
```

Display it wihtin Bootstrap as example:

```
<div class="form-group">
    <label for="mathgroup">{{ app('mathcaptcha')->label(); }}</label>
    {!! app('mathcaptcha')->input(['class' => 'form-control', 'id' => 'mathgroup']); !!}
</div>
```

##### Validation

Add `'mathcaptcha' => 'required|mathcaptcha'` to rules array.

Add corresponding translation string to your `lang/validation.php` files.