<li class="nav-item">
    <a class="nav-link" href="{{ route('reviewer.home') }}">{{ __('Inicio') }}</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('reviewer.folios') }}">{{ __('Depósitos') }}</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('reviewer.codes') }}">{{ __('Codigos') }}</a>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        Cortes
    </a>
    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
        <li>
            <a class="dropdown-item" href="{{ route('reviewer.revisions') }}">{{ __('Lista') }}</a>
        </li>
        <li><a class="dropdown-item" href="{{ route('reviewer.categories') }}">Categorias</a></li>
        <li><a class="dropdown-item" href="{{ route('reviewer.pagos') }}">Pagos</a></li>
    </ul>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('reviewer.schools') }}">{{ __('Escuelas') }}</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('reviewer.books') }}">{{ __('Libros') }}</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('reviewer.emails') }}">{{ __('Coreos') }}</a>
</li>