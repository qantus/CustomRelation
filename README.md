# CustomRelation
Custom relation module for mindy

Usage:

## urls (example)

```php
...
'/custom_relation' => new Patterns('Modules.CustomRelation.urls', 'custom_relation'),
...
```

## modules (example)

```php
...
    'CustomRelation' => [
        'fields' => [
            'production' => [
                \Modules\Goods\Models\Product::className() => [
                    'title' => 'Товары',
                    'order' => ['name']
                ],
            ],
        ]
    ],
...
```
