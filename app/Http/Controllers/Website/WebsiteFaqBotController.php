<?php
namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Faq;

class WebsiteFaqBotController extends Controller
{
    public function handle(Request $request)
    {
        $question = strtolower($request->input('question'));
        $faqs = Faq::all();
    
        foreach ($faqs as $faq) {
            foreach (explode(',', strtolower($faq->keywords)) as $keyword) {
                if (str_contains($question, trim($keyword))) {
                    return response()->json(['answer' => $faq->answer]);
                }
            }
        }
    
        return response()->json(['answer' => 'Sorry, I didn’t understand that']);
    }
    
    
}
