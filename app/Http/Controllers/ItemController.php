<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->authorizeResource(Item::class, 'Item');
    // }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('items.index', [
            'items' => Item::orderBy("obtained", "desc")->paginate(9),
            'labels' => Label::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::user()){
            return abort(403, 'Hozzáférés megtadagva: jelentkezz be!');
        }elseif(!Auth::user()->is_admin){
            return abort(403, 'Hozzáférés megtagadva: nincs admin jogosultságod.');
        }
        return view('items.create', ['labels' => Label::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()){
            return abort(403, 'Hozzáférés megtadagva: jelentkezz be!');
        }elseif(!Auth::user()->is_admin){
            return abort(403, 'Hozzáférés megtagadva: nincs admin jogosultságod.');
        }

        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'obtained' => 'required|date',
            'image' => 'nullable|image',
            'labels' => 'nullable',
            'labels.*' => 'distinct|integer|exists:labels,id',
        ],
        [
            'name.required' => 'A név kitöltése kötelező!',
            'name.max' => 'A név legfeljebb 255 karakter hosszú legyen!',
            'description.required' => 'A leírás kitöltése kötelező!',
            'obtained.required' => 'A beszerzés dátumának kitöltése kötelező!',
            'obtained.date' => 'A beszerzési dátum csak dátum lehet',
            'image.image' => 'Csak kép fájl lehet!',
            'labels.*.distinct' => 'A címkék nem ismétlődhetnek!',
            'labels.*.integer' => 'Hiba a jelölönégyzek értékével!',
            'labels.*.exists' => 'A címke nem létezik!',

        ]
        );

        if ($request->hasFile('image')){
            $file = $request->file('image');
            $filename = $file->hashName();
            Storage::disk('public')->put('images/' . $filename, $file->get());
            $validated['image'] = $filename;
        }

        Item::create($validated)->labels()->sync($request->labels);

        Session::flash('message', 'Az tárgy sikeresen létrehozva!');

        return redirect()->route('items.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {

        return view('items.show', ['item' => $item,
            'labels' => $item->labels()->get(),
            'comments' => $item->comments()->with('author')->orderBy('created_at', 'desc')->get(),
        ]);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        if(!Auth::user()){
            return abort(403, 'Hozzáférés megtadagva: jelentkezz be!');
        }elseif(!Auth::user()->is_admin){
            return abort(403, 'Hozzáférés megtagadva: nincs admin jogosultságod.');
        }
        return view('items.edit', ['item' => $item,
            'itemlabels' => $item->labels()->get(),
            'labels' => Label::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {

        if(!Auth::user()){
            return abort(403, 'Hozzáférés megtadagva: jelentkezz be!');
        }elseif(!Auth::user()->is_admin){
            return abort(403, 'Hozzáférés megtagadva: nincs admin jogosultságod.');
        }

        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'obtained' => 'required|date',
            'image' => 'nullable|image',
            'labels' => 'nullable',
            'labels.*' => 'distinct|integer|exists:labels,id',
        ],
        [
            'name.required' => 'A név kitöltése kötelező!',
            'name.max' => 'A név legfeljebb 255 karakter hosszú legyen!',
            'description.required' => 'A leírás kitöltése kötelező!',
            'obtained.required' => 'A beszerzés dátumának kitöltése kötelező!',
            'obtained.date' => 'A beszerzési dátum csak dátum lehet',
            'image.image' => 'Csak kép fájl lehet!',
            'labels.*.distinct' => 'A címkék nem ismétlődhetnek!',
            'labels.*.integer' => 'Hiba a jelölönégyzek értékével!',
            'labels.*.exists' => 'A címke nem létezik!',

        ]
    );

        if ($request->hasFile('image')){
            $file = $request->file('image');
            $filename = $file->hashName();
            Storage::disk('public')->put('images/' . $filename, $file->get());
            $validated['image'] = $filename;
        }

        $item->update($validated);
        $item->labels()->sync($request->labels);

        Session::flash('message', 'Az tárgy sikeresen szerkesztve!');

        return redirect()->route('items.show', ['item' => $item]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        if(!Auth::user()){
            return abort(403, 'Hozzáférés megtadagva: jelentkezz be!');
        }elseif(!Auth::user()->is_admin){
            return abort(403, 'Hozzáférés megtagadva: nincs admin jogosultságod.');
        }

        $item->delete();

        Session::flash('message', 'Az tárgy sikeresen törölve!');

        return redirect()->route('items.index');
    }
}
