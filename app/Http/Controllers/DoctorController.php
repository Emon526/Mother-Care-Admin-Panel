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
        $responsedoctors = Doctor::all();
        $doctors = [];
        foreach ($responsedoctors as $doctor) {
            $doctors[] = [
                'doctorId' => $doctor->doctorId,
                'image' => $doctor->image,
                'doctorname' => json_decode($doctor->doctorname, true),
                'degree' => json_decode($doctor->degree, true),
                'speciality' => json_decode($doctor->speciality, true),
                'workplace' => json_decode($doctor->workplace, true),
                'biography' => json_decode($doctor->biography, true),
                'experience'=>$doctor->experience,
                'rating'=>$doctor->rating,
                'appointmentNumber'=>$doctor->appointmentNumber,
                'location' => json_decode($doctor->location, true)
            ];
        }
        return view('doctors.index',compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    // Get the list of doctors
    $doctors = Doctor::all();

    // Calculate the number of doctors
    $doctorsLength = $doctors->count();

    // Pass the list of doctors and the length to the view
    return view('doctors.create', compact('doctorsLength'));
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
            'doctorId'=>'required',
            'image'=>'required|image|mimes:png,jpg,jpeg|max:2048',
            'bangladoctorname'=>'required',
            'englishdoctorname'=>'required',
            'bangladegree'=>'required',
            'englishdegree'=>'required',
            'banglaspeciality'=>'required',
            'englishspeciality'=>'required',
            'banglaworkplace'=>'required',
            'englishworkplace'=>'required',
            'banglabiography'=>'required',
            'englishbiography'=>'required',
            'experience'=>'required',
            'rating'=>'required',
            'appointmentNumber'=>'required',
            'banglalocation'=>'required',
            'englishlocation'=>'required'
        ]);

            $file = $request->file('image');
            $imageData = file_get_contents($file);
            $imageBase64 = base64_encode($imageData);
        // dd($request->all());

        Doctor::create([
            'doctorId'=>$request->doctorId,
            'image'=> $imageBase64,
            'doctorname'=>json_encode([
                'bn'=>$request->bangladoctorname,
                'en'=>$request->englishdoctorname,
            ]),
            'degree'=>json_encode([
                'bn'=>$request->bangladegree,
                'en'=>$request->englishdegree,
            ]),
            'speciality'=>json_encode([
                'bn'=>$request->banglaspeciality,
                'en'=>$request->englishspeciality,
            ]),
            'workplace'=>json_encode([
                'bn'=>$request->banglaworkplace,
                'en'=>$request->englishworkplace,
            ]),
            'biography'=>json_encode([
                'bn'=>$request->banglabiography,
                'en'=>$request->englishbiography,
            ]),
            'experience'=>$request->experience,
            'rating'=>$request->rating,
            'appointmentNumber'=>$request->appointmentNumber,
            'location'=>json_encode([
                'bn'=>$request->banglalocation,
                'en'=>$request->englishlocation,
            ]),
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
    public function edit($doctor)
    {
        //
        $doctor = Doctor::where('doctorId', $doctor)->firstOrFail();
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
        // dd($request->all());

        $request->validate([
            'image'=>'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'doctorId'=>'required',
            'bangladoctorname'=>'required',
            'englishdoctorname'=>'required',
            'bangladegree'=>'required',
            'englishdegree'=>'required',
            'banglaspeciality'=>'required',
            'englishspeciality'=>'required',
            'banglaworkplace'=>'required',
            'englishworkplace'=>'required',
            'banglabiography'=>'required',
            'englishbiography'=>'required',
            'experience'=>'required',
            'rating'=>'required',
            'appointmentNumber'=>'required',
            'banglalocation'=>'required',
            'englishlocation'=>'required'
        ]);
        
        $imageBase64 = $doctor->image; // default to existing value
        
        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $imageData = file_get_contents($file);
            $imageBase64 = base64_encode($imageData);
        }
  
        $doctor->update([
       
            'image'=> $imageBase64,
            'doctorId'=>$request->doctorId,
            'doctorname'=>json_encode([
                'bn'=>$request->bangladoctorname,
                'en'=>$request->englishdoctorname,
            ]),
            'degree'=>json_encode([
                'bn'=>$request->bangladegree,
                'en'=>$request->englishdegree,
            ]),
            'speciality'=>json_encode([
                'bn'=>$request->banglaspeciality,
                'en'=>$request->englishspeciality,
            ]),
            'workplace'=>json_encode([
                'bn'=>$request->banglaworkplace,
                'en'=>$request->englishworkplace,
            ]),
            'biography'=>json_encode([
                'bn'=>$request->banglabiography,
                'en'=>$request->englishbiography,
            ]),
            'experience'=>$request->experience,
            'rating'=>$request->rating,
            'appointmentNumber'=>$request->appointmentNumber,
            'location'=>json_encode([
                'bn'=>$request->banglalocation,
                'en'=>$request->englishlocation,
            ]),
        ]);
     
        return redirect()->route('doctors.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function destroy($doctor)
    {
        //
        Doctor::where('doctorId', $doctor)->firstOrFail()->delete();
        return redirect()->route('doctors.index');
    }
}