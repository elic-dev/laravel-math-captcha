# Very simple math captcha for Laravel5

A simple math question (`+`,`-`,`*`) to validate user input.

## Installation

```
composer require elic-dev/laravel-math-captcha
```

### Setup Laravel > 5.5

This package supports Laravel Package Auto-Discovery.

### Setup Laravel <= 5.4

You can add the ServiceProvider to the providers array in `app/config/app.php`.

```
ElicDev\MathCaptcha\MathCaptchaServiceProvider::class,
```


## Usage

This package only returns the question and the input. You have to position it within your labels and form classes.

```php
{{ app('mathcaptcha')->label() }}
{!! app('mathcaptcha')->input() !!}
```

Display it wihtin Bootstrap as example:

```
<div class="form-group">
    <label for="mathgroup">Please solve the following math function: {{ app('mathcaptcha')->label() }}</label>
    {!! app('mathcaptcha')->input(['class' => 'form-control', 'id' => 'mathgroup']) !!}
    @if ($errors->has('mathcaptcha'))
        <span class="help-block">
            <strong>{{ $errors->first('mathcaptcha') }}</strong>
        </span>
    @endif
</div>
```

Looks like

![MathCaptcha Bootstrap](https://raw.githubusercontent.com/elic-dev/laravel-math-captcha/master/readme_bootstrap_sample.png)


#### Validation

Add `'mathcaptcha' => 'required|mathcaptcha'` to rules array.


```php
$this->validate($request, [
    'mathcaptcha' => 'required|mathcaptcha',
]);

```

Add corresponding translation string to your `lang/validation.php` files.

#### Reset

This package does not generate a new math question for each request. Once the
form has been submited without validation erros you can reset the library to force
generate a new question.

```php
app('mathcaptcha')->reset();
```

## Configuration

### Operands, Min, Max

You can adjust the available operands (`+`,`-`,`*`) and minimum or maximum randum 
values used. Some users might stuggle with more complex math operations.


```
php artisan vendor:publish --provider="ElicDev\MathCaptcha\MathCaptchaServiceProvider" --tag=config
```

### Display as text

It is possible to show the math question as text (e.g. "Four plus Five"). You can adjust a setting in the config file. This requires translations and a language files. A few languages are provided with this package.


```
php artisan vendor:publish --provider="ElicDev\MathCaptcha\MathCaptchaServiceProvider" --tag=lang
```


