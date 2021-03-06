@extends('layouts.app')

@section('content')
    <thread-view-component :initial-replies-count="{{$thread->replies_count}}" inline-template >
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="level">
                            <span class="flex"><a href="{{route('profile', $thread->creator->name)}}">{{$thread->creator->name}}</a> posted:
                                {{$thread->title}}
                            </span>
                                @can('update', $thread)
                                    <form action="{{$thread->path()}}" method="post">
                                        {{csrf_field()}}
                                        {{method_field('DELETE')}}
                                        <button type="submit" class="btn btn-link">Delete this post</button>
                                    </form>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body">
                            {{$thread->body}}
                        </div>
                    </div>

                    <replies-component @added="repliesCount++" @removed="repliesCount--"></replies-component>

                    {{--<div class="m-2">--}}
                    {{--{{$replies->links()}}--}}
                    {{--</div>--}}

                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">

                        </div>

                        <div class="card-body">
                            <p>
                                This thread was publiched {{$thread->created_at->diffForHumans()}} by <a
                                        href="#">{{$thread->creator->name}}</a> and currently
                                has <span v-text="repliesCount"></span> {{str_plural('comment', $thread->replies_count)}}.
                            </p>

                            <p>
                                <subscribe-button-component :active="{{json_encode($thread->is_subscribed_to)}}"></subscribe-button-component>
                            </p>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </thread-view-component>
@endsection