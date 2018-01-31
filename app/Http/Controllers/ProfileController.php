<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Auth;
use App\User;
use Session;
use Validator;
use App\Data;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = \Auth::user();

if($request->year && $request->month){
        $year = $request->year;
        $month = $request->month;
} else {
       $year = date('Y');
       $month = date('m');
}
    $data = $user->datas()
            // ->where('user_id',\Auth::id())
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get();

        return view('profile.index')->with(['datas'=>$data,'user'=>$user,'year'=>$year,'month'=>$month]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id,Request $request)
    {
        if(!$id){$id = \Auth::user();}
        $rules = array(
            'day'       => 'required|numeric|max:31',
            'time'      => 'required|numeric',
            'com' => 'required|max:255',
        );
        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            // $user_id = $id;
            $request->user_id = $id;
            $new = Data::create(request([
    'day' => request('day'),
    'time' => request('time'),
    'com' => request('com'),
    'hash' => md5(date('Y-m-d H:i:s')),
    'user_id' => \Auth::user()->id
            ]));
            $new->save();

            // redirect
            Session::flash('message', 'Successfully!');
        return redirect()->back();
    }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'day'       => 'required|numeric|max:31',
            'time'      => 'required|numeric',
            'com' => 'required|max:255',
        );
        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $nerd = Data::where('hash',$request->hash)->first();

            if(!$nerd){
                        $new = Data::create([
    'day' => $request->day,
    'time' => $request->time,
    'com' => $request->com,
    'hash' => md5(date('Y-m-d H:i:s')),
    'user_id' => \Auth::user()->id
            ]);
            $new->save();

            }
                else {
            $nerd->day       = $request->day;
            $nerd->time      = $request->time;
            $nerd->com = $request->com;
            $nerd->save();
        }
            // redirect
            Session::flash('message', 'Successfully !');
        return redirect()->back();
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Data::findOrFail($id);
        $data->delete();

        return redirect()->back()
            ->with('flash_message',
             'Data successfully deleted.');
    }

    // public function addItem(Request $request)
    // {
    //     $rules = array(
    //             'name' => 'required|alpha_num',
    //     );
    //     $validator = Validator::make(Input::all(), $rules);
    //     if ($validator->fails()) {
    //         return Response::json(array(
    //                 'errors' => $validator->getMessageBag()->toArray(),
    //         ));
    //     } else {
    //         $data = new Data();
    //         $data->name = $request->name;
    //         $data->save();
    //         return response()->json($data);
    //     }
    // }
    // public function readItems(Request $req)
    // {
    //     $data = Data::all();
    //     return view('welcome')->withData($data);
    // }
    // public function editItem(Request $req)
    // {
    //     $data = Data::find($req->id);
    //     $data->name = $req->name;
    //     $data->save();
    //     return response()->json($data);
    // }
    // public function deleteItem(Request $req)
    // {
    //     Data::find($req->id)->delete();
    //     return response()->json();
    // }
}
