@props(['onclick' => null])

<button type="button"
        {{ $attributes->merge(['class' => 'sigma-close-button']) }}
        @if ($onclick)
            onclick="{{ $onclick }}"
        @else
            data-dismiss="modal"
        @endif
        aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
