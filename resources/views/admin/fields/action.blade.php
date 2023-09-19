<div class="link-wrapper" @style(['margin-top: 35px'])>
    <a href={{ $href }} @style([
        '-moz-osx-font-smoothing:grayscale',
        '-webkit-appearance: none',
        '-webkit-font-smoothing:antialiased',
        'background-color: transparent',
        'background:#3278b8',
        'border-radius: 2px',
        'border: 0 none',
        'color:#fff',
        'cursor: pointer',
        'display: inline-block',
        'font-size: 1em',
        'height: 40px',
        'letter-spacing: inherit',
        'line-height: 38px',
        'margin: 0',
        'outline: none',
        'overflow: hidden',
        'padding: 0 30px',
        'text-align: center',
        'text-decoration: none',
        'text-overflow: ellipsis',
        'transition: color .2s linear,border-color .2s linear,background-color .2s linear',
        'white-space: nowrap',
    ])>
        {{ $action }}
    </a>
</div>
