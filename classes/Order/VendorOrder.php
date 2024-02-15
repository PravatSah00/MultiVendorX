<?php

namespace MultiVendorX\Order;

defined('ABSPATH') || exit;

/**
 * @class 		MVX Vendor Order Class
 *
 * @version		3.4.0
 * @package		MultivendorX
 * @author 		MultiVendorX
 */
class VendorOrder {
    
    public $id = 0;
    public $vendor_id = 0;
    public $order = null;
    
    /**
     * Get the order if ID is passed, otherwise the order is new and empty.
     * @param  int | object Order to read.
     */
    public function __construct( $order = 0 ) {
        if ( $order instanceof \WC_Order || $order instanceof \WC_Order_Refund ) {
            $this->id = absint( $order->get_id() );
            $this->order = $order;
        } else {
            $this->id = absint( $order );
            $this->order = wc_get_order( $this->id );
        }

        $this->vendor_id = $this->order ? absint( $this->order->get_meta( '_vendor_id', true) ) : 0;
    }

    /**
     * Check the order is vendor order or not.
     * If the order is vendor order return true else false.
     * @param   bool $current_vendor
     * @return  bool
     */
    public function is_vendeor_order($current_vendor = false) {
        if(!$this->vendor_id) {
            return false;
        }
        if($current_vendor) {
            return $this->vendor_id === get_current_user_id();
        }
        return true;
    }
    
    /**
     * Get the props of vendor order.
     * Retrives data from vendoer meta.
     * @param  string $prop
     * @return mixed
     */
    public function get_prop( $prop ) {
        return  $this->order->get_meta( $prop, true);
    }

    /**
     * Get the vendeor id of vendor order.
     * @return int
     */
    public function get_vender_id() {
        return $this->vendor_id;
    }
    
    /**
     * Get vendor objet if the order is vendoer order.
     * Otherwise it return false.
     * @return object | null
     */
    public function get_vendor() {
        return get_mvx_vendor($this->vendor_id);
    }
    
    /**
     * Get vendor commission total.
     * @since 3.4.0
     */
    public function get_commission_total($context = 'view') {
        $commission_id = $this->get_prop('_commission_id');
        return \MVX_Commission::commission_totals($commission_id, $context);
    }
    
    /**
     * Get vendor commission amount.
     * @since 3.4.0
     */
    public function get_commission($context = 'view') {
        $commission_id = $this->get_prop('_commission_id');
        return \MVX_Commission::commission_amount_totals($commission_id, $context);
    }
    
    /**
     * Get formatted commission total.
     * @since 3.4.0
     */
    public function get_formatted_commission_total($context = 'view') {
        $commission_id = $this->get_prop('_commission_id');
        $commission_amount = get_post_meta( $commission_id, '_commission_amount', true );
        if($commission_amount != \MVX_Commission::commission_amount_totals($commission_id, 'edit')){
            return '<del>' . wc_price($commission_amount, array('currency' => $this->order->get_currency())) . '</del> <ins>' . \MVX_Commission::commission_amount_totals($commission_id, $context).'</ins>'; 
        }else{
            return \MVX_Commission::commission_amount_totals($commission_id, $context);
        }
    }
    
    /**
     * Get commission refunded amount.
     * @since 3.4.0
     */
    public function get_commission_refunded_amount($context = 'view') {
        $commission_id = $this->get_prop('_commission_id');
        return \MVX_Commission::commission_refunded_totals($commission_id, $context);
    }
    
    /**
     * Get items commission refunded amount.
     * @since 3.4.0
     */
    public function get_items_commission_refunded_amount($context = 'view') {
        $commission_id = $this->get_prop('_commission_id');
        return \MVX_Commission::commission_items_refunded_totals($commission_id, $context);
    }
    
    /**
     * Get total commission refunded amount.
     * @since 3.4.7
     */
    public function get_total_commission_refunded_amount($context = 'view') {
        $commission_id = $this->get_prop('_commission_id');
        return \MVX_Commission::commission_refunded_totals($commission_id, $context);
    }
    
    /**
     * Get vendor shipping amount.
     * @since 3.4.0
     */
    public function get_shipping($context = 'view') {
        $commission_id = $this->get_prop('_commission_id');
        return \MVX_Commission::commission_shipping_totals($commission_id, $context);
    }
    
    /**
     * Get vendor tax amount.
     * @since 3.4.0
     */
    public function get_tax($context = 'view') {
        $commission_id = $this->get_prop('_commission_id');
        return \MVX_Commission::commission_tax_totals($commission_id, $context);
    }
    
    /**
     * Get formatted order total earned.
     * @since 3.4.3
     */
    public function get_formatted_order_total_earned($context = 'view') {
        $commission_id = $this->get_prop('_commission_id');
        $commission_total = get_post_meta( $commission_id, '_commission_total', true );
        if($commission_total != \MVX_Commission::commission_totals($commission_id, 'edit')){
            return '<del>' . wc_price($commission_total, array('currency' => $this->order->get_currency())) . '</del> <ins>' . \MVX_Commission::commission_totals($commission_id, $context).'</ins>'; 
        }else{
            return \MVX_Commission::commission_totals($commission_id, $context);
        }
    }
}
