@extends('layouts.master')
@section('title','Deliveries')
@section('content')
    <x-toolbar title="Order Deliveries"
               :breadcrumbs="[
    ['label'=>'Deliveries']
]"
    />
    <x-delivery-content/>
@endsection
@push('scripts')
    <x-delivery-scripts/>
@endpush
