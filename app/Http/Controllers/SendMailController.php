<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendMailController extends Controller
{
    //
    public function sendMailWithAttachment(Request $request)
    {
        // Laravel 8
        // $data["email"] = "test@gmail.com";
        // $data["title"] = "Techsolutionstuff";
        // $data["body"] = "This is test mail with attachment";

        // $files = [
        //     public_path('attachments/test_image.jpeg'),
        //     public_path('attachments/test_pdf.pdf'),
        // ];

        // Mail::send('mail.test_mail', $data, function($message)use($data, $files) {
        //     $message->to($data["email"])
        //             ->subject($data["title"]);

        //     foreach ($files as $file){
        //         $message->attach($file);
        //     }
        // });
        $mailData = [
            'title' => 'This is Test Mail',
            // 'files' => [
            //     public_path('attachments/test_image.jpeg'),
            //     public_path('attachments/test_pdf.pdf'),
            // ]
        ];

        Mail::to('software@nutriton.com.mx')->send(new TestMail($mailData));

        echo "Mail send successfully !!";
    }
}
