<p>Пользователь: {{$sender}} приглашает вас в организацию: {{$organization}}.</p>

<a href="{{ route('accept', ['token' => $invite->token]) }}">Нажмите сюда</a> чтобы принять приглашение!
