<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Lib\CurlRequest;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\Dossier;
use App\Models\DossierRejection;
use App\Models\Facture;
use App\Models\Fournisseur;
use App\Models\Transaction;
use App\Models\User;
use App\Models\PlanLog;
use App\Models\RentLog;
use App\Models\UserLogin;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Constants\Status;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    public function dashboard()
    {
        $pageTitle = 'Dashboard';

        // User Info
        $widget['total_users']             = User::count();
        $widget['verified_users']          = User::active()->count();
        $widget['email_unverified_users']  = User::emailUnverified()->count();
        $widget['mobile_unverified_users'] = User::mobileUnverified()->count();


        // user Browsing, Country, Operating Log
        $userLoginData = UserLogin::where('created_at', '>=', Carbon::now()->subDays(30))->get(['browser', 'os', 'country']);

        $chart['user_browser_counter'] = $userLoginData->groupBy('browser')->map(function ($item, $key) {
            return collect($item)->count();
        });
        $chart['user_os_counter'] = $userLoginData->groupBy('os')->map(function ($item, $key) {
            return collect($item)->count();
        });
        $chart['user_country_counter'] = $userLoginData->groupBy('country')->map(function ($item, $key) {
            return collect($item)->count();
        })->sort()->reverse()->take(5);


        $deposit['total_deposit_amount']        = Deposit::successful()->sum('amount');
        $deposit['total_deposit_pending']       = Deposit::pending()->count();
        $deposit['total_deposit_rejected']      = Deposit::rejected()->count();
        $deposit['total_deposit_charge']        = Deposit::successful()->sum('charge');

         //Vehicle booking
         $widget['total_vehicle_booking']     = RentLog::active()->count();
         $widget['upcoming_vehicle_booking']  = RentLog::active()->upcoming()->count();
         $widget['running_vehicle_booking']   = RentLog::active()->running()->count();
         $widget['completed_vehicle_booking'] = RentLog::active()->completed()->count();

           //Plan booking
         $widget['total_plan_booking']     = PlanLog::active()->count();
         $widget['upcoming_plan_booking']  = PlanLog::active()->upcoming()->count();
         $widget['running_plan_booking']   = PlanLog::active()->running()->count();
         $widget['completed_plan_booking'] = PlanLog::active()->completed()->count();

        return view('admin.dashboard', compact('pageTitle', 'widget', 'chart', 'deposit', 'widget'));
    }




    public function depositAndWithdrawReport(Request $request)
    {

        $diffInDays = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date));

        $groupBy = $diffInDays > 30 ? 'months' : 'days';
        $format = $diffInDays > 30 ? '%M-%Y'  : '%d-%M-%Y';

        if ($groupBy == 'days') {
            $dates = $this->getAllDates($request->start_date, $request->end_date);
        } else {
            $dates = $this->getAllMonths($request->start_date, $request->end_date);
        }
        $deposits = Deposit::successful()
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();



        $data = [];

        foreach ($dates as $date) {
            $data[] = [
                'created_on' => $date,
                'deposits' => getAmount($deposits->where('created_on', $date)->first()?->amount ?? 0)
            ];
        }

        $data = collect($data);

        // Monthly Deposit & Withdraw Report Graph
        $report['created_on']   = $data->pluck('created_on');
        $report['data']     = [
            [
                'name' => 'Deposited',
                'data' => $data->pluck('deposits')
            ]
        ];

        return response()->json($report);
    }

    public function transactionReport(Request $request)
    {

        $diffInDays = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date));

        $groupBy = $diffInDays > 30 ? 'months' : 'days';
        $format = $diffInDays > 30 ? '%M-%Y'  : '%d-%M-%Y';

        if ($groupBy == 'days') {
            $dates = $this->getAllDates($request->start_date, $request->end_date);
        } else {
            $dates = $this->getAllMonths($request->start_date, $request->end_date);
        }

        $plusTransactions   = Transaction::where('trx_type', '+')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();

        $minusTransactions  = Transaction::where('trx_type', '-')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();


        $data = [];

        foreach ($dates as $date) {
            $data[] = [
                'created_on' => $date,
                'credits' => getAmount($plusTransactions->where('created_on', $date)->first()?->amount ?? 0),
                'debits' => getAmount($minusTransactions->where('created_on', $date)->first()?->amount ?? 0)
            ];
        }

        $data = collect($data);

        // Monthly Deposit & Withdraw Report Graph
        $report['created_on']   = $data->pluck('created_on');
        $report['data']     = [
            [
                'name' => 'Plus Transactions',
                'data' => $data->pluck('credits')
            ],
            [
                'name' => 'Minus Transactions',
                'data' => $data->pluck('debits')
            ]
        ];

        return response()->json($report);
    }


    private function getAllDates($startDate, $endDate)
    {
        $dates = [];
        $currentDate = new \DateTime($startDate);
        $endDate = new \DateTime($endDate);

        while ($currentDate <= $endDate) {
            $dates[] = $currentDate->format('d-F-Y');
            $currentDate->modify('+1 day');
        }

        return $dates;
    }

    private function  getAllMonths($startDate, $endDate)
    {
        if ($endDate > now()) {
            $endDate = now()->format('Y-m-d');
        }

        $startDate = new \DateTime($startDate);
        $endDate = new \DateTime($endDate);

        $months = [];

        while ($startDate <= $endDate) {
            $months[] = $startDate->format('F-Y');
            $startDate->modify('+1 month');
        }

        return $months;
    }


    public function profile()
    {
        $pageTitle = 'Profile';
        $admin = auth('admin')->user();
        return view('admin.profile', compact('pageTitle', 'admin'));
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ]);
        $user = auth('admin')->user();

        if ($request->hasFile('image')) {
            try {
                $old = $user->image;
                $user->image = fileUploader($request->image, getFilePath('adminProfile'), getFileSize('adminProfile'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        $notify[] = ['success', 'Profile updated successfully'];
        return to_route('admin.profile')->withNotify($notify);
    }

    public function password()
    {
        $pageTitle = 'Password Setting';
        $admin = auth('admin')->user();
        return view('admin.password', compact('pageTitle', 'admin'));
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $user = auth('admin')->user();
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password doesn\'t match!!'];
            return back()->withNotify($notify);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        $notify[] = ['success', 'Password changed successfully.'];
        return to_route('admin.password')->withNotify($notify);
    }

    public function notifications()
    {
        $notifications = AdminNotification::orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        $hasUnread = AdminNotification::where('is_read', Status::NO)->exists();
        $hasNotification = AdminNotification::exists();
        $pageTitle = 'Notifications';
        return view('admin.notifications', compact('pageTitle', 'notifications', 'hasUnread', 'hasNotification'));
    }


    public function index()
    {
        $pageTitle = 'Dashboard';

        // quick KPIs
        $widget = [
            'dossiers_total'    => Dossier::count(),
            'dossiers_pending'  => Dossier::where('status', 'PENDING')->count(),
            'dossiers_approved' => Dossier::where('status', 'APPROVED')->count(),
            'dossiers_rejected' => Dossier::where('status', 'REJECTED')->count(),

            'fournisseurs_total' => Fournisseur::count(),

            // Factures = table factures (each linked to a dossier)
            'factures_total'    => Facture::whereHas('dossier')->count(),

            // Amounts (still from dossiers – keep if $montant_ttc lives on dossiers)
            'ttc_sum' => (float) Dossier::sum('montant_ttc'),
            'ttc_this_month' => (float) Dossier::whereBetween('engagement_date', [
                now()->startOfMonth(), now()->endOfMonth()
            ])->sum('montant_ttc'),
        ];

        // recent rejections (last 30 days)
        $rejections_30d = DossierRejection::where('event', 'REJECT')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        return view('admin.dashboard', compact('pageTitle', 'widget', 'rejections_30d'));
    }

    /** line/area: dossiers events (created/approved/rejected) grouped by day or month */
    public function dossierChart(Request $request)
    {
        [$start, $end, $fmt, $dates] = $this->dateBucket($request);

        // created (by engagement_date)
        $created = Dossier::whereBetween('engagement_date', [$start, $end])
            ->selectRaw("DATE_FORMAT(engagement_date, '{$fmt}') as bucket, COUNT(*) c")
            ->groupBy('bucket')->pluck('c', 'bucket');

        // approved: updated_at with status=APPROVED
        $approved = Dossier::where('status', 'APPROVED')
            ->whereBetween('updated_at', [$start, $end])
            ->selectRaw("DATE_FORMAT(updated_at, '{$fmt}') as bucket, COUNT(*) c")
            ->groupBy('bucket')->pluck('c', 'bucket');

        // rejected: from log table
        $rejected = DossierRejection::where('event', 'REJECT')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw("DATE_FORMAT(created_at, '{$fmt}') as bucket, COUNT(*) c")
            ->groupBy('bucket')->pluck('c', 'bucket');

        $series = [
            ['name' => 'Créés',     'data' => []],
            ['name' => 'Approuvés', 'data' => []],
            ['name' => 'Rejetés',   'data' => []],
        ];

        foreach ($dates as $d) {
            $series[0]['data'][] = (int)($created[$d]  ?? 0);
            $series[1]['data'][] = (int)($approved[$d] ?? 0);
            $series[2]['data'][] = (int)($rejected[$d] ?? 0);
        }

        return response()->json(['created_on' => array_values($dates), 'data' => $series]);
    }

    /** column: total TTC (and optional HT) grouped by day/month */
    public function amountChart(Request $request)
    {
        [$start, $end, $fmt, $dates] = $this->dateBucket($request);

        $amounts = Dossier::whereBetween('engagement_date', [$start, $end])
            ->selectRaw("DATE_FORMAT(engagement_date, '{$fmt}') as bucket")
            ->selectRaw('SUM(montant_ht)  as ht')
            ->selectRaw('SUM(montant_ttc) as ttc')
            ->groupBy('bucket')
            ->get()
            ->keyBy('bucket');

        $series = [
            ['name' => 'Montant TTC', 'data' => []],
            ['name' => 'Montant HT',  'data' => []],
        ];

        foreach ($dates as $d) {
            $row = $amounts[$d] ?? null;
            $series[0]['data'][] = (float)($row->ttc ?? 0);
            $series[1]['data'][] = (float)($row->ht  ?? 0);
        }

        return response()->json(['created_on' => array_values($dates), 'data' => $series]);
    }

    /** pie/bar: top fournisseurs by TTC in range (default 30d) */
    public function topFournisseursChart(Request $request)
    {
        $start = Carbon::parse($request->get('start_date', now()->subDays(30)->toDateString()))->startOfDay();
        $end   = Carbon::parse($request->get('end_date', now()->toDateString()))->endOfDay();

        $rows = Dossier::select('fournisseur_id', DB::raw('SUM(montant_ttc) as total_ttc'))
            ->whereBetween('engagement_date', [$start, $end])
            ->groupBy('fournisseur_id')
            ->with('fournisseur:id,name')
            ->orderByDesc('total_ttc')
            ->limit(7)
            ->get();

        $labels = $rows->map(fn($r) => optional($r->fournisseur)->name ?? "ID #{$r->fournisseur_id}");
        $data   = $rows->pluck('total_ttc')->map(fn($v) => (float)$v);

        return response()->json(['labels' => $labels, 'data' => $data]);
    }

    /** helper: decide daily vs monthly buckets and produce a full label list */
    private function dateBucket(Request $request): array
    {
        $start = Carbon::parse($request->get('start_date', now()->subDays(14)->toDateString()))->startOfDay();
        $end   = Carbon::parse($request->get('end_date', now()->toDateString()))->endOfDay();

        $diff = $start->diffInDays($end);
        $fmt  = $diff > 30 ? '%m-%Y' : '%d-%m-%Y';

        $dates  = [];
        $cursor = (clone $start);

        if ($diff > 30) {
            while ($cursor <= $end) {
                $dates[] = $cursor->format('m-Y');
                $cursor->addMonthNoOverflow()->startOfMonth();
            }
        } else {
            while ($cursor <= $end) {
                $dates[] = $cursor->format('d-m-Y');
                $cursor->addDay();
            }
        }

        return [$start, $end, $fmt, $dates];
    }



}
