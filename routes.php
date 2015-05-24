<?php
use Firestarter\Shop\Models\Product as Product;
use Firestarter\Shop\Models\Coupon as Coupon;
use Firestarter\Shop\Models\Order as Order;
use Firestarter\Shop\Models\Currency as Currency;
use Firestarter\Shop\Models\Settings as Settings;

Route::group(['prefix' => 'license'], function()
{
    Route::get('order/webmoney/wmz', function()
    {
          /**
            Нужны все валюты
          */
         $currencies = Currency::get()->keyBy('id')->toArray();
         /**
            Это предварительный запрос?
         */
         if(post('LMI_PREREQUEST')==1) 
         {
            /**
                Есть ли такой товар в базе?
            */
            if(!$product = Product::find(post('SHOP_USER_PRODUCT_ID'))->toArray());
            {
                die("ERROR: НЕТ ТАКОГО ТОВАРА");
            };
            /**
                Строка с пользователем?
            */
            if(!post('SHOP_USER_NAME') OR post('SHOP_USER_NAME')=='')
            {
                die("ERROR: НЕ УКАЗАН ПОЛЬЗОВАТЕЛЬ");
            };
            /**
                Строка с почтой?
            */
            if(!post('SHOP_USER_EMAIL') OR post('SHOP_USER_EMAIL')=='')
            {
                die("ERROR: НЕ УКАЗАН EMAIL");
            };
            /**
                Кошелек верный?
            */
            if(post('LMI_PAYEE_PURSE')=='' OR post('LMI_PAYEE_PURSE')!==Settings::get('wmz_purse'))
            {
                die("ERROR: НЕВЕРНЫЙ КОШЕЛЕК ПОЛУЧАТЕЛЯ ".post('LMI_PAYEE_PURSE'));
            };
            /**
                Проверка наличия купона в форме и расчет цены:
            */
            if(post('SHOP_USER_COUPON'))
            {
                if(!$coupon  = Coupon::where('product_id', '=', post('SHOP_USER_PRODUCT_ID'))->where('value', '=', post('SHOP_USER_COUPON'))->first()->toArray())
                {
                    die("ERROR: У ДАНОГО ТОВАРА НЕТ ТАКОГО КУПОНА");
                }
                /**
                    {{(product.price - product.price*coupon.discount/100)*currencies[settings.wmz_carrency].value}}
                */
                $price = ($product['price'] - $product['price']*$coupon['discount']/100)*$currencies[Settings::get('wmz_carrency')]['value'];                
            }
            else
            {
                /**
                    {{product.price*currencies[settings.wmz_carrency].value}}
                */
                $price = $product['price']*$currencies[Settings::get('wmz_carrency')]['value'];
            };
            /**
                Проверка цены:
            */
             if(post('LMI_PAYMENT_AMOUNT')=='' OR post('LMI_PAYMENT_AMOUNT')!==$price)
            {
                die("ERROR: НЕВЕРНАЯ СУММА");
            };            
            /**
                Все нормально - выводим YES
            */
            die("YES");            
         }
         /**
            ЕСЛИ НЕТ LMI_PREREQUEST, СЛЕДОВАТЕЛЬНО ЭТО ФОРМА ОПОВЕЩЕНИЯ О ПЛАТЕЖЕ
         */         
         else
         {
              /**
                Ключ
              */  
              $secret_key=Settings::get('wmz_secret_key');
              /**
                Клеим строку
              */

              $common_string = 
                post('LMI_PAYEE_PURSE').
                post('LMI_PAYMENT_AMOUNT').
                post('LMI_PAYMENT_NO').
                post('LMI_MODE').
                post('LMI_SYS_INVS_NO').
                post('LMI_SYS_TRANS_NO').
                post('LMI_SYS_TRANS_DATE').
                $secret_key.
                post('LMI_PAYER_PURSE').
                post('LMI_PAYER_WM');
                 
              /** 
                    Шифруем полученную строку в SHA256 и переводим ее в верхний регистр
              */
              $hash = strtoupper(hash("sha256",$common_string));
              /**
                    Прерываем работу скрипта, если контрольные суммы не совпадают
              */
              if($hash!==post('LMI_HASH'))
              {
                exit;
              }
              /**
                Создаем заказ
              */
              if(post('SHOP_USER_COUPON'))
              {
                $coupon  = Coupon::where('product_id', '=', post('SHOP_USER_PRODUCT_ID'))->where('value', '=', post('SHOP_USER_COUPON'))->first()->toArray();
                $coupon_id = $coupon['id'];
              }
                else
              {
                $coupon_id = 0;
              }
              /**
              */
              $post = new Order;
              $post->product_id = post('SHOP_USER_PRODUCT_ID');
              $post->coupon_id =  $coupon_id;
              $post->user_name = post('SHOP_USER_NAME');
              $post->email = post('SHOP_USER_EMAIL');
              $post->save();
              /**
                Добавляю инфу по пользователю в массив товара
              */              
              $product['user_name'] = post('SHOP_USER_NAME');
              $product['user_email'] = post('SHOP_USER_EMAIL');
              /**
                Отправляем софт клиенту
              */
              Mail::sendTo(post('SHOP_USER_EMAIL'), 'firestarter.shop::mail.license', $product);
              
              return Redirect::to('/');
              
         }
         
    });
});