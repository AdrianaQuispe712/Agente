<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use App\Models\Category;
use Stringable;

class ListCategoryTool implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */

    
    public function description(): Stringable|string
    {
        return 'Tool to list all the categories available in the store. You can also filter the categories by providing a specific category name.';
        //Herramienta para listar todas las categorías disponibles en la tienda. También puedes filtrarlas indicando un nombre específico.
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $categories = Category::when(isset($request['category']), fn($query) => $query->where('name', $request['category']))->get();
        return $categories;
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'value' => $schema->string()->required(),
        ];
    }
}
