@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
 $alignmentClasses = match ($align) {
    'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
    'top' => 'origin-top',
    default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};

 $width = match ($width) {
    '48' => 'w-48',
    default => $width,
};
@endphp

<div class="dd-wrapper" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = !open" class="dd-trigger">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute z-50 mt-2 {{ $width }} dd-panel {{ $alignmentClasses }}"
            style="display: none;"
            @click="open = false">
        <div class="dd-body {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>

<style>
/* ===== SELECT TRIGGER ===== */
.dd-trigger {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border-radius: 10px;
    border: 1.5px solid var(--border);
    background: var(--card-bg);
    cursor: pointer;
    transition: all 0.2s;
    color: var(--text-dark);
    font-size: .88rem;
    font-weight: 500;
    white-space: nowrap;
    user-select: none;
    position: relative;
    padding-right: 30px;
}
.dd-trigger::after {
    content: '';
    position: absolute;
    right: 12px;
    top: 50%;
    width: 6px;
    height: 6px;
    border-right: 2px solid var(--text-muted);
    border-bottom: 2px solid var(--text-muted);
    transform: translateY(-50%) rotate(45deg);
    transition: transform 0.2s;
}
.dd-trigger:hover {
    border-color: var(--primary);
    background: var(--primary-light);
    color: var(--primary);
}
.dd-trigger:hover::after {
    border-color: var(--primary);
    transform: translateY(-50%) rotate(225deg);
}
.dd-trigger[aria-expanded="true"]::after {
    transform: translateY(-50%) rotate(-45deg);
    border-color: var(--primary);
}

/* ===== DROPDOWN PANEL ===== */
.dd-panel {
    background: #fff;
    border-radius: 14px;
    border: 1px solid var(--border);
    box-shadow: 0 20px 50px rgba(0,0,0,.08), 0 4px 15px rgba(0,0,0,.03);
    overflow: hidden;
}
.dd-body {
    padding: 6px;
}

/* ===== MENU ITEMS ===== */
.dd-body a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 8px;
    color: var(--text-dark);
    font-size: .86rem;
    font-weight:500;
    text-decoration: none;
    transition: all 0.15s;
}
.dd-body a:hover {
    background: var(--primary-light);
    color: var(--primary);
    font-weight: 600;
}
.dd-body a:active {
    background: #d1fae5;
}
.dd-body a.active {
    background: var(--primary);
    color: #fff;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(5,150,105,.2);
}

/* ===== DIVIDER ===== */
.dd-body hr {
    border: none;
    border-top: 1px solid var(--border);
    margin: 4px 0;
}

/* ===== HEADER (opsional, kalau mau pakai di dropdown header) ===== */
.dd-header {
    padding: 8px 14px 4px;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: var(--text-muted);
    pointer-events: none;
}
</style>
@endsection