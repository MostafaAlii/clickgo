<?php
namespace App\Http\Controllers\Dashboard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\CountryRepositoryInterface;
use App\DataTables\Dashboard\Admin\CountryDataTable;
use App\Http\Requests\Dashboard\Country\StoreCountryRequest;
use App\DTOs\CountryDTO;
use App\Models\Country;
class CountryController extends Controller {

    public function __construct(protected CountryDataTable $countryDataTable, protected CountryRepositoryInterface $countryInterface) {
        $this->countryInterface = $countryInterface;
        $this->countryDataTable = $countryDataTable;
    }

    public function index(CountryDataTable $countryDataTable) {
        return $this->countryInterface->index($this->countryDataTable);
    }

    public function store(StoreCountryRequest $request) {
        $dto = CountryDTO::fromRequest($request);
        $this->countryInterface->store($dto);
        return redirect()->back()->with('success', __('dashboard.country_created'));
    }

    public function edit(Country $country)
    {
        return response()->json([
            'country' => $country->load('translations'),
            'image_url' => $country->getMediaUrl('countries', $country, null, 'media', 'country', true),
        ]);
    }

    public function update(Request $request, Country $country) {
        $dto = CountryDTO::fromRequest($request);
        $this->countryInterface->update($country, $dto);
        return redirect()->route('admin.country.index')->with('success', trans('dashboard/messages.updated_successfully'));
    }
}