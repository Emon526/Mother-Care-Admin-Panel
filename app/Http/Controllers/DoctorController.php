<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $doctors = Doctor::all();
        return view('doctors.index',compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('doctors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'image'=>'required|image|mimes:png,jpg,jpeg|max:2048',
            'doctorname'=>'required',
            'degree'=>'required',
            'speciality'=>'required',
            'workplace'=>'required',
            'biography'=>'required',
            'experience'=>'required',
            'rating'=>'required',
            'appointmentNumber'=>'required',
            'location'=>'required'
        ]);
        

            $file = $request->file('image');
            $imageData = file_get_contents($file);
            $imageBase64 = base64_encode($imageData);

        Doctor::create([
            'image'=> $imageBase64,
            'doctorname'=>$request->doctorname,
            'degree'=>$request->degree,
            'speciality'=>$request->speciality,
            'workplace'=>$request->workplace,
            'biography'=>$request->biography,
            'experience'=>$request->experience,
            'rating'=>$request->rating,
            'appointmentNumber'=>$request->appointmentNumber,
            'location'=>$request->location,
        ]);
        return redirect()->route('doctors.index');
    }

     /**
     * Display the specified resource.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function show(Doctor $doctor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function edit(Doctor $doctor)
    {
        //
        return view('doctors.edit',compact('doctor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Doctor $doctor)
    {
        

        $request->validate([

            'image'=>'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'doctorname'=>'required',
            'degree'=>'required',
            'speciality'=>'required',
            'workplace'=>'required',
            'biography'=>'required',
            'experience'=>'required',
            'rating'=>'required',
            'appointmentNumber'=>'required',
            'location'=>'required'
        ]);
        
        $imageBase64 = $doctor->image; // default to existing value
        
        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $imageData = file_get_contents($file);
            $imageBase64 = base64_encode($imageData);
        }
  
        $doctor->update([
       
            'image'=> $imageBase64,
            'doctorname'=>$request->doctorname,
            'degree'=>$request->degree,
            'speciality'=>$request->speciality,
            'workplace'=>$request->workplace,
            'biography'=>$request->biography,
            'experience'=>$request->experience,
            'rating'=>$request->rating,
            'appointmentNumber'=>$request->appointmentNumber,
            'location'=>$request->location,
        ]);
     
        return redirect()->route('doctors.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctor $doctor)
    {
        //
        $doctor->delete();
        return redirect()->route('doctors.index');
    }
}