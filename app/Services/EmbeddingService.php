<?php
// app/Services/EmbeddingService.php
namespace App\Services;

use App\Models\Product;
use OpenAI\Laravel\Facades\OpenAI;

class EmbeddingService
{
    /**
     * Get embedding for a query string
     */
    public function embedText(string $text): array
    {
        $response = OpenAI::embeddings()->create([
            'model' => 'text-embedding-3-small',
            'input' => $text,
        ]);

        return $response['data'][0]['embedding'];
    }

    /**
     * Compute cosine similarity
     */
    private function cosineSimilarity(array $a, array $b): float
    {
        $dot = 0.0;
        $aMag = 0.0;
        $bMag = 0.0;

        foreach ($a as $i => $val) {
            $dot += $val * $b[$i];
            $aMag += $val ** 2;
            $bMag += $b[$i] ** 2;
        }

        return $dot / (sqrt($aMag) * sqrt($bMag));
    }

    /**
     * Search products by text
     */
    public function searchProducts(string $query, int $limit = 5): array
    {
        $queryEmbedding = $this->embedText($query);

        $products = Product::whereNotNull('embedding')->get();

        $results = $products->map(function ($product) use ($queryEmbedding) {
            return [
                'product' => $product,
                'score'   => $this->cosineSimilarity($queryEmbedding, $product->embedding),
            ];
        });

        return $results
            ->sortByDesc('score')
            ->take($limit)
            ->values()
            ->toArray();
    }
}

?>
