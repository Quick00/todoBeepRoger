<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ToDo;
use Illuminate\Support\Str;

class ToDoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ToDo::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {



            $exploded = explode(',', $request->image);
            $decoded = base64_decode($exploded[1]);

            if (str::contains($exploded[0], 'jpg')) {
                $extension = 'jpg';
            } else {
                $extension = 'png';
            }

            $filename = str::random() . '.' . $extension;

            $path = public_path() . '\img' . '/' . $filename;

            file_put_contents($path, $decoded);



        ToDo::create( $request->except('image') + [
            'image' => $filename
            ]);


        return(['message' => 'Dit werkt boi']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $todo = ToDo::findOrFail($id);
        $todo->update($request->all());
        return ['message' => 'Dit is goeie bro'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $todo = ToDo::findOrFail($id);
        $todo->destroy($id);
        return ['message' => 'Verwijderd'];
    }
}
