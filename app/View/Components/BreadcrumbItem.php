<?php
namespace App\View\Components;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
class BreadcrumbItem extends Component {
    public function __construct(public $route, public $title, public $class) {
        $this->route = $route;
        $this->title = $title;
        $this->class = $class;
    }

    public function render(): View|Closure|string {
        return view('components.dashboard.breadcrumb-item');
    }
}
