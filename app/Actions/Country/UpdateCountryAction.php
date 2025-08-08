<?php
namespace App\Actions\Country;
use App\Dtos\CountryDTO;
use App\Models\Country;
use App\Models\Concerns\UploadMedia;
class UpdateCountryAction {
    use UploadMedia;
    public function __invoke(Country $country, CountryDTO $dto): Country {
        foreach ($dto->name as $locale => $name) {
            $country->translateOrNew($locale)->name = $name;
        }
        foreach ($dto->description as $locale => $description) {
            $country->translateOrNew($locale)->description = $description;
        }
        $country->save();
        if ($dto->countryImage) {
            $this->updateSingleMedia('countries', $dto->countryImage, $country, null, 'media', true);
        }
        return $country;
    }
}