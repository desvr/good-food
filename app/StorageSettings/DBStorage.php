<?php

namespace App\StorageSettings;

use App\Models\DatabaseStorageModel;
use Darryldecode\Cart\CartCollection;
use Illuminate\Support\Facades\Cache;

class DBStorage
{
    public function has($key) {
        $cart_storage_model = Cache::remember('cart_storage_model:key:' . $key, 60 * 15, function() use ($key) {
            return DatabaseStorageModel::find($key);
        });

        if (empty($cart_storage_model)) {
            Cache::forget('cart_storage_model:key:' . $key);
        }

        return $cart_storage_model;
    }

    public function get($key) {
        if ($cart_storage_model = $this->has($key)) {
            return new CartCollection($cart_storage_model->cart_data);
        } else {
            return [];
        }
    }

    public function put($key, $value) {
        $cart_storage_model = Cache::pull('cart_storage_model:key:' . $key);
        if (empty($cart_storage_model)) {
            $cart_storage_model = DatabaseStorageModel::find($key);
        }

        if ($cart_storage_model) {
            $cart_storage_model->cart_data = $value;
            $cart_storage_model->save();

            Cache::remember('cart_storage_model:key:' . $key, 60 * 15, function() use ($cart_storage_model) {
                return $cart_storage_model;
            });
        } else {
            DatabaseStorageModel::create([
                'id' => $key,
                'cart_data' => $value
            ]);
        }
    }
}
