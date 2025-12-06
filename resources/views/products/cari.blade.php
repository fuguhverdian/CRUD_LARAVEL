@extends('layouts.master')

@section('content')
<section class="d-flex justify-content-center">
    <div class="p-4 ">

        <div class="p-2 pull-right">
            <a href="{{ route('product.add') }}" class="btn btn-lg btn-primary">
                <span>+ Tambah</span>
            </a>
        </div>

        <h2>Hasil Pencarian: "{{ $search }}"</h2>

        {{-- FORM SEARCH --}}
        <form action="{{ route('product.cari') }}" method="GET">
            <input type="text" name="search" placeholder="Cari lagi..." value="{{ $search }}">
            <button type="submit">Search</button>
        </form>

        {{-- ALERT ERROR --}}
        @if(isset($error))
            <div class="alert alert-danger my-3">
                {{ $error }}
            </div>
        @endif

        <table class="table table-responsive table-hover table-stripped mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Deskripsi Produk</th>
                    <th>Image</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

                @if($products->count() == 0)
                    <tr>
                        <td colspan="5">Tidak ada hasil ditemukan</td>
                    </tr>
                @endif

                @foreach ($products as $key => $product)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->description }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $product->image) }}" width="120">
                    </td>
                    <td>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('product.edit', $product->id) }}" class="btn btn-warning">Edit</a>

                            <form class="mx-2" action="{{ route('product.delete', $product->id) }}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>

        <a href="{{ route('home') }}" class="btn btn-secondary mt-3">Kembali ke halaman utama</a>

    </div>
</section>
@stop
