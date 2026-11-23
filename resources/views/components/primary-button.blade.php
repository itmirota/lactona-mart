<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-md border border-transparent bg-brand-dark px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-brand-accent focus:bg-brand-accent focus:outline-none focus:ring-2 focus:ring-brand-dark focus:ring-offset-2 active:bg-brand-dark']) }}>
    {{ $slot }}
</button>
