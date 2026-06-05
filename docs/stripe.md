

# Instalación del cli

https://docs.stripe.com/stripe-cli/install#install

# Crear webhooks
https://dashboard.stripe.com/acct_1TeiiORsIzZXmkan/test/workbench/webhooks


# Comando para configurar el webhook
```bash

stripe listen --forward-to localhost:8000/webhooks/stripe
```
Esto genera una llave secreta


# Enlace de tarjeta de prueba.

https://docs.stripe.com/testing#testing-interactively


# Comandos de php artisan tinker para generar subscripcion

```php
$user = App\Models\User::find(1);

$user->newSubscription('default', config('services.stripe.price_ai_yearly'))->create();
```

```php
$user = App\Models\User::find(1);
$user->subscriptions()->create([
    'type'           => 'default',
    'stripe_id'      => 'sub_test_' . uniqid(),
    'stripe_status'  => 'active',
    'stripe_price'   => config('services.stripe.price_ai_yearly'),
    'quantity'       => 1,
    'trial_ends_at'  => null,
    'ends_at'        => null,
]);
```
