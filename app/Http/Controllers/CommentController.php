<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return abort(404);
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
        }

        $validated = $request->validate([
            'item_id' => 'required|integer',
            'text' => 'required',
        ],[
            'text.required' => 'A megjegyzés kitöltése kötelező!',
        ]);

        $url_id = last(explode('/',$request->headers->get('referer'))) ?? null;

        if($validated['item_id'] != $url_id){
            return abort(403, 'Hozzáférés megtagadva: a megadott id nem egyezik az url-ben lévővel!');
        }

        if(!Item::find($validated['item_id']))
            return abort(404, 'A megadott elem nem létezik!');

        $comment = new Comment($validated);
        $comment->author()->associate(Auth::user())->save();

        return redirect()->back();
        
           
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        $this->authorize('update', $comment);
        $item = $comment->item;

        return redirect()->route('items.show', $item)->with('editcomment', $comment);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'item_id' => 'required|integer',
            'text' => 'required',
        ],[
            'text.required' => 'A megjegyzés kitöltése kötelező! Szerkesztés sikertelen!',
        ]);

        $url_id = last(explode('/',$request->headers->get('referer'))) ?? null;

        if($validated['item_id'] != $url_id){
            return abort(403, 'Hozzáférés megtagadva: a megadott id nem egyezik az url-ben lévővel!');
        }

        if(!Item::find($validated['item_id']))
            return abort(404, 'A megadott elem nem létezik!');

        $comment->update($validated);


        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return redirect()->back();
    }
}
