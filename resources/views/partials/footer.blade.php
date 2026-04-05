<footer class="mobile-nav">
  <nav>
    <ul class="nav-list">
      <li class="nav-item"><a href="{{ url('/') }}" class="{{ Request::is('/') ? 'active' : '' }}"><span>🏠</span><span>Accueil</span></a></li>
      <li class="nav-item"><a href="{{ url('/carte') }}" class="{{ Request::is('carte') ? 'active' : '' }}"><span>🗺️</span><span>Carte</span></a></li>
      <li class="nav-item"><a href="{{ url('/signalement') }}" class="{{ Request::is('signalement') ? 'active' : '' }}"><span>📢</span><span>Signaler</span></a></li>
      <li class="nav-item"><a href="{{ url('/notifications') }}" class="{{ Request::is('notifications') ? 'active' : '' }}"><span>🔔</span><span>Notifs</span></a></li>
      <li class="nav-item"><a href="{{ url('/profil') }}" class="{{ Request::is('profil') ? 'active' : '' }}"><span>👤</span><span>Profil</span></a></li>
    </ul>
  </nav>
</footer>
