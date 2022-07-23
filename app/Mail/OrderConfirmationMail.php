<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private $active_token;
    private $shipping_info;
    private $order_products;

    public function __construct($active_token, $shipping_info, $order_products)
    {
        $this->active_token = $active_token;
        $this->shipping_info = $shipping_info;
        $this->order_products = $order_products;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $active_token = $this->active_token;
        $shipping_info = $this->shipping_info;
        $order_products = $this->order_products;
        return $this->view('mails.order-confirm',compact('active_token','shipping_info','order_products'))
        ->from('aguren24139@gmail.com','Hệ thống hỗ trợ Unimart') // Email và tên người gửi
        ->subject('Email xác thực đơn hàng Unimart') // Tiêu đề email
        ;
    }
}
