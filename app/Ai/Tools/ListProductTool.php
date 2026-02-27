<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;
use App\Models\Product;
// use function PHPUnit\Framework\callback;
use Illuminate\Database\Query\Builder;

class ListProductTool implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'List all the products available in the store or filter them based on category name.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $category = $request['category'] ?? null;

        $products = Product::when($category, function (Builder $query) use ($category) {
            $query->whereHas('categories', function (Builder $query) use ($category) {
                $query->where('title', $category);
            });
        })->get();

        return $products->toJson();
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'category' => $schema->string()->nullable(),
        ];
    }
}
