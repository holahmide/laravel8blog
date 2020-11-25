@props(['post' => $post])

<div class="mb-4">
    <a href="{{ route('users.posts', auth()->user()) }}" class="font-bold">{{ $post->user->name }}</a> 
    <span class="text-gray-600 text-sm">{{ $post->created_at->diffForHumans() }}</span>
    <p class="mb-2">{{ $post->body }}</p>

    @can('delete', $post)
        <form action="{{ route('posts.destroy', $post) }}" method="post">
            @csrf
            @method('DELETE')
            <button class="text-blue-500" type="submit">Delete</button>
        </form>
    @endcan

    <div class="flex items-center">
        @auth
        @if(!$post->likedBy(auth()->user()))
            <form action="{{ route('posts.likes', $post) }}" method="post" class="mr-1">
                @csrf
                <button type="submit" class="text-white bg-green-500 p-2 rounded-sm">Like</button>
            </form>
        @else
            <form action="{{ route('posts.likes', $post) }}" method="post" class="mr-1">
                @csrf
                @method('DELETE') {{-- Method Spoofing --}}
                <button type="submit" class="text-white bg-gray-500 p-2 rounded-sm">Unlike</button>
            </form>
        @endif
        @endauth
        <span>{{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}</span>
    </div>
</div>