<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;
use function PHPUnit\Framework\callback;

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
        $category = $request['category']??null;

        $products = Product::when(value: $category, callback: function(Builder $query) use($category){
            $query->whereHas(relation: 'categories', callback: function(Builder<TRelatedModel> $query) use($category): void{
                $query->where(column: 'title', operator: $category);
            });
        })->get();

        return $products;
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
