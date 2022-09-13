<?php
/**
 * @package Nolan_Group;
 * Register Filters for Action Scheduler
 */
namespace Nolan_Group\Filters;

/**
 * Action Scheduler filters.
 */
class ActionScheduler
{
    /**
     * Register filters and actions
     */
    public function register() {
        add_filter( 'action_scheduler_queue_runner_time_limit', [$this, 'increase_time_limit'] );
    }
    
    
    public function increase_time_limit($time_limit) {
        return 120;
    }
}
