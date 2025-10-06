<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\Brand;
use App\Models\Location;
use App\Models\Page;
use App\Models\Rating;
use App\Models\Seater;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    public function index()
    {
        $brands = Brand::active()->withCount('vehicles')->orderBy('name')->get();
        $seaters = Seater::active()->withCount('vehicles')->orderBy('number')->get();
//        $vehicles  = Vehicle::active()->latest()->paginate(getPaginate());
        $vehicles = Vehicle::selectRaw('MIN(id) as id')
            ->where('status', Status::ENABLE)
            ->groupBy('model')
            ->get();

        $vehicles = Vehicle::whereIn('id', $vehicles->pluck('id'))->get();

        $pageTitle = 'All Vehicles';

        $sections = Page::where('slug', 'dossier')->first();
        $seoContents = $sections->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;

        return view('template.vehicles.index', compact('vehicles', 'pageTitle', 'brands', 'seaters', 'seoContents', 'seoImage', 'sections'));
    }

    public function details($id, $slug)
    {

        $pageTitle = 'Vehicle Details';
        $vehicle = Vehicle::active()->where('id', $id)->with('ratings')->withCount('ratings')->withAvg('ratings', 'rating')->firstOrFail();
        $rentalTerms = getContent('rental_terms.element', false, 1);
        $isRating = Rating::where('user_id', auth()?->id())->where('id', $id)->count();
        return view('template.vehicles.details', compact('vehicle', 'pageTitle', 'rentalTerms', 'isRating'));
    }

    public function filter(Request $request)
    {
        $pageTitle = 'Vehicle Search';
        $brands = Brand::active()->withCount('vehicles')->orderBy('name')->get();
        $seaters = Seater::active()->withCount('vehicles')->orderBy('number')->get();

        $vehicles = Vehicle::active()->searchable(['name', 'model'])->filter(['brand_id', 'seater_id', 'name', 'model']);

        if ($request->min_price) {
            $vehicles->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $vehicles->where('price', '<=', $request->max_price);
        }
        $vehicles = $vehicles->latest()->paginate(getPaginate())->withQueryString();

        return view('template.vehicles.index', compact('vehicles', 'pageTitle', 'brands', 'seaters'));
    }

    public function available(Request $request)
    {
        // Step 1: Validate input
        $request->validate([
            'pick_location' => ['required', 'integer', Rule::exists('locations', 'id')->where('status', Status::ENABLE)],
            'drop_location' => [
                'required', 'integer',
                Rule::exists('locations', 'id')->where('status', Status::ENABLE)
            ],
            'pick_time' => 'required|date_format:Y-m-d h:i A',
            'drop_time' => 'required|date_format:Y-m-d h:i A|after:pick_time',
        ], [
            'drop_location.different' => 'Please choose a different drop-off location.',
        ]);

        // Step 2: Parse dates
        $pickTime = Carbon::createFromFormat('Y-m-d h:i A', $request->pick_time);
        $dropTime = Carbon::createFromFormat('Y-m-d h:i A', $request->drop_time);

        // Step 3: Query available vehicles (not booked in selected range)
        $vehicles = Vehicle::active()
            ->whereDoesntHave('confirmedRents', function ($query) use ($pickTime, $dropTime) {
                $query->where(function ($q) use ($pickTime, $dropTime) {
                    $q->whereBetween('pick_time', [$pickTime, $dropTime])
                        ->orWhereBetween('drop_time', [$pickTime, $dropTime])
                        ->orWhere(function ($q2) use ($pickTime, $dropTime) {
                            $q2->where('pick_time', '<', $pickTime)
                                ->where('drop_time', '>', $dropTime);
                        });
                });
            })
            ->with(['brand', 'seater']) // Optional eager loading
            ->get();

        $brands = Brand::active()->withCount('vehicles')->orderBy('name')->get();
        $seaters = Seater::active()->withCount('vehicles')->orderBy('number')->get();

        // Step 4: Get active locations (for potential reuse in view)
        $locations = Location::active()->orderBy('name')->get();

        // Step 5: Save time/location selections in session (if needed later)
        session([
            'selected_pick_time' => $pickTime->format('Y-m-d h:i A'),
            'selected_drop_time' => $dropTime->format('Y-m-d h:i A'),
            'selected_pick_location' => $request->pick_location,
            'selected_drop_location' => $request->drop_location,
        ]);

        // Step 6: Page title and return view
        $pageTitle = 'Available Vehicles';

        return view('template.vehicles.index', compact(
            'vehicles',
            'pageTitle',
            'locations',
            'brands',
            'seaters',
        ));
    }

    public function brand($brand_id, $slug)
    {
        $brands = Brand::active()->withCount('vehicles')->orderBy('name')->get();
        $seaters = Seater::active()->withCount('vehicles')->orderBy('number')->get();

// Step 1: Get one dossier ID per model for this brand
        $vehicleIds = Vehicle::selectRaw('MIN(id) as id')
            ->where('status', Status::ENABLE)
            ->where('brand_id', $brand_id)
            ->groupBy('model')
            ->pluck('id');

        // Step 2: Fetch full dossier details for those IDs
        $vehicles = Vehicle::whereIn('id', $vehicleIds)->latest()->paginate(6);        $pageTitle = 'Brand Vehicles';
        return view('template.vehicles.index', compact('vehicles', 'pageTitle', 'brands', 'seaters'));
    }

    public function seater($seat_id)
    {
        $brands = Brand::active()->withCount('vehicles')->orderBy('name')->get();
        $seaters = Seater::active()->withCount('vehicles')->orderBy('number')->get();

// Step 1: Get one dossier ID per model for this seater
        $vehicleIds = Vehicle::selectRaw('MIN(id) as id')
            ->where('status', Status::ENABLE)
            ->where('seater_id', $seat_id)
            ->groupBy('model')
            ->pluck('id');

        // Step 2: Fetch full dossier details for those IDs
        $vehicles = Vehicle::whereIn('id', $vehicleIds)->latest()->paginate(6);        $pageTitle = 'Vehicles Seating';

        return view('template.vehicles.index', compact('vehicles', 'pageTitle', 'brands', 'seaters'));
    }

}
