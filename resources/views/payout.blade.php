<div class="row">
    <div class="col-12">
        <div class="page">
            <aside class="left">
                <div class="user"><a href="/profile"><img src="{!! $avatar ?? mix('/resources/images/user.jpg') !!}"></a>
                    <p class="h3 headline">{!! $user['name'] !!} <span class="small">Artist</span></p>
                </div>

                <div class="navigation">
                    <nav>
                        <ul>
                            <li class="overview"><a href="/dashboard"><i
                                        class="fa fa-house"></i><span>Overview</span></a></li>
                            <li class="releases"><a href="/releases"><i
                                        class="fa fa-music"></i><span>Releases</span></a></li>
                            <li class="payout"><a class="active" href="/payout"><i
                                        class="fa fa-money-check"></i><span>Payout</span></a></li>
                            <li class="profile"><a href="/profile"><i class="fa fa-user"></i><span>Profile</span></a>
                            </li>
                            <li><a class="logout" href="/logout"><i
                                        class="fa fa-right-from-bracket"></i><span>Logout</span></a></li>
                        </ul>
                    </nav>
                </div>
            </aside>
            <main>
                <div class="padding">
                    <div class="content">
                        <h1>Payout</h1>
                        <div class="row">
                            <div class="col-12">
                                <form id="payoutForm" method="POST" action="/payout">
                                    @csrf
                                    <div class="box">
                                        <p class="headline">Get your earnings</p>
                                        <div class="row">
                                            <div class="col-12 col-lg-6">
                                                <label for="paypal">Paypal email</label>
                                                <input id="paypal" required="true" type="text" name="paypal"
                                                    value="{!! $user['email'] !!}">
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                <label for="money">Payout amount <small>(minimum 5€)</small></label>
                                                <input name="money" required="true" type="number" min="0"
                                                    max="{!! $balance !!}" step="0.01"
                                                    value="{!! $balance !!}" id="money">
                                            </div>
                                            <div class="col-12">
                                                <label for="accept">
                                                    <input type="checkbox" id="accept" name="accept"
                                                        required="true">
                                                    I agree that any PayPal fees incurred will be deducted from the
                                                    amount requested.
                                                </label>
                                                <div class="flex">
                                                    <input class="btn" type="submit" value="Request now">
                                                    <input class="btn secondary" type="reset" value="Cancel">
                                                </div>
                                                <div class="output">
                                                    @if ($errors->has('payoutMessage'))
                                                        <div class="border-error">{{ $errors->first('payoutMessage') }}
                                                        </div>
                                                    @endif
                                                    @if (session()->has('payoutSuccess'))
                                                        <div class="border-success">{{ session('payoutSuccess') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<script src="/js/analytics.js"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-9P4DS23DR6"></script>
