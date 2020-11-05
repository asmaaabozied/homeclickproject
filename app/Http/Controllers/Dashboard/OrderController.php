<?php

namespace App\Http\Controllers\Dashboard;


use App\Capon;
use App\Image;
use App\Mail\MailMessage;
use App\Contact;
use App\Inquiry;
use App\Notification;
use App\Order;
use App\Order_Product;
use App\Store;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{


    public function index(Request $request)
    {

        //abort_unless(\Gate::allows('read_jobs'), 403);

        $orders = Order::latest()->paginate(25);


        return view('dashboard.orders.index', compact('orders'));

    }//end of index


    public function change_status($id)
    {


        $info = Order::find($id);


        if ($info->status == "canceled") {
            $info->status = "waiting";


        } elseif ($info->status == "waiting") {
            $info->status = "accept";


            $username = $info->user->name;
            $storename = $info->store->name;


            $contentar = $storename . 'تم قبول الطلب';

            $contenten = $storename . 'The request has been accepted';

            $id = $info->user->id;


            $notification = Notification::create([
                'content_ar' => $contentar,
                'content_en' => $contenten,
                'type' => 'Order',
                'user_id' => $id,


            ]);

            $user = User::where('id', $id)->get()->pluck('firebase_token');


            $createdat = $notification->created_at->diffForHumans(Carbon::now());

            if (count($user)) {
                $title = $storename;
                $content = $contentar;
                $data = [
                    'created_at' => $createdat,
                    'user_name' => $username,
                ];
                $send = notifyByFirebase($title, $content, $user, $data);
                info("firebase result: " . $send);
            }


        } elseif ($info->status == "accept") {
            $info->status = "canceled";


            $username = $info->user->name;
            $storename = $info->store->name;


            $contentar = $storename . 'تم رفض الطلب';

            $contenten = $storename . 'request has been rejected';

            $id = $info->user->id;


            $notification = Notification::create([
                'content_ar' => $contentar,
                'content_en' => $contenten,
                'type' => 'Order',
                'user_id' => $id,


            ]);

            $user = User::where('id', $id)->get()->pluck('firebase_token');


            $createdat = $notification->created_at->diffForHumans(Carbon::now());

            if (count($user)) {
                $title = $storename;
                $content = $contentar;
                $data = [
                    'created_at' => $createdat,
                    'user_name' => $username,
                ];
                $send = notifyByFirebase($title, $content, $user, $data);
                info("firebase result: " . $send);
            }


        }
        $info->save();
        session()->flash('success', __('site.updated_successfully'));
        return back();

    }

    public function detailsuser($id)
    {

        $user_id = Consultation::find($id)->user_id;
        $user = User::find($user_id);

        return view('dashboard.consultations.detail', compact('user'));


    }


    public function reportorders(Request $request)
    {
        $orders = Order::latest()->paginate(25);


        return view('dashboard.orders.reports', compact('orders'));


    }

    public function show($id)
    {
        $order = Order::find($id);

        $orders = $order->products()->get();



        return view('dashboard.orders.show', compact('orders','order'));


    }

    public function downloadFile($id)
    {

        $file = \App\Image::find($id);
        $pathToFile = public_path() . '/uploads/' . $file->image;
        return response()->download($pathToFile, $file->image);

    }


        public function create()

    {
        // abort_unless(\Gate::allows('create_jobs'), 403);


        return view('dashboard.orders.create');

    }//end of create


    public
    function store(Request $request)
    {


    }//end of store

    public
    function edit($id)
    {
        $order = Order::find($id);

        $users = User::where('type', 'User')->get()->pluck('name', 'id');

        $capons = Capon::get()->pluck('discount', 'id');

        $offer = Store::get()->pluck('name', 'id');


        return view('dashboard.orders.edit', compact('order', 'users', 'capons', 'offer'));


    }//end of edit


    public
    function update(Request $request, $id)
    {

        $order = Order::find($id);

        $order->update($request->except(['_token', '_method']));


        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.orders.index');


    }


    public
    function destroy($id)
    {

        //  abort_unless(\Gate::allows('job_delete'), 403);

        $order = Order::find($id);

        $order->delete();

        session()->flash('success', __('site.deleted_successfully'));

        return back();

    }//end of destroy


}//end of controller
