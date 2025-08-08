<?php

namespace  App\Repositories\Contracts;

use App\DataTables\Dashboard\Admin\CountryDataTable;
use App\Dtos\CountryDTO;
use App\Models\Country;
interface CountryRepositoryInterface {
    public function index(CountryDataTable $countryDataTable);
    public function store(CountryDTO $dto);
    public function update(Country $country, CountryDTO $dto);
}
