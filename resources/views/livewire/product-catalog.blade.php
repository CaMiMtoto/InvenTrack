<div>
    <div class="mb-5">
        <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
            <div class="page-title d-flex flex-column gap-1 me-3 mb-2">
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold mb-6">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-700 fw-bold">
                        <a href="{{ route('admin.dashboard') }}"
                           class="text-gray-500 d-flex align-items-center justify-content-center">
                            <x-lucide-house class="tw-h-5 tw-w-5 text-gray-400"/>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <x-lucide-chevron-right class="tw-h-5 tw-w-5 text-gray-700 mx-n1"/>
                    </li>
                    <!--end::Item-->
                    <li class="breadcrumb-item">
                        Products Catalog
                    </li>
                </ul>
                <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">
                    View Catalog
                </h1>
            </div>

            <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center ms-1 ms-lg-3">
                    <!--begin::Menu wrapper-->
                    <div
                        class="btn btn-icon btn-danger position-relative btn-sm rounded-pill">
                        <!--begin::Svg Icon | path: icons/duotone/Communication/Group-chat.svg-->
                        <span class="svg-icon svg-icon-1">
                            <x-lucide-shopping-bag class="tw-h-10 tw-w-10"/>
                        </span>
                        <!--end::Svg Icon-->
                        <a href="{{ route('admin.orders.create') }}"
                            class="fw-bolder position-absolute translate-middle top-0 start-75 tw-h-5 tw-w-5 bg-danger-subtle d-flex align-items-center justify-content-center rounded-pill text-danger p-4">
                            {{ \Cart::session(auth()->id())->getContent()->count() }}
                        </a>
                    </div>
                    <!--end::Menu wrapper-->
                </div>
                <div>
                    <input type="text" class="form-control form-control-sm w-lg-250px" placeholder="Search .."
                           wire:model.live.debounce="q"/>
                </div>
            </div>
        </div>
    </div>

    <div class="row  my-4">
        @forelse($products as $item)
            <div class="col-md-4 col-xl-3 col-sm-6 my-3">
                <div class="card ">
                    {{--                        <img src="https://picsum.photos/seed/picsum/200/300" class="card-img-top tw-h-72" alt="...">--}}
                    <div id="carouselExampleAutoplaying{{$item->id}}" class="carousel slide"
                         data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @forelse($item->images as $image)
                                <div class="carousel-item {{$loop->first?'active':''}}">
                                    <img src="{{ $image->url }}"
                                         class="card-img-top tw-h-72 d-block w-100 tw-object-contain"
                                         alt="...">
                                </div>
                            @empty
                                <img src="https://placehold.co/600x400?text={{$item->name}}"
                                     class="card-img-top tw-h-72" alt="...">
                            @endforelse

                        </div>
                        <button class="carousel-control-prev" type="button"
                                data-bs-target="#carouselExampleAutoplaying{{$item->id}}" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button"
                                data-bs-target="#carouselExampleAutoplaying{{$item->id}}" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">
                            {{ $item->name }}
                        </h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>  {{ number_format($item->price) }} RWF</strong>
                            @if ($this->isInCart($item->id))
                                <button wire:click="remove({{ $item->id }})" type="button"
                                        class="btn btn-light-danger btn-sm rounded-pill btn-icon">
                                    <x-lucide-trash class="tw-h-5 tw-w-5"/>
                                </button>
                            @else
                                <button class="btn btn-light-primary btn-sm rounded-pill btn-icon" type="button"
                                        wire:click="add({{$item->id}})">
                                    <x-lucide-plus class="tw-h-5 tw-w-5"/>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card-body py-15 py-lg-20 d-flex justify-content-center align-items-center flex-column">

                    <!--begin::Title-->
                    <h1 class="fw-bolder fs-2hx text-gray-900 mb-4">
                        Oops!
                    </h1>
                    <!--end::Title-->

                    <!--begin::Text-->
                    <div class="fw-semibold fs-6 text-gray-500 mb-7">
                        No products found.
                    </div>
                    <!--end::Text-->

                    <!--begin::Illustration-->
                    <div class="mb-3">
                        <x-lucide-shopping-basket class="tw-h-20"/>
                    </div>
                    <!--end::Illustration-->

                    <!--begin::Link-->

                    <!--end::Link-->

                </div>
            </div>
        @endforelse
    </div>
    {{ $products->links() }}
</div>
