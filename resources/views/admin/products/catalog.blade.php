@extends('layouts.master')
@section('title','Product Catalog')
@section('content')
    <x-toolbar title="View Catalog"
               :breadcrumbs="[
    ['label'=>'Products']
]"
    />

    <div class="my-4">
        <div class="row">
            @forelse($products as $item)
                <div class="col-md-4 col-xl-3 col-sm-6 my-3">
                    <div class="card ">
                        {{--                        <img src="https://picsum.photos/seed/picsum/200/300" class="card-img-top tw-h-72" alt="...">--}}
                        <div id="carouselExampleAutoplaying{{$item->id}}" class="carousel slide"
                             data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @forelse($item->images as $image)
                                    <div class="carousel-item {{$loop->first?'active':''}}">
                                        <img src="{{ $image->url }}" class="card-img-top tw-h-72 d-block w-100 tw-object-contain"
                                             alt="...">
                                    </div>
                                @empty
                                    <img src="https://placehold.co/600x400?text={{$item->name}}" class="card-img-top tw-h-72" alt="...">
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
                            <p class="card-text">

                            </p>
                        </div>

                        <div class="card-body justify-content-between d-flex pt-0 align-items-center">
                            <div>
                                <strong>  {{ number_format($item->price) }} RWF</strong>
                            </div>
                            <a href="#" class="btn-sm btn btn-light-primary">
                                Details
                            </a>
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
@endsection
