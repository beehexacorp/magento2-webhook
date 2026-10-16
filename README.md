# Beehexa Webhook Module (`beehexacorp/module-webhook`)

## What is this module used for?

`Beehexa_Webhook` is used to push Magento events to external webhook endpoints.

The module currently listens to these main events:

- Product: save, delete
- Category: save, delete
- Customer: save, delete
- Order: create/update
- Invoice: save

The outgoing payload is built by a message builder with these key fields:

- `entity_type` (product/category/customer/order/invoice)
- `entity_id`
- `event_name` (`new`, `update`, `delete`)

The module prevents duplicate publishing in the same request flow using `WebhookRegistry` + entity key strategies:

- Customer key: email
- Default (product/order/...): entity ID

## Installation

### 1) Install via Composer (recommended)

```bash
composer require beehexacorp/module-webhook
php bin/magento module:enable Beehexa_Webhook
php bin/magento setup:upgrade
php bin/magento cache:flush
```

### 2) Required dependencies

This module requires:

- `beehexa/webhook-php`
- `beehexacorp/magento-base`
- `magento/framework >= 102`

If you install from Packagist using Composer, dependencies are resolved automatically.

## Where to configure

Go to Admin:

- `Stores > Configuration > Beehexa > Webhook Integration`

Root config path:

- `beehexa/webhook/*`

Available fields:

- `beehexa/webhook/enable` - Enable/disable webhook integration
- `beehexa/webhook/endpoint_provider` - Provider (`default`, `hexasync`, `slack`)
- `beehexa/webhook/hook_endpoint` - URL webhook endpoint
- `beehexa/webhook/api_token` - Token/secret used to sign requests (encrypted)

Default values (from `etc/config.xml`):

- `enable = 0`
- `endpoint_provider = hexasync`
- `hook_endpoint = ''`

## Operational notes

- If `endpoint_provider` or `hook_endpoint` is not configured, the module throws an exception when publishing.
- Module logs are written to: `var/log/beehexa_webhook.log`.
- `enable` is currently shown at default scope only, while endpoint/token fields support website scope.

## Quick test after configuration

1. Enable the module in config.
2. Enter provider + endpoint + token.
3. Save config, then clear cache if needed.
4. Update a product/order to trigger webhook publishing.
5. Check your receiver endpoint and `var/log/beehexa_webhook.log`.

