<?php

class BasketController extends Controller{

    public function __construct(){
        parent::__construct();
        $this->title = 'Корзина покупок';
        $this->h1 = 'Ваши покупки';
        $this->tpl = 'Basket';
    }

    public function index(){
        $a = new Basket;
        return $a->index();
    }

    public function AddBasket(){
        $this->tpl = '';
        $a = new Basket;
        return json_encode($a->AddBasket());

    }

    public function RemoveBasket(){
        $this->tpl = '';
        $a = new Basket;
        return json_encode($a->RemoveBasket());
       
    }
    
    public function MathematicBasket(){
        $this->tpl = '';
        $a = new Basket;
        return json_encode($a->Mathematic());
        }

    public function OrderBasket(){
        $this->tpl = '';
        $a = new Basket;
        $b = $a->Order(); 
        //Если заказ не оформлен. и выход со статусом fatal === true. то очищает корзину юзера.
        if($b['fatal'] === true){
            setcookie('basket', '', time()-2592000, '/');
        }
        return json_encode($b);
        }

}