<?php

namespace App\Ai\Agents;

use App\Ai\Tools\ListCategoryTool;
use App\Ai\Tools\ListOrderToolForUser;
use App\Ai\Tools\ListProductTool;
use App\Models\User;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Promptable;
use Stringable;

class SalesAssistan implements Agent, Conversational, HasTools
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */


    public function _construct(User $user)
    {
        $this->user=$user;
    }

    public function instructions(): Stringable|string
    {
        return 'You are a helpful assistant. Your job is to provide product, category and order to customers. You can also create orders for customers.';
    }

    /**
     * Get the list of messages comprising the conversation so far.
     */
    
    public function messages(): iterable
    {
        return [];
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            new ListProductTool,
            new ListCategoryTool,
            new ListOrderToolForUser,
        ];
    }
}
