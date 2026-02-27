<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use App\Models\Order;
use App\Models\User;
use Stringable;

class ListOrderToolForUser implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */


    public function __construct(private User $user) {
        
    }
    public function description(): Stringable|string
    {
        return 'List order for the user. You need to provide user Id.';
        //Orden de lista para el usuario. Debe proporcionar su ID de usuario.
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $orders = Order::where('user_id', $this->user->id)->get();
        return $orders;
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            // 'value' => $schema->string()->required(),
        ];
    }
}
