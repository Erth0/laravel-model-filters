# Laravel Model Filters

### An clean way of filtering laravel models by query strings.
[![GitHub issues](https://img.shields.io/github/issues/Erth0/laravel-model-filters.svg)](https://github.com/Erth0/laravel-model-filters/issues)
[![GitHub forks](https://img.shields.io/github/forks/Erth0/laravel-model-filters.svg)](https://github.com/Erth0/laravel-model-filters/network)
[![GitHub stars](https://img.shields.io/github/stars/Erth0/laravel-model-filters.svg)](https://github.com/Erth0/laravel-model-filters/stargazers)
[![GitHub license](https://img.shields.io/github/license/Erth0/laravel-model-filters.svg)](https://github.com/Erth0/laravel-model-filters/blob/master/LICENCE.md)
[![Twitter](https://img.shields.io/twitter/url/https/github.com/Erth0/laravel-model-filters.svg?style=social)](https://twitter.com/intent/tweet?text=Wow:&url=https%3A%2F%2Fgithub.com%2FErth0%2Flaravel-model-filters)

## Installation

You can install this package via composer using this command:

```bash
composer require eth0/laravel-filters
```

The package will automatically register itself, but if your laravel versions is < 5.5
you will need to add ``` Mukja\LaravelFilters\LaravelFiltersServiceProvider::class,``` 
service provider under your ```config/app.php``` file.

## Documentation
Once the package is installed there will be 2 new artisan commands.
- ``` php artisan make:filter ```
- ``` php artisan make:model:filter ```

We can then generate a new model filter just by typing ```php artisan make:filter ModelFilter``` this will
generate a new php file under ```app/Filters/``` folder with the name ```ModelFilter.php``` which will look like below.
```php
<?php

namespace App\Filters\ModelFilter;

use Mukja\LaravelFilters\FiltersAbstract;

class ModelFilter extends FiltersAbstract
{
    protected $filters = [
        //
    ];
}

```
On the ```$filters``` variable we can register all our model filters.
```php
<?php

namespace App\Filters\ModelFilter;

use Mukja\LaravelFilters\FiltersAbstract;
use App\Filters\Model\{
    ModelStatusFilter,
    ModelCreatedAtFilter
};

class ModelFilter extends FiltersAbstract
{
    protected $filters = [
        'status' => ModelStatusFilter::class,
        'created_at' => ModelCreatedAtFilter::class
    ];
}

```
However we do not have created yet the model filters so lets create now the model filters.
> Note: Pro tip if you add an prefix before your file like below Model a new folder Model will be generated
inside your Filters folder so you can keep your filters tidy.

```php artisan make:model:filter Model\ModelStatusFilter Model\ModelCreatedAtFilter```

Below is one of the file generated:
```php
<?php

namespace App\Filters\Model;

use Mukja\LaravelFilters\FilterAbstract;
use Illuminate\Database\Eloquent\Builder;

class ModelStatusFilter extends FilterAbstract
{
    /**
     * Define column mappings.
     * @return array
     */
    public function mappings ()
    {
        return [];
    }

    /**
     * Filter the column.
     * @param  Builder $builder
     * @param  mixed  $value
     * @return Builder $builder
     */
    public function filter (Builder $builder, $value)
    {
        //
    }
}

```

On the mappings method we can return an array of mappings for example if we do want to convert a few words to match
our database columns like:
```php
public function mappings ()
{
    return [
        'status' => 'active',
        's' => 'active'
    ];
}
```

Next on the ```filter``` method we can build our query filter like:
```php
/**
 * Filter the column.
 * @param  Builder $builder
 * @param  mixed  $value
 * @return Builder $builder
 */
public function filter (Builder $builder, $value)
{
    return $builder->where('status', $value);
}
```

Once we have everything ready the last step is to add a scope to our laravel model which this filter will be used.
```php
public function scopeFilter (Builder $builder, $request, array $filters = [])
{
    return (new ModelFilter($request))->filter($builder);
}
```

And then we can use our model filters everywhere on our applications just by adding the filter scope like:
```php
$model = Model::filter($request);
```

Now we have our filters setup which mean we can now send to our server query string ```https:://example.com?status=active``` this will return us all the records where the status is active.
## Road Map
Here's the plan for what's coming:

- [ ] Add tests.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see feel free to make any pull request to make this package even better.

## Security

If you discover any security related issues, please email [e.mukja@icloud.com](mailto:e.mukja@icloud.com) instead of using the issue tracker.

## Credits

- [Eluert Mukja](https://github.com/Erth0)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
