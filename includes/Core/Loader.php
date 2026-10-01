<?php

namespace AarambhaWooCommerceBirthdayDiscount\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WordPress actions and filters.
 */
class Loader {

    /**
     * Registered actions.
     *
     * @var array
     */
    private array $actions = array();

    /**
     * Registered filters.
     *
     * @var array
     */
    private array $filters = array();

    /**
     * Add an action.
     *
     * @param string $hook          Hook name.
     * @param object $component     Class object.
     * @param string $callback      Method name.
     * @param int    $priority      Hook priority.
     * @param int    $accepted_args Number of accepted arguments.
     *
     * @return void
     */
    public function add_action(
        string $hook,
        object $component,
        string $callback,
        int $priority = 10,
        int $accepted_args = 1
    ): void {
        $this->actions[] = array(
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args,
        );
    }

    /**
     * Add a filter.
     *
     * @param string $hook          Hook name.
     * @param object $component     Class object.
     * @param string $callback      Method name.
     * @param int    $priority      Hook priority.
     * @param int    $accepted_args Number of accepted arguments.
     *
     * @return void
     */
    public function add_filter(
        string $hook,
        object $component,
        string $callback,
        int $priority = 10,
        int $accepted_args = 1
    ): void {

        $this->filters[] = array(
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args,
        );
    }

    /**
     * Register all actions and filters with WordPress.
     *
     * @return void
     */
    public function run(): void {

        foreach ( $this->actions as $action ) {

            error_log(
                'AWBD: Registering action - ' . $action['hook']
            );

            add_action(
                $action['hook'],
                array(
                    $action['component'],
                    $action['callback'],
                ),
                $action['priority'],
                $action['accepted_args']
            );
        }

        foreach ( $this->filters as $filter ) {

            add_filter(
                $filter['hook'],
                array(
                    $filter['component'],
                    $filter['callback'],
                ),
                $filter['priority'],
                $filter['accepted_args']
            );
        }
    }
}