<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Verification;

class VerificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $latestVerification = Verification::where('user_id', $user->id)
            ->latest()
            ->first();

        return view('verification', compact('latestVerification'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'front_image' => 'required|image|max:4096',
            'back_image' => 'required|image|max:4096',
            'selfie_image' => 'required|image|max:4096',
        ]);

        $user = auth()->user();

        $existingPending = Verification::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existingPending) {
            return redirect('/verification')->with('error', 'You already have a verification request pending review.');
        }

        $front = $request->file('front_image')->store('verifications', 'public');
        $back = $request->file('back_image')->store('verifications', 'public');
        $selfie = $request->file('selfie_image')->store('verifications', 'public');

        Verification::create([
            'user_id' => $user->id,
            'front_image' => $front,
            'back_image' => $back,
            'selfie_image' => $selfie,
            'status' => 'pending',
            'admin_note' => null,
        ]);

        $user->verification_status = 'pending';
        $user->save();

        return redirect('/profile')->with('success', 'Verification submitted successfully.');
    }
}