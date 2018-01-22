@extends('backend.layouts.master')
@section('title')
    {{ __('books.create_book') }}
@endsection
@section('content')
<script type="text/javascript">
  $role = {!! json_encode(trans('users')) !!};
</script>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      {{ __('books.create_book') }}
    </h1>
    <ol class="breadcrumb">
      <li>
        <a href="#">
          <i class="fa fa-dashboard"></i> {{ __('books.home') }}</a>
      </li>
      <li><a href="">{{ __('books.books') }}</a></li>
      <li class="active">{{ __('books.create_book') }}</li>
    </ol>
  </section>
  <section class="content">
      @include('flash::message')
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <form action="{{route('books.store')}}" role="form" method="POST" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="box-body">
                <div class="row">
                  <div class="col-xs-12">
                    <div class="form-group">
                      <div class="row">
                        <div class="col-xs-12">
                          <label for="InputTitle">{{ __('books.title') }}</label>
                          <input type="text" class="form-control" name="title" placeholder="{{ __('books.title') }}" value="{!! old('title') !!}">
                          @if($errors->first('title')) 
                            <span class="text-danger">{{ $errors->first('title') }}</span>
                          @endif
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-xs-3">
                          <label>{{ __('books.category') }}</label>
                          <select class="form-control" name="category_id" >
                            @foreach ($categories as $category)
                              <option value="{{ $category->id }}">{{ $category->title }}</option>
                            @endforeach
                          </select>
                          @if($errors->first('category'))
                            <span class="text-danger">{{ $errors->first('category') }}</span>
                          @endif
                        </div>
                        <div class="col-xs-3">
                          <label for="InputPrice">{{ __('books.price') }}</label>
                          <input type="text" class="form-control" name="price" placeholder="{{ __('books.price') }}" value="{!! old('price') !!}">
                           @if($errors->first('price'))
                            <span class="text-danger">{{ $errors->first('price') }}</span>
                          @endif
                        </div>
                         <div class="col-xs-3">
                          <label>{{ __('books.unit') }}</label>
                          <select class="form-control" name="unit" >
                              @foreach( __('books.listunit') as $key => $unit )
                              <option value="{{ $key }}">{{ $unit }}</option>
                              @endforeach
                          </select>
                          @if($errors->first('unit'))
                            <span class="text-danger">{{ $errors->first('unit') }}</span>
                          @endif
                        </div>
                        <div class="col-xs-3">
                          <label for="InputYear">{{ __('books.year') }}</label>
                          <input type="number" class="form-control" name="year" placeholder="{{ __('books.year') }}" value="{!! old('year') !!}">
                          @if($errors->first('year'))
                            <span class="text-danger">{{ $errors->first('year') }}</span>
                          @endif
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="InputDescription">{{ __('books.description') }}</label>
                      <textarea class="textarea form-control" placeholder="{{ __('books.place_some_text_here') }}" name="description">{!! old('description') !!}</textarea>
                      @if($errors->first('description')) 
                        <span class="text-danger">{{ $errors->first('description') }}</span>
                      @endif
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-xs-6">
                          <div class="col-xs-8 vertical-bottom">
                            <label for="InputFromPerson">{{ __('books.from_person') }}</label>
                            <input type="text" id="from-person-field" class="form-control" name="from_person" placeholder="{{ __('books.from_person') }}" value="{!! old('from_person') !!}">
                          </div>
                          <div class="vertical-bottom">
                            <button type="button" id="btn-verify-employee-code" class="btn btn-primary btn-flat">{{ __('users.verify') }}</button>
                          </div>
                          @if($errors->first('from_person'))
                          <div class="d-block">
                            <span class="text-danger">{{ $errors->first('from_person') }}</span>
                          </div>
                          @endif
                        </div>
                        <div class="col-xs-6">
                          <label for="InputAuthor">{{ __('books.author') }}</label>
                          <input type="text" class="form-control" name="author" placeholder="{{ __('books.author') }}" value="{!! old('author') !!}">
                          @if($errors->first('author'))
                            <span class="text-danger">{{ $errors->first('author') }}</span>
                          @endif
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="get-info-message col-xs-6">
                          <div class="get-info-success col-xs-12" hidden>
                            <div class="col-xs-6">
                              <label for="InputUsername">{{ __('users.name') }}</label>
                              <input type="text" class="form-control" name="username" value="" readonly>
                            </div>
                            <div class="col-xs-6">
                              <label for="InputEmail">{{ __('users.email') }}</label>
                              <input type="text" class="form-control" name="email" value="" readonly>
                            </div>
                          </div>
                          <div class="get-info-failure col-xs-12" hidden>
                            <span class="text-danger"></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="InputFile">{{ __('books.picture') }}</label>
                      <input type="file" name="picture" value="{!! old('picture') !!}">
                      @if($errors->first('picture'))
                        <span class="text-danger">{{ $errors->first('picture') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <button type="submit" id="btn-create-book" class="btn btn-primary btn-flat" disabled>{{ __('books.create') }}</button>&nbsp;&nbsp;
                  <button type="button" class="btn btn-flat " onclick="window.history.back();">{{ __('books.back') }}</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
</div>
@endsection
