<?php

namespace App\Livewire;

use App\Models\Product;
use Darryldecode\Cart\Cart;
use Darryldecode\Cart\Exceptions\InvalidItemException;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductCatalog extends Component
{
    use WithPagination;

    #[Url]
    public string $q = '';

    public $items = [];

    public function mount(): void
    {
        $this->refreshCart();
    }

    /**
     * @throws InvalidItemException
     * @throws \Exception
     */
    public function add($id): void
    {
        $product = Product::query()->find($id);
        // add the product to the cart
        $userID = auth()->id();
        \Cart::session($userID)->add(array(
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'attributes' => array(),
            'associatedModel' => $product
        ));

        $this->refreshCart();
    }

    /**
     * @throws \Exception
     */
    public function isInCart($id): bool
    {
        $product = Product::query()->find($id);
        $cartItem = \Cart::session(auth()->id())->get($product->id);
        return $cartItem ? true : false;
    }

    /**
     * @throws \Exception
     */
    public function remove($id): void
    {
        \Cart::session(auth()->id())->remove($id);
        $this->refreshCart();
    }

    public function refreshCart(): void
    {
    }


    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        $products = Product::query()->with(['images', 'category'])
            ->when($this->q, function ($query) {
                $query->where('name', 'like', '%' . $this->q . '%')
                    ->orWhere('description', 'like', '%' . $this->q . '%')
                    ->orWhereHas('category', function ($query) {
                        $query->where('name', 'like', '%' . $this->q . '%');
                    });
            })
            ->latest()
            ->paginate(12)->withQueryString();
        return view('livewire.product-catalog', compact('products'));
    }
}
