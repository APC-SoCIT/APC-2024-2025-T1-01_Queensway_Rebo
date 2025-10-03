<?php
// app/Http/Controllers/Website/WebsiteSearchController.php
namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use OpenAI\Laravel\Facades\OpenAI;

class WebsiteSearchController extends Controller
{
    /**
     * Show homepage with featured products (and search results if any).
     */
    public function index()
    {
        return view('welcome', [
            'latestProducts' => Product::latest()->take(6)->get(),
            'products' => null,   // no search yet
            'query' => null,
        ]);
    }

    /**
     * Search products by text query.
     */
    public function searchText(Request $request, $query = null)
    {
        $query = $query ?? $request->input('query');

        if (!$query) {
            return response()->json(['error' => 'Please enter a search query.'], 400);
        }

        // Generate embedding for query
        $response = OpenAI::embeddings()->create([
            'model' => 'text-embedding-3-small',
            'input' => $query,
        ]);
        $queryEmbedding = $response['data'][0]['embedding'];

        // Compare with stored product embeddings
        $products = Product::all()->map(function ($product) use ($queryEmbedding) {
            $product->similarity = $this->cosineSimilarity($product->embedding, $queryEmbedding);
            return $product;
        })->sortByDesc('similarity')->take(5);

        return view('website.product-matcher-results', compact('products', 'query'))->render();
    }



    /**
     * Search products by uploaded image.
     */
    public function searchImage(Request $request)
    {
        $file = $request->file('image');
        if (!$file) {
            return response()->json(['error' => 'Please upload an image.'], 400);
        }

        $path = $file->getRealPath();
        $base64 = base64_encode(file_get_contents($path));

        // Describe image
        $vision = OpenAI::chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'Describe the product in the image in a short sentence.'],
                [
                    'role' => 'user',
                    'content' => [
                        ['type' => 'text', 'text' => 'Describe this image:'],
                        ['type' => 'image_url', 'image_url' => ['url' => "data:image/jpeg;base64,$base64"]],
                    ]
                ],
            ],
        ]);

        $description = $vision['choices'][0]['message']['content'] ?? 'Unknown product';

        // Compare with stored product embeddings
        $response = OpenAI::embeddings()->create([
            'model' => 'text-embedding-3-small',
            'input' => $description,
        ]);
        $queryEmbedding = $response['data'][0]['embedding'];

        $products = Product::all()->map(function ($product) use ($queryEmbedding) {
            $product->similarity = $this->cosineSimilarity($product->embedding, $queryEmbedding);
            return $product;
        })->sortByDesc('similarity')->take(5);

        // Pass $query as the description of the image
        return view('website.product-matcher-results', [
            'products' => $products,
            'query' => $description
        ])->render();
    }




    /**
     * Cosine similarity helper.
     */
    private function cosineSimilarity($a, $b)
    {
        $dot = 0.0;
        $magA = 0.0;
        $magB = 0.0;
        $len = min(count($a), count($b)); // safety in case of mismatch

        for ($i = 0; $i < $len; $i++) {
            $dot += $a[$i] * $b[$i];
            $magA += $a[$i] ** 2;
            $magB += $b[$i] ** 2;
        }

        return $magA && $magB ? $dot / (sqrt($magA) * sqrt($magB)) : 0;
    }
}
