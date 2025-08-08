<?php
namespace App\Actions\Country;
use App\Dtos\CountryDTO;
use App\Models\Country;
use App\Models\Concerns\UploadMedia;
class StoreCountryAction {
    use UploadMedia;
    public function __invoke(CountryDTO $dto): Country {
        $country = new Country();
        foreach ($dto->name as $locale => $name) {
            $country->translateOrNew($locale)->name = $name;
        }
        foreach ($dto->description as $locale => $description) {
            $country->translateOrNew($locale)->description = $description;
        }
        $country->save();
        $image = $dto->countryImage;
        if ($image) {
            $this->uploadSingleMedia(
                baseFolder: 'countries',
                file: $image,
                model: $country,
                relation: 'media',
                useStorage: true,
                generateThumbnail: false,
                collectionName: 'country',
                addWatermark: false
            );
        }
        return $country;
    }
}