<?php
//
//namespace App\Http\Controllers;
//
//use App\Constants\Status;
//use App\Models\AdminNotification;
//use App\Models\Frontend;
//use App\Models\Language;
//use App\Models\Location;
//use App\Models\Page;
//use App\Models\Plan;
//use App\Models\Subscriber;
//use App\Models\SupportMessage;
//use App\Models\SupportTicket;
//use Carbon\Carbon;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Cookie;
//
//class SiteController extends Controller {
////    public function index() {
////        $reference = @$_GET['reference'];
////        if ($reference) {
////            session()->put('reference', $reference);
////        }
////
////        $pageTitle   = 'Home';
////
////        return view('template.home', compact('pageTitle'));
////    }
//
//    public function index() {
//        $reference = @$_GET['reference'];
//        if ($reference) {
//            session()->put('reference', $reference);
//        }
//
//        $pageTitle = 'Home';
//
//        // Get the homepage sections (slug = '/')
//        $sections = Page::where('slug', '/')->first();
//
//        // Load SEO content if available
//        $seoContents = $sections?->seo_content;
//        $seoImage = @$seoContents->image
//            ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo'))
//            : null;
//
//        return view('template.home', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
//    }
//
//
//    public function pages($slug) {
//
//        $page        = Page::where('slug', $slug)->firstOrFail();
//        $pageTitle   = $page->name;
//        $sections    = $page->secs;
//        $seoContents = $page->seo_content;
//        $seoImage    = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
//
//        return view('template.pages', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
//    }
//
//    public function blogs() {
//        $pageTitle   = 'Blogs';
//        $blogs       = Frontend::where('data_keys', 'blog.element')->latest()->paginate(getPaginate());
//        $sections    = Page::where('slug', 'blog')->first();
//        $seoContents = $sections->seo_content;
//        $seoImage    = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
//        return view('template.blogs', compact('blogs', 'pageTitle', 'seoContents', 'seoImage', 'sections'));
//    }
//
//    public function plans() {
//        $pageTitle   = 'Pricing Plans';
//        $plans       = Plan::active()->get();
//        $locations   = Location::active()->orderBy('name')->get();
//        $sections    = Page::where('tempname', 'classic')->where('slug', 'plan')->first();
//        $seoContents = $sections->seo_content;
//        $seoImage    = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
//        return view('templates.classic.plans.index', compact('plans', 'pageTitle', 'locations', 'seoContents', 'seoImage', 'sections'));
//    }
//
//    public function contact() {
//        $pageTitle   = "Contact Us";
//        $user        = auth()->user();
//        $sections    = Page::where('slug', 'contact')->first();
////        dd($sections);
//        $seoContents = $sections->seo_content;
//        $seoImage    = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
//        return view('template.contact', compact('pageTitle', 'user', 'sections', 'seoContents', 'seoImage'));
//    }
//
//    public function contactSubmit(Request $request) {
//        $request->validate([
//            'name'    => 'required',
//            'email'   => 'required',
//            'subject' => 'required|string|max:255',
//            'message' => 'required',
//        ]);
//
//        $request->session()->regenerateToken();
//
//        if (!verifyCaptcha()) {
//            $notify[] = ['error', 'Invalid captcha provided'];
//            return back()->withNotify($notify);
//        }
//
//        $random = getNumber();
//
//        $ticket           = new SupportTicket();
//        $ticket->user_id  = auth()->id() ?? 0;
//        $ticket->name     = $request->name;
//        $ticket->email    = $request->email;
//        $ticket->priority = Status::PRIORITY_MEDIUM;
//
//        $ticket->ticket     = $random;
//        $ticket->subject    = $request->subject;
//        $ticket->last_reply = Carbon::now();
//        $ticket->status     = Status::TICKET_OPEN;
//        $ticket->save();
//
//        $adminNotification            = new AdminNotification();
//        $adminNotification->user_id   = auth()->user() ? auth()->user()->id : 0;
//        $adminNotification->title     = 'A new contact message has been submitted';
//        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
//        $adminNotification->save();
//
//        $message                    = new SupportMessage();
//        $message->support_ticket_id = $ticket->id;
//        $message->message           = $request->message;
//        $message->save();
//
//        $notify[] = ['success', 'Ticket created successfully!'];
//
//        return to_route('ticket.view', [$ticket->ticket])->withNotify($notify);
//    }
//
//    public function policyPages($slug) {
//        $policy      = Frontend::where('slug', $slug)->where('data_keys', 'policy_pages.element')->firstOrFail();
//        $pageTitle   = $policy->data_values->title;
//        $seoContents = $policy->seo_content;
//        $seoImage    = @$seoContents->image ? frontendImage('policy_pages', $seoContents->image, getFileSize('seo'), true) : null;
//        return view('template.policy', compact('policy', 'pageTitle', 'seoContents', 'seoImage'));
//    }
//
//    public function changeLanguage($lang = null) {
//        $language = Language::where('code', $lang)->first();
//        if (!$language) {
//            $lang = 'fr';
//        }
//
//        session()->put('lang', $lang);
//        return back();
//    }
//
//    public function blogDetails($slug) {
//        $blog = Frontend::where('slug', $slug)->where('data_keys', 'blog.element')->firstOrFail();
//        $blog->increment('views');
//        $recentBlogs = Frontend::where('data_keys', 'blog.element')->where('slug', '!=', $slug)->take(5)->get();
//        $pageTitle   = 'Blog Detail';
//        $seoContents = $blog->seo_content;
//        $seoImage    = @$seoContents->image ? frontendImage('blog', $seoContents->image, getFileSize('seo'), true) : null;
//        return view('template.blog_details', compact('blog', 'pageTitle', 'recentBlogs', 'seoContents', 'seoImage'));
//    }
//
//    public function cookieAccept() {
//        Cookie::queue('gdpr_cookie', gs('site_name'), 43200);
//    }
//
//    public function cookiePolicy() {
//        $cookieContent = Frontend::where('data_keys', 'cookie.data')->first();
//        abort_if($cookieContent->data_values->status != Status::ENABLE, 404);
//        $pageTitle = 'Cookie Policy';
//        $cookie    = Frontend::where('data_keys', 'cookie.data')->first();
//        return view('template.cookie', compact('pageTitle', 'cookie'));
//    }
//
//    public function placeholderImage($size = null) {
//        $imgWidth  = explode('x', $size)[0];
//        $imgHeight = explode('x', $size)[1];
//        $text      = $imgWidth . '×' . $imgHeight;
//        $fontFile  = realpath('assets/font/solaimanLipi_bold.ttf');
//        $fontSize  = round(($imgWidth - 50) / 8);
//        if ($fontSize <= 9) {
//            $fontSize = 9;
//        }
//        if ($imgHeight < 100 && $fontSize > 30) {
//            $fontSize = 30;
//        }
//
//        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
//        $colorFill = imagecolorallocate($image, 100, 100, 100);
//        $bgFill    = imagecolorallocate($image, 255, 255, 255);
//        imagefill($image, 0, 0, $bgFill);
//        $textBox    = imagettfbbox($fontSize, 0, $fontFile, $text);
//        $textWidth  = abs($textBox[4] - $textBox[0]);
//        $textHeight = abs($textBox[5] - $textBox[1]);
//        $textX      = ($imgWidth - $textWidth) / 2;
//        $textY      = ($imgHeight + $textHeight) / 2;
//        header('Content-Type: image/jpeg');
//        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
//        imagejpeg($image);
//        imagedestroy($image);
//    }
//
//    public function maintenance() {
//        $pageTitle = 'Maintenance Mode';
//        if (gs('maintenance_mode') == Status::DISABLE) {
//            return to_route('home');
//        }
//        $maintenance = Frontend::where('data_keys', 'maintenance.data')->first();
//        return view('template.maintenance', compact('pageTitle', 'maintenance'));
//    }
//
//    public function subscribe() {
//        $rules = [
//            'email' => 'required|email|unique:subscribers,email',
//        ];
//
//        $validator = validator()->make(\request()->all(), $rules);
//        if ($validator->fails()) {
//            return response()->json(['error' => $validator->errors()->getMessages()]);
//        }
//
//        $subscribe        = new Subscriber();
//        $subscribe->email = \request()->email;
//        $subscribe->save();
//
//        return response()->json(['success' => true, 'message' => 'Thanks for subscribe!']);
//    }
//
//}
