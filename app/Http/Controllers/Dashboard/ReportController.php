<?php

namespace App\Http\Controllers\Dashboard;

use App\Consultation;
use App\Lawer;
use App\Product;
use App\Store;
use App\Type;
use App\Uservistor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

use App\Lawercase;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use GeoIP;

class ReportController extends Controller
{
    public function reportcases(Request $request)
    {


        //abort_unless(\Gate::allows('read_categories'), 403);

        $cases = Lawercase::all();

        return view('dashboard.cases.reports', compact('cases'));

    }//end of index

    public function reportproducts(Request $request){


        $products=Product::where('family_id',Auth::id())->get();

        return view('dashboard.products.reports', compact('products'));

    }


    public function reportseller(Request $request){


        $sellers = Store::latest()->paginate(Paginate_number);


        return view('dashboard.sellers.reports', compact('sellers'));


    }



    public function reportconsulation(Request $request)
    {


        $consultations = Consultation::latest()->paginate(Paginate_number);


        return view('dashboard.consultations.reports', compact('consultations'));


    }





    public function reportusers(Request $request)
    {


        $users = User::where(function ($q) use ($request) {

            return $q->when($request->search, function ($query) use ($request) {
                return $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        })->latest()->paginate(Paginate_number);
//       dd($users);
        return view('dashboard.users.reports', compact('users'));


    }

    public function reportvisitor(Request $request)
    {

        $visitors = Uservistor::all();

//      dd(geoip()->getLocation(	"197.61.85.50"));

        return view('dashboard.users.reportsvisitors', compact('visitors'));


    }

    public function destroy($id)
    {

        $visitors = Uservistor::find($id);

        $visitors->delete();

        return back();

    }


}//end of controller
