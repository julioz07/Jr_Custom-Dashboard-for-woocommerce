<?php
if (!defined('ABSPATH')) {
    exit;
}

class WCD_Dashboard {
    public function __construct() {
        // Remove default dashboard content - priority 5 to remove before adding our content
        add_action('init', array($this, 'remove_default_dashboard'));
        
        // Add our custom content - priority 10 (default)
        add_action('woocommerce_account_dashboard', array($this, 'render_dashboard_cards'));
        
        // Load styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
    }

    public function remove_default_dashboard() {
        remove_action('woocommerce_account_dashboard', 'woocommerce_account_dashboard');
    }

    public function enqueue_styles() {
        if (is_account_page()) {
            wp_enqueue_style('dashicons');
            wp_enqueue_style(
                'wcd-dashboard-style',
                WCD_PLUGIN_URL . 'assets/css/dashboard.css',
                array('dashicons'),
                WCD_VERSION
            );
        }
    }

    public function render_dashboard_cards() {
        echo '<div class="wcd-dashboard">';
        echo '<div class="wcd-dashboard-grid">';
        
        // Card 1: Recent Orders
        $this->render_recent_orders_card();
        
        // Card 2: Quick Stats
        $this->render_quick_stats_card();
        
        // Card 3: Account Actions
        $this->render_account_actions_card();
        
        echo '</div>';
        echo '</div>';
    }

    private function render_recent_orders_card() {
        $orders = wc_get_orders(array(
            'customer' => get_current_user_id(),
            'limit' => 3,
            'orderby' => 'date',
            'order' => 'DESC',
        ));

        echo '<div class="wcd-card wcd-recent-orders">';
        echo '<h3>' . esc_html__('Recent Orders', 'woo-custom-dashboard') . '</h3>';
        
        if ($orders) {
            echo '<ul class="wcd-order-list">';
            foreach ($orders as $order) {
                echo '<li class="wcd-order-item">';
                echo '<span class="wcd-order-number">#' . esc_html($order->get_order_number()) . '</span>';
                echo '<span class="wcd-order-status ' . esc_attr('status-' . $order->get_status()) . '">' . 
                     esc_html(wc_get_order_status_name($order->get_status())) . '</span>';
                echo '<span class="wcd-order-total">' . wp_kses_data($order->get_formatted_order_total()) . '</span>';
                echo '<a href="' . esc_url($order->get_view_order_url()) . '" class="wcd-view-order">' . 
                     esc_html__('View', 'woo-custom-dashboard') . '</a>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p class="wcd-no-orders">' . esc_html__('No orders yet.', 'woo-custom-dashboard') . '</p>';
        }
        
        echo '</div>';
    }

    private function render_quick_stats_card() {
        $customer = wp_get_current_user();
        $order_count = wc_get_customer_order_count($customer->ID);
        $total_spent = wc_price(wc_get_customer_total_spent($customer->ID));

        echo '<div class="wcd-card wcd-quick-stats">';
        echo '<h3>' . esc_html__('Your Stats', 'woo-custom-dashboard') . '</h3>';
        
        echo '<div class="wcd-stats-grid">';
        
        // Orders count
        echo '<div class="wcd-stat-item">';
        echo '<span class="wcd-stat-value">' . esc_html($order_count) . '</span>';
        echo '<span class="wcd-stat-label">' . esc_html__('Orders Placed', 'woo-custom-dashboard') . '</span>';
        echo '</div>';
        
        // Total spent
        echo '<div class="wcd-stat-item">';
        echo '<span class="wcd-stat-value">' . wp_kses_post($total_spent) . '</span>';
        echo '<span class="wcd-stat-label">' . esc_html__('Total Spent', 'woo-custom-dashboard') . '</span>';
        echo '</div>';
        
        echo '</div>';
        echo '</div>';
    }

    private function render_account_actions_card() {
        echo '<div class="wcd-card wcd-account-actions">';
        echo '<h3>' . esc_html__('Quick Actions', 'woo-custom-dashboard') . '</h3>';
        
        echo '<div class="wcd-actions-grid">';
        
        // Download action
        echo '<a href="' . esc_url(wc_get_account_endpoint_url('downloads')) . '" class="wcd-action-link">';
        echo '<span class="dashicons dashicons-download"></span>';
        echo '<span class="wcd-action-label">' . esc_html__('Downloads', 'woo-custom-dashboard') . '</span>';
        echo '</a>';
        
        // Orders action
        echo '<a href="' . esc_url(wc_get_account_endpoint_url('orders')) . '" class="wcd-action-link">';
        echo '<span class="dashicons dashicons-archive"></span>';
        echo '<span class="wcd-action-label">' . esc_html__('Orders', 'woo-custom-dashboard') . '</span>';
        echo '</a>';
        
        // Edit account action
        echo '<a href="' . esc_url(wc_get_account_endpoint_url('edit-account')) . '" class="wcd-action-link">';
        echo '<span class="dashicons dashicons-admin-users"></span>';
        echo '<span class="wcd-action-label">' . esc_html__('Account Details', 'woo-custom-dashboard') . '</span>';
        echo '</a>';
        
        // Edit address action
        echo '<a href="' . esc_url(wc_get_account_endpoint_url('edit-address')) . '" class="wcd-action-link">';
        echo '<span class="dashicons dashicons-location"></span>';
        echo '<span class="wcd-action-label">' . esc_html__('Addresses', 'woo-custom-dashboard') . '</span>';
        echo '</a>';
        
        echo '</div>';
        echo '</div>';
    }
}
