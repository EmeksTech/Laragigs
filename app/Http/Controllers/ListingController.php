<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    //show all listings
    public function index()
    {
        // dd(request('tag'));
        return view('listings.index', [
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(6)
        ]);
    }

    //show one listing
    public function show(Listing $listing)
    {
        return view('listings.show', [
            'listing' => $listing
        ]);
    }

    //show create form
    public function create()
    {
        return view('listings.create');
    }

    //store listing
    public function store(Request $request)
    {
        //dd($request-)

        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required',
        ]);

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $formFields['user_id'] = auth()->id();

        Listing::create($formFields);
        return redirect('/')->with('message', 'Listing created successfully!');
    }

    //show edit form
    public function edit(Listing $listing)
    {
        return view('listings.edit', ['listing' => $listing]);
    }

    public function update(Request $request,  Listing $listing)
    {
        //dd($request-)
        //make sure logged in user owns the listing
        if ($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        $formFields = $request->validate([



            'title' => 'required',
            'company' => ['required'],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required',
        ]);

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }



        $listing->update($formFields);
        return back()->with('message', 'Listing updated successfully!');
    }

    public function destroy(Listing $listing)
    {

        //make sure logged in user owns the listing
        if ($listing->user_id != auth()->id()) {

            abort(403, 'Unauthorized Action');
            
        }

        $listing->delete();
        return redirect('/')->with('message', 'Listing deleted successfully!');

    }

    //manage listing funtion
    function manage(){
        return view('listings.manage', ['listings' => auth()->user()->listings()->get()]);
    }
}
