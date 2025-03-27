<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public $items = null;
    public $totalQty = 0;
    public $totalPrice = 0;

    public function __construct($oldCart = null)
    {
        if($oldCart) {
            $this->items = $oldCart->items;
            $this->totalQty = $oldCart->totalQty;
            $this->totalPrice = $oldCart->totalPrice;
        }
    }

    public function add($item, $id, $qty = 1)
    {
        if($item->promotion_price == 0) {
            $cart = [
                'qty' => 0,
                'price' => $item->unit_price,
                'item' => $item
            ];

            if($this->items) {
                if(array_key_exists($id, $this->items)) {
                    $cart = $this->items[$id];
                }
            }

            $cart['qty'] = $cart['qty'] + $qty;
            $cart['price'] = $item->unit_price * $cart['qty'];
            $this->items[$id] = $cart;
            $this->totalQty = $this->totalQty + $qty;
            $this->totalPrice += $item->unit_price * $cart['qty'];
        }else {
            $cart = ['qty' => 0, 'price' => $item->promotion_price, 'item' => $item];
            if($this->items) {
                if(array_key_exists($id, $this->items)) {
                    $cart = $this->items[$id];
                }
            }
            $cart['qty'] = $cart['qty'] + $qty;
            $cart['price'] = $item->unit_price * $cart['qty'];
            $this->items[$id] = $cart;
            $this->totalQty = $this->totalQty + $qty;
            $this->totalPrice += $item->unit_price * $cart['qty'];
        }
    }

    public function reduceByOne($id) {

    }

    public function removeItem($id) {

    }
}
