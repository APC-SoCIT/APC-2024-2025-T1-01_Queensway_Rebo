<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\Product;
use OpenAI\Laravel\Facades\OpenAI;
use Throwable;

class GenerateProductEmbeddings extends Command
{
    protected $signature = 'products:embed 
                            {--force : Re-generate even if embedding already exists}
                            {--chunk=100 : How many products per chunk}';

    protected $description = 'Generate OpenAI embeddings for products (name/description) and store them in DB';

    public function handle(): int
    {
        $chunkSize = (int) $this->option('chunk');
        $force = (bool) $this->option('force');

        $query = Product::query();
        if (!$force) {
            $query->whereNull('embedding')->orWhere('embedding', '');
        }

        $total = (clone $query)->count();
        if ($total === 0) {
            $this->info('Nothing to embed. All products already have embeddings.');
            return self::SUCCESS;
        }

        $this->info("Generating embeddings for {$total} product(s)...");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $query->orderBy('id')->chunkById($chunkSize, function ($products) use ($bar) {
            foreach ($products as $product) {
                try {
                    $text = $this->buildEmbeddingText($product);

                    // Truncate to safe length (~8000 chars)
                    $text = Str::limit($text, 8000, '');

                    $response = OpenAI::embeddings()->create([
                        'model' => 'text-embedding-3-small',
                        'input' => $text,
                    ]);

                    

                    $embedding = $response->embeddings[0]->embedding; // array of floats

                    $product->embedding = json_encode($embedding);
                    $product->save();

                    // Tiny delay to reduce API rate-limit risk
                    usleep(80_000);
                } catch (Throwable $e) {
                    \Log::warning('Embedding failed', [
                        'product_id' => $product->id,
                        'message' => $e->getMessage(),
                    ]);
                } finally {
                    $bar->advance();
                }
            }
        });

        $bar->finish();
        $this->newLine(2);
        $this->info('Done generating embeddings.');

        return self::SUCCESS;
    }

    /**
     * Build a concise text representation for embeddings
     */
    private function buildEmbeddingText($product): string
    {
        $parts = [
            'Name: ' . ($product->name ?? ''),
            'SKU: ' . ($product->sku ?? ''),
            'Description: ' . ($product->description ?? ''),
            'Category: ' . ($product->category ?? ''),
            'Price: ' . ($product->price ?? ''),
            'Quantity: ' . ($product->quantity ?? ''),
        ];

        return collect($parts)
            ->filter(fn($v) => !empty(trim((string) $v)))
            ->implode("\n");
    }
}
