<div class="row">
    <div class="col-12">
        <div class="page">
            <aside class="left">
                <div class="logo"><a href="/login"><img src="{{ mix('/resources/images/branding.svg') }}"></a></div>
            </aside>
            <main>
                <div class="padding">
                    <div class="infoText">
                        <h1 class="h2 headline"><span>Welcome back</span><span class="small">Please log into your
                                account to access your dashboard</span></h1>
                    </div>
                    <div class="overview">
                        <form id="loginAuthentication" action="/login" method="POST">
                            @csrf
                            @if ($errors->has('message'))
                                <div class="error">
                            @endif
                            <input id="email" name="email" required="true" placeholder="Your email" type="email"
                                autofocus="true">
                            @if ($errors->has('message'))
                    </div>
                    @endif
                    @if ($errors->has('message'))
                        <div class="error">
                    @endif
                    <input id="password" name="password" required="true" placeholder="Your password" type="password">
                    @if ($errors->has('message'))
                </div>
                @endif
                <label for="remember">
                    <input id="remember" type="checkbox" name="remember">
                    Remember Me
                </label>
                <input type="submit" value="Sign in">
                </form>
                <a class="btn secondary full" style="margin-top: 10px" href="/forgot">Forgot your password?</a>
        </div>
    </div>
    </main>
</div>
</div>
</div>
