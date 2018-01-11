@extends('backend.layouts.master') 
@section('title') 
  {{ __('books.list_books') }}
@endsection 
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {{ __('books.list_books') }}
    </h1>
    <ol class="breadcrumb">
      <li>
        <a href="#">
          <i class="fa fa-dashboard"></i> {{ __('books.home') }}</a>
      </li>
      <li>
        <a href="#">{{ __('books.books') }}</a>
      </li>
      <li class="active">{{ __('books.list') }}</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <div class="pull-left">
              <a href="{{ route('books.create') }}"><button type="button" name="btn-add" id="btn-add" class="btn btn-success btn-flat">{{ __('books.add_book') }}</button></a>
            </div>
            <div class="pull-right col-xs-6">
              <form action="{{ route('books.index') }}" method="GET">
                  <div class="col-xs-6">
                    <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="{{ __('books.search') }}">
                  </div>
                  <div class="col-xs-4">
                    <select name="filter" id="filter" class="form-control">
                      @foreach( __('books.list_search') as $key => $search )
                        <option value="{{ $key }}" {{$key == app('request')->input('filter') ? 'selected' : '' }}>{{ $search }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-xs-2">
                    <button type="submit" id="btn-search" class="btn btn-primary btn-flat">{{ __('books.search') }}</button>
                  </div>
              </form>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            @include('backend.layouts.partials.modal')
            <table id="list-books" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th class="text-center" width="5%">
                    {{ __('books.no') }}
                  </th>
                  <th>
                    {{ __('books.title') }}
                    <a href="" name="title" class="pull-right sort-element">
                      <i class="fa fa-sort-amount-asc text-muted" aria-hidden="true"></i>
                    </a>
                  </th>
                  <th>
                   {{ __('books.author') }}
                    <a href="" name="author" class="pull-right sort-element">
                      <i class="fa fa-sort-amount-asc text-muted" aria-hidden="true"></i>
                    </a>
                  </th>
                  <th class="text-center">
                    {{ __('books.rating') }}
                    <a href="" name="rating" class="pull-right sort-element">
                      <i class="fa fa-sort-amount-asc text-muted" aria-hidden="true"></i>
                    </a>
                  </th>
                  <th class="text-center" width="12%">
                    {{ __('books.total_borrowed') }}
                    <a href="" name="total_borrowed" class="pull-right sort-element">
                      <i class="fa fa-sort-amount-asc text-muted" aria-hidden="true"></i>
                    </a>
                  </th>
                  <th class="text-center" width="15%">
                    {{ __('books.options') }}
                  </th>
                </tr>
              </thead>
              <tbody>
                @foreach ($books as $index => $book)  
                  <tr>
                    <td class="text-center">{{ $index + $books->firstItem() }}</td>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td class="text-center">{{ $book->rating }}</td>
                    <td class="text-center">{{ $book->total_borrowed }}</td>
                    <td class="text-center">
                      <div class="btn-option text-center">
                        <a href="{{ route('books.edit', $book->id) }}" class="btn btn-primary btn-flat fa fa-pencil"></a>&nbsp;&nbsp;
                        <form method="POST" action="{{ route('books.destroy', $book->id) }}" class="inline">
                          <button type="submit" class="btn btn-danger btn-flat fa fa-trash-o btn-delete-item"
                            data-title="{{ __('Confirm deletion!') }}"
                            data-confirm="{{ __('Are you sure you want to delete?') }}"
                          ></button>
                        </form> 
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            <div class="text-right">
              {{ $books->appends(['search' => Request::get('search'), 'filter' => Request::get('filter')])->links() }}
            </div>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
