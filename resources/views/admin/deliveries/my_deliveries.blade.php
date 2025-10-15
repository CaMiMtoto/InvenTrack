@extends('layouts.master')
@section('title',"My Deliveries")
@section('content')
    <x-toolbar title="Assigned To Me"
               :breadcrumbs="[
    ['label'=>'Deliveries']
]"
    />

    <x-delivery-content/>

@endsection

@push('scripts')
    <x-delivery-scripts/>
@endpush
