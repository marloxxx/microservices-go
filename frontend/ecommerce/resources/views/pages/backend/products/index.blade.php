@extends('layouts.backend.master')
@section('title', 'All Products')
@section('breadcrumb')
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold">
        <!--begin::Item-->
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a href="{{ route('backend.dashboard') }}" class="text-white">
                <i class="ki-outline ki-home text-white fs-3"></i>
            </a>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item">
            <i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a href="{{ route('backend.products.index') }}" class="text-white">Products</a>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item">
            <i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item text-white fw-bold lh-1">All Products</li>
        <!--end::Item-->
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('content')
    <!--begin::role-->
    <div class="card card-flush">
        <!--begin::Card header-->
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                    <span class="svg-icon svg-icon-1 position-absolute ms-4">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                            <path
                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                    <input type="text" data-filter="search" class="form-control form-control-solid w-250px ps-14"
                        placeholder="Search Product" />
                </div>
                <!--end::Search-->
            </div>
            <!--end::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                <!--begin::Add product-->
                <a href="{{ route('backend.products.create') }}" class="btn btn-primary" id="kt_toolbar_primary_button">
                    Add product
                </a>
                <!--end::Add product-->
                <button type="button" onclick="delete_all();" class="btn btn-danger me-3" id="kt_toolbar_delete_button">
                    Delete All
                </button>
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <div class="card-body pt-0">
            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
            @endif
        </div>
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <div class="table-responsive">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="datatables">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="check-all"
                                        onclick="check_all(this);">
                                    <label class="custom-control-label" for="check-all"></label>
                                </div>
                            </th>
                            <th class="min-w-250px">Name</th>
                            <th class="min-w-100px">Price</th>
                            <th class="min-w-100px">Quantity</th>
                            <th class="min-w-100px">Category</th>
                            <th class="min-w-70px">Actions</th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-semibold text-gray-600">
                        @foreach ($products as $product)
                            <!--begin::Table row-->
                            <tr>
                                <!--begin::Checkbox-->
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value={{ $product->ID }}
                                            data-product-filter="product_id" />
                                    </div>
                                </td>
                                <!--end::Checkbox-->
                                <!--begin::product=-->
                                <td>
                                    <div class="d-flex">
                                        <!--begin::Thumbnail-->
                                        <span class="symbol symbol-50px">
                                            <span class="symbol-label"
                                                style="background-image:url({{ asset($product->image) }})">
                                            </span>
                                        </span>
                                        <!--end::Thumbnail-->
                                        <div class="ms-5">
                                            <!--begin::Title-->
                                            <span class="text-gray-800 text-hover-primary fs-5 fw-bold mb-1"
                                                data-product-filter="product_name">{{ $product->name }}</a>
                                                <!--end::Title-->
                                        </div>
                                    </div>
                                </td>
                                <!--end::product=-->
                                <!--begin::Price=-->
                                <td>{{ $product->price }}</td>
                                <!--end::Price=-->
                                <!--begin::Quantity=-->
                                <td>{{ $product->quantity }}</td>
                                <!--end::Quantity=-->
                                <!--begin::Category=-->
                                <td>{{ $product->category_name }}</td>
                                <!--end::Category=-->
                                <!--begin::Action=-->
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <!--begin::Menu item-->
                                        <a href="{{ route('backend.products.edit', $product->ID) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit me-1"></i>
                                            Edit
                                        </a>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <a href="javascript:;" class="btn btn-sm btn-danger"
                                            onclick="handle_confirm('Apakah Anda Yakin?','Yakin','Tidak','DELETE','{{ route('backend.products.destroy', $product->ID) }}');">
                                            <i class="fas fa-trash me-1"></i>
                                            Delete
                                        </a>
                                        <!--end::Menu item-->
                                    </div>

                                </td>
                                <!--end::Action=-->
                            </tr>
                            <!--end::Table row-->
                        @endforeach
                    </tbody>
                    <!--end::Table body-->
                </table>
                <!--end::Table-->
            </div>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Menu-->
@endsection
@push('custom-scripts')
    <script>
        $(document).ready(function() {
            $('#kt_toolbar_delete_button').hide();
            $('#datatables').DataTable();

            $('[data-filter="search"]').on('keyup', function() {
                $('#datatables').DataTable().search(
                    $(this).val()
                ).draw();
            });
        });

        function check(el) {
            var is_checked = $(el).is(':checked');
            if (is_checked) {
                $('#kt_toolbar_delete_button').show();
                $('#kt_toolbar_primary_button').hide();
            } else {
                $('#kt_toolbar_delete_button').hide();
                $('#kt_toolbar_primary_button').show();
            }
        }

        function check_all(el) {
            var is_checked = $(el).is(':checked');
            if (is_checked) {
                $('#datatables').find('tbody input[type="checkbox"]').prop('checked', true);
                $('#kt_toolbar_delete_button').show();
                $('#kt_toolbar_primary_button').hide();
            } else {
                $('#datatables').find('tbody input[type="checkbox"]').prop('checked', false);
                $('#kt_toolbar_delete_button').hide();
                $('#kt_toolbar_primary_button').show();
            }

        }

        function delete_all() {
            var ids = [];
            $('#datatables').find('tbody input[type="checkbox"]:checked').each(function(i) {
                ids[i] = $(this).val();
            });
            if (ids.length > 0) {
                Swal.fire({
                    title: "Apakah anda yakin?",
                    text: "Data yang dipilih akan dihapus!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Hapus!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '',
                            type: 'POST',
                            data: {
                                ids: ids,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.alert) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: response.message,
                                        icon: 'success',
                                        confirmButtonText: 'Ok'
                                    }).then((result) => {
                                        $('#datatables').DataTable().ajax.reload();
                                        $('#check-all').prop('checked', false);
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: 'Terjadi kesalahan saat menghapus data',
                                        icon: 'error',
                                        confirmButtonText: 'Ok'
                                    });
                                }
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Batal!',
                            text: 'Konfirmasi dibatalkan',
                            icon: 'info',
                            confirmButtonText: 'Ok'
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: "Pilih data yang akan dihapus!",
                    text: "",
                    icon: "warning",
                    confirmButtonText: 'Ok'
                });
            }
        }
    </script>
@endpush
