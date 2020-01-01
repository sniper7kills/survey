# Survey Package for Laravel

Simple survey package for Laravel that allows for easy customization of the look and feel of surveys
due to the lack of any styling included.

## Installation
1. Composer 
2. Publish
3. Configure
4. Migrate
5. Style
6. Usage

Get the package using composer
```shell script
composer require sniper7kills/survey
```

## Configuration

### Publishing Files
The following command will publish all assets from this package.
```shell script
php artisan vendor:publish --tag=survey-all
```
### Admin Contract
For any Admin Models implement the  `Sniper7Kills\Survey\Contract\SurveyAdmin` contract.

Example:
```php
class User implements \Sniper7Kills\Survey\Contract\SurveyAdmin
{
    public function isASurveyAdmin()
    {
        if($this->id == 1)
            return true;
        return false;
    }
}
```

### Updating Migrations
TODO; if your primary user models use uuid's instead of ints migrations will need to be changed;
That is not currently supported.

## Migrate
```shell script
php artisan migrate
```

## Style the views
The included views have no styling, so once they are styled you should publish them.

## Usage
The default dashboard is available at `/survey/admin/dashboard`.
From there new surveys can be created and responses can be viewed.

### Survey Settings
Surveys can either use a slug; or a UUID for access, by default a 
slug is used; but this can be overridden when creating a new survey, or in the config.

Surveys can also be open to guests or limited to application users.

Surveys can also have an End Time assigned to them to limit when people can submit them.

### Question Settings
Questions can be a *text* input, *selection* input, *radio* input, or *checkbox* input.
Checkbox inputs are the only input types where multiple options can be selected. Text inputs
are the only inputs that do not require an option to be created prior to being published.

Questions can also be marked as required and submissions will not be accepted without those questions being answered.

### Option Settings
When creating options, an *order* value can be set; when the options are retrieved for a question
they will be in increasing order based on their *order* value.

If two values have the same *order* value; they will be retrieved alphabetically.

Options that have no *order* value will be before options with an order value.

## Future Additions
While not available yet, there are plans to incorporate API endpoints into the application
and having VueJS components available for a more fluid interaction.

Another possibility for additions is a multi-select to complement the checkboxes, but
this addition wouldn't occur until after API and vue resources have been created.