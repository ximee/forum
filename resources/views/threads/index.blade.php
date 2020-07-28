@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Forum threads</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @forelse($threads as $thread)
                            <article>
                                <div class="level">
                                    <h4 class="flex">
                                        <a href="{{$thread->path()}}">
                                            @if($thread->hasUpdatesFor(auth()->user()))
                                                <strong>{{$thread->title}}</strong>
                                            @else
                                                {{$thread->title}}
                                            @endif
                                        </a>
                                    </h4>

                                    <strong><a href="{{$thread->path()}}">{{$thread->replies_count}} {{str_plural('reply', $thread->replies_count)}}</a></strong>

                                </div>

                                <div class="body">{{$thread->body}}</div>

                                <hr>
                            </article>
                        @empty
                            <p>There are no articles yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection