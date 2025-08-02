<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\DataTables\Dashboard\Admin\CategoryDataTable;
class CategoryController extends Controller {

    public function __construct(protected CategoryDataTable $categoryDataTable, protected CategoryRepositoryInterface $categoryInterface) {
        $this->categoryInterface = $categoryInterface;
        $this->categoryDataTable = $categoryDataTable;
    }

    public function index(CategoryDataTable $categoryDataTable) {
        return $this->categoryInterface->index($this->categoryDataTable);
    }

    public function create() {

    }

    public function store(Request $request) {

    }

    public function show(string $id) {

    }

    public function edit(string $id) {

    }

    public function update(Request $request, string $id) {

    }

    public function destroy(string $id) {

    }
}
