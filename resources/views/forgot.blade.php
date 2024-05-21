<div class="row">
    <div class="col-12">
        <div class="page">
            <aside class="left">
                <div class="logo"><a href="/login"><img src="{{ mix('/resources/images/branding.svg') }}"></a></div>
            </aside>
            <main>
                <div class="padding">
                    @if ($errors->has('send'))
                        <div class="infoText">
                            <h1 class="h2 headline noMargin"><span>Successful requested</span> <span class="small">If
                                    the given email is stored in our system, you will receive an email with further
                                    information shortly. Otherwise if you have any questions please contact Raphael or
                                    our team!</span></h1>
                        </div>
                        <script>
                            setTimeout(function() {
                                window.location.href = "/login";
                            }, 10000);
                        </script>
                    @else
                        <div class="infoText">
                            <h1 class="h2 headline"><span>Reset your password</span> <span class="small">Please type in
                                    your email address to receive your password</span></h1>
                        </div>
                        <div class="overview">
                            <form id="forgotAuthentication" action="/forgot" method="POST">
                                @csrf
                                <input id="email" name="email" required="true" placeholder="Your email"
                                    type="email" autofocus="true">
                                <input type="submit" value="Request">

                            </form>
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</div>
