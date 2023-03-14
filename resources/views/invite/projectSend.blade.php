<p>Пользователь: {{$sender}} приглашает вас в проект: {{$project->name}}.</p>

<a href="{{ route('project.accept', ['token' => $invite->token]) }}">Нажмите сюда</a> чтобы принять приглашение!
