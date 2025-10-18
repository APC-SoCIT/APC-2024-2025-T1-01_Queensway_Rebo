<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class WebsiteReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'feedback' => 'required|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $userId = Auth::id();

        // Prevent duplicate review per order
        $existing = Review::where('order_id', $request->order_id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already submitted feedback for this order.');
        }

        Review::create([
            'order_id' => $request->order_id,
            'user_id' => $userId,
            'feedback' => $request->feedback,
            'rating' => $request->rating,
        ]);

        return back()->with('success', 'Thank you for your feedback!');
    }
}
