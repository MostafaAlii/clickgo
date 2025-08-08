<?php

namespace App\Services;
use App\Models\Country;
use App\Dtos\CountryDTO;
use App\Actions\Country\{StoreCountryAction,UpdateCountryAction};

class CountryService {
    public function store(CountryDTO $dto): Country {
        return (new StoreCountryAction())($dto);
    }

    public function update(Country $country, CountryDTO $dto): Country {
        return (new UpdateCountryAction())($country, $dto);
    }
}