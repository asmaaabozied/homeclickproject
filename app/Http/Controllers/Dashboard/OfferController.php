<?php

namespace App\Http\Controllers\Dashboard;

use App\Category;
use App\Catogery;
use App\Mail\MailMessage;
use App\Notification;
use App\Offer;
use App\Product;
use App\Store;
use App\User;
use Carbon\Carbon;

//use App\Image;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

//use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;


class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */


    public function index(Request $request)
    {
        $offers = Offer::where('store_id', Auth::id())->latest()->paginate(Paginate_number);


        return view('dashboard.offers.index', compact('offers'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {

        $products = Product::where('family_id', Auth::id())->get()->pluck('name', 'id');

        $catogeries = Category::where('store_id', Auth::id())->get()->pluck('name', 'id');

        $offers = Store::get()->pluck('name', 'id');

        return view('dashboard.offers.create', compact('products', 'catogeries', 'offers'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */


    public function store(Request $request)
    {


        $offer = Offer::create($request->except(['_token', '_method']));
        $offer->store_id = Auth::id();

        $offer->save();


        $name = $offer->store->name;


        $offers = $offer->discount;


        $contentar = 'حتى نهايه الأسبوع' . $name . ' ع مطبخ ' . $offers . ' عرض جديد خصم ';

        $contenten = 'New offer discount' . $offers . 'on kitchen' . $name . 'until the end of the week ';


        $users = User::where('type', 'User')->get();

        foreach ($users as $user)

            $notification = Notification::create([
                'content_ar' => $contentar,
                'content_en' => $contenten,
                'type' => 'Offer',
                'user_id' => $user->id,


            ]);

        $user = User::get()->pluck('firebase_token');


        $createdat = $notification->created_at->diffForHumans(Carbon::now());

        if (count($user)) {
            $title = $name;
            $content = $contentar;
            $data = [
                'created_at' => $createdat,
                'user_name' => $name,
            ];
            $send = notifyByFirebase($title, $content, $user, $data);
            info("firebase result: " . $send);
        }


        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.offers.index');

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {


    }


    /**
     * Show the form for editing the specified resource.
     * @param Consultation $consultation
     * @return Response
     */
    public function edit(Offer $offer)
    {

        $products = Product::where('family_id', Auth::id())->get()->pluck('name', 'id');

        $catogeries = Category::where('store_id', Auth::id())->get()->pluck('name', 'id');

        $offers = Store::get()->pluck('name', 'id');

        return view('dashboard.offers.edit', compact('offer', 'products', 'catogeries', 'offers'));


    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Consultation $consultation
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $offer = Offer::find($id);


        $attributes = $request->all();


        $offer->update($attributes);


        session()->flash('success', __('site.updated_successfully'));

        return redirect()->route('dashboard.offers.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param Consultation $consultation
     * @return Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        Offer::find($id)->delete();
        session()->flash('success', __('site.deleted_successfully'));

        return back();
    }

    public function statusConsultations(Request $request)
    {
        $info = Consultation::find($request->id);


        if ($info->status == "finished") {
            $info->status = "waiting";
        } elseif ($info->status == "waiting") {
            $info->status = "reply";

            $typeconslution_id = $info->Typeconsultation->id;
            $title = $info->Typeconsultation->name;

            $content = $info->details;

            $contents = "تم قبول طلبك و سيقوم أحد مستشارينا بالتواصل معك في أقرب وقت";


            $user = $info->user->notifications;

            $notification = $info->user->notifications()->create([
                'title' => $title,
                'content' => $content,
                'type' => 'consulations',
                'typeconslution_id' => $typeconslution_id

            ]);

            $user_id = $info->user->id;

            $user = User::where('id', $user_id)->get()->pluck('firebase_token');


            $createdat = $notification->created_at->diffForHumans(Carbon::now());

            $tokens = $info->user->firebase_token;


//        $arrtoken = [$tokens];
            if (count($user)) {
                $title = $title;
                $contents = $contents;
                $data = [
                    'created_at' => $createdat,
                    'user_name' => $info->user->name,
                ];
                $send = notifyByFirebase($title, $contents, $user, $data);
                info("firebase result: " . $send);
            }


        } elseif ($info->status == "reply") {
            $info->status = "finished";

        }


        //  $info->save();
        session()->flash('success', __('site.updated_successfully'));
        return back();


    }


}
