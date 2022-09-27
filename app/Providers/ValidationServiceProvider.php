<?php

    namespace App\Providers;

    use App\Validators\InstallationJobCompleteValidator;
    use App\Validators\OpportunityWonValidator;
    use App\Validators\PostCodeValidator;
    use Illuminate\Support\ServiceProvider;
    use Illuminate\Support\Facades\Validator;
    use Carbon\Carbon;

    class ValidationServiceProvider extends ServiceProvider
    {

        public function boot()
        {
            Validator::extend('legal_characters', function($attribute, $value)
            {
                return preg_match("/^[^\{\}\[\]<>]+$/m", $value);
            });

            Validator::replacer('legal_characters', function ($message, $attribute, $rule, $parameters)
            {
                if (strpos($attribute, '_') !== false) {
                    $attribute = last(explode('_', $attribute));
                } elseif (strpos($attribute, '.') !== false){
                    $attribute = last(explode('.', $attribute));
                }

                return sprintf('Field %s could not contain illegal characters such as { [ ', ucfirst($attribute));
            });
        }
    }
