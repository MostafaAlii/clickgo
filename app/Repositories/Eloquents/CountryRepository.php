<?php

namespace  App\Repositories\Eloquents;
use App\Repositories\Contracts\CountryRepositoryInterface;
use Illuminate\Http\Request;
use App\DataTables\Dashboard\Admin\CountryDataTable;
use App\Services\CountryService;
use App\Dtos\CountryDTO;
use App\Models\Country;
class CountryRepository implements CountryRepositoryInterface {
    public function __construct(protected CountryService $countryService) {

    }
    public function index(CountryDataTable $countryDataTable) {
        return $countryDataTable->render('dashboard.Admin.country.index', ['PageTitle' => trans('dashboard/country.countries')]);
    }

    public function store(CountryDTO $dto) {
        return $this->countryService->store($dto);
    }

    public function update(Country $country, CountryDTO $dto) {
        return $this->countryService->update($country, $dto);
    }
}