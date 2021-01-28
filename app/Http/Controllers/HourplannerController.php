<?php

namespace App\Http\Controllers;

use App\Models\Hourplanner;
use Illuminate\Http\Request;

class HourplannerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Hourplanner::all())
                ->addColumn('action', 'hours-action')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('pages/hourplanner');
    }

    public function store(Request $request)
    {

        $hour_id = $request->id;

        $hours   =   Hourplanner::updateOrCreate(
            [
                'id' => $hour_id
            ],
            [
                'hour_one' => $request->hour_one,
                'hour_two' => $request->hour_two,
                'total_hours' => $request->total_hours,
            ]);

        return Response()->json($hours);

    }

    public function edit(Request $request)
    {
        $where = array('id' => $request->id);
        $hour  = Hourplanner::where($where)->first();

        return Response()->json($hour);
    }

    public function destroy(Request $request)
    {
        $hour = Hourplanner::where('id',$request->id)->delete();

        return Response()->json($hour);
    }
}
