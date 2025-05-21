<?php
namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WebsiteFaqBotController extends Controller
{
    public function handle(Request $request)
    {
        $question = strtolower($request->input('question'));
    
        $faqMap = [
            'store_hours' => [
                'keywords' => ['store hours', 'opening hours', 'what time do you open', 'hours of operation'],
                'response' => 'Our store is open from 9 AM to 6 PM, Monday to Saturday.',
            ],
            'shipping' => [
                'keywords' => ['shipping', 'delivery', 'do you ship', 'courier'],
                'response' => 'We offer nationwide shipping via J&T and LBC.',
            ],
            'returns' => [
                'keywords' => ['return', 'refund', 'exchange'],
                'response' => 'Returns are accepted within 7 days of purchase with receipt.',
            ],
            'payment' => [
                'keywords' => ['payment', 'pay', 'gcash', 'paypal'],
                'response' => 'We accept PayPal, GCash, and bank transfers.',
            ],
        ];
    
        foreach ($faqMap as $topic) {
            foreach ($topic['keywords'] as $keyword) {
                if (str_contains($question, $keyword)) {
                    return response()->json(['answer' => $topic['response']]);
                }
            }
        }
    
        return response()->json(['answer' => 'Sorry, I didn’t understand that. Try asking about shipping, returns, or store hours.']);
    }
    
    
}
