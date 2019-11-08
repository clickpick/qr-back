<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1,width=device-width,height=device-height,viewport-fit=cover,shrink-to-fit=no,uc-fitscreen=yes,target-densityDpi=device-dpi">
        <title>QR Game</title>
        <link href="{{ mix('css/face.css') }}" rel="stylesheet">
    </head>
    <body>
        <main class="wrapper">
            <div class="wrapper-bg"
                style="background-image: url({{\App\Project::getActive()->getFirstMedia('banner')->getFullUrl('card')}})"></div>
            <div class="container">
                <h1>{{\App\Project::getActive()->name}}</h1>
            </div>
            <div class="container">
                <h2>Кол-во пользователей: {{\App\User::count()}}</h2>
                <h3>Активировано символов: {{number_format(DB::table('activated_project_key_user')
                ->join('project_keys', 'activated_project_key_user.project_key_id', '=', 'project_keys.id')
                ->where('project_keys.project_id', \App\Project::getActive()->id)
                ->count())}}</h3>
                <h3>
                    <h3>Собрано пожертвований: {{number_format(\App\VkPayOrder::where('status', 1)->sum('amount'))}}</h3>
                    @php
                        $keysCount = \App\Project::getActive()->projectKeys()->count();
                    @endphp
                    <h3>Кол-во победителей: {{DB::table('activated_project_key_user')
                    ->join('project_keys', 'activated_project_key_user.project_key_id', '=', 'project_keys.id')
                    ->where('project_keys.project_id', \App\Project::getActive()->id)
                    ->having(DB::raw('count(activated_project_key_user.id)'), '>=', $keysCount)
                    ->groupBy('activated_project_key_user.user_id')
                    ->select(DB::raw('count(activated_project_key_user.id) as count'))
                    ->get()->count()}} / {{\App\Project::getActive()->winners_count}}</h3>
                </h3>
            </div>
            <div class="container grid">
                @foreach(\App\Project::getActive()->projectKeys as $projectKey)
                    <div class="col">
                        <h3>Символ {{$projectKey->value}}</h3>
                        <h3>Кол-во человек, данным симолов: {{number_format($projectKey->users()->count())}}</h3>
                        <h3>Активировали данный символ: {{number_format(DB::table('activated_project_key_user')->where('project_key_id', $projectKey->id)->count())}}</h3>
                    </div>
                @endforeach
            </div>
        </main>
        <script src="{{ mix('js/face.js') }}"></script>
    </body>
</html>
