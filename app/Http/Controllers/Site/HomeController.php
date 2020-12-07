<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;

class HomeController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    
    public function feedbackEmail(Request $request){
    
        $this->validate($request, [
            'email'      => 'required|email',
            'message'    => 'required'
        ]);
        
        $email   = $request->email;
        $message = $request->message;
        
        $data= ['email' => $email , 'email_message'=> $message ];
        
        Mail::send('emails.feedback', $data , function ($message) use ($email ){
            $message->from( $email , config('app.name').' Feedback' );
            $message->subject('Library Feedback');
            $message->to('raja.ram@abtach.org');
        });
        
        Mail::send('emails.feedback', $data , function ($message) use ($email){
            $message->from($email , config('app.name').' Feedback' );
            $message->subject('Library Feedback');
            $message->to( 'eworldtradeweb@gmail.com' );
        });
        
        return response()->json(['success' => true , 'message'=> 'Your message sent successfully.']);
    }
    
}
