@extends('layouts.app')

@section('content')

<h1 class="text-center">Odabir jela</h1>
<div class="ml-5">
    <form method="GET" action="{{ route('dish.show') }}">

                <div class="form-group">
                    <label for="basic"><h3>Odaberite jezik na kojem želite izlistati jela:</h3></label>
                        <select class="form-control" id="basic" name="lang">
                            <option selected disabled>Odaberite jezik</option>
                            @foreach($locales as $locale)
                                
                            <option value="{{ $locale }}">{{ $locale }}</option>
                                    
                            @endforeach
                        </select>
                </div>

                <div class="form-group">
                    <label for="basic"><h3>Odaberite kategoriju jela:</h3></label>
                        <select class="form-control" id="basic" name="category">
                            <option selected disabled>Odaberite kategoriju</option>
                            <option value="!NULL">Sve kategorije</option>
                            <option value="NULL">Ni jedna kategorija</option>
                            @foreach($categories as $category)
                                
                              <option value="{{ $category->category_id }}">{{ $category->title }}</option>
                                    
                            @endforeach
                        </select>
                </div>

                <div class="form-group">
                    <label for="basic"><h3>Odaberite po kojim tagovima želite izlistati jela:</h3></label>
                        <select class="form-control" id="basic" name="tags[]" multiple>
                            @foreach($tags as $tag)
                                
                              <option value="{{ $tag->tag_id }}">{{ $tag->title }}</option>
                                    
                            @endforeach
                        </select>
                </div>

                <div class="form-group">
                      <div class="checkbox">
                        <label><input type="checkbox" name="ingredientShow">Da li žeite i listu sastojaka od dobivenih jela?</label>
                      </div>
                      <div class="checkbox">
                        <label><input type="checkbox" name="tagShow">Da li žeite i listu tagova od dobivenih jela?</label>
                      </div>
                      <div class="checkbox disabled">
                        <label><input type="checkbox" name="categoryShow">Da li žeite i kategoriju od dobivenih jela?</label>
                      </div>
                  </div>

            <button type="submit" class="btn btn-primary">Unesi</button>
            <a href="/jela" class="btn btn-primary">Natrag</a> 
        
    </form>
</div>

@endsection