@extends('layouts.app')

@section('content')

    <div class="container">
        
            
        @php
            $dishId = NULL
        @endphp
        
        @foreach($dishesCollection as $title)
       
        @if ($title->count() == 0)
            <h2>{{ 'Ne postoji ni jedno jelo iz odabrane kategorije sa odabranim tagom!'}}</h2>
        @endif

        @foreach ($title as $dish)
            
            @if ($dish->id == $dishId)
                @continue;
            @endif
            <h2><li>{{ $dish->title }} - {{ $dish->description }}</li></h2>

            @if ($ingredients)
                @foreach ($ingredients as $ingredient)
                <li>{{ 'Sastojci: ' . $ingredient->title . ', slug: ' . $ingredient->slug }}</li>
                @endforeach     
            @endif

            @if ($categoryShow)
                @foreach ($categoryShow as $category)
                ><li>{{ 'Kategorije: ' . $category->title . ', slug: ' . $category->slug}}</li>
                @endforeach     
            @endif

            @if ($tagShow)
                @foreach ($tagShow as $tag)
                <li>{{ 'Tagovi: ' . $tag->title . ', slug: ' . $tag->slug}}</li>
                @endforeach     
            @endif
       @php
           $dishId = $dish->id
       @endphp
            
        @endforeach
        @endforeach
        {{ $paginatedItems->links() }}   
    </div>
    
@endsection