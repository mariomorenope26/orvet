<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\ContactMessage;
use App\Models\GalleryImage;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about', [
            'brands' => Brand::active()->get(),
            'gallery' => GalleryImage::active()->type('institutional')->get(),
            'team' => TeamMember::active()->get(),
        ]);
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function sendContact(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::create($data);

        return back()->with('status', '¡Gracias! Tu mensaje fue enviado. Te contactaremos pronto.');
    }

    public function privacy()
    {
        return view('pages.legal', [
            'title' => 'Política de privacidad',
            'content' => setting('privacy_policy'),
        ]);
    }

    public function terms()
    {
        return view('pages.legal', [
            'title' => 'Términos de servicio',
            'content' => setting('terms'),
        ]);
    }
}
